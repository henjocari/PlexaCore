<?php

namespace App\Http\Controllers;

use App\Mail\PqrsMail;
use App\Models\SilogtranPqrs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class SilogtranPqrsController extends Controller
{
    /**
     * Formulario PQRS + listado de solicitudes radicadas.
     */
    public function index(Request $request)
    {
        $query = SilogtranPqrs::query();

        if ($request->filled('busqueda')) {
            $b = $request->input('busqueda');
            $query->where(function ($q) use ($b) {
                $q->where('radicado', 'LIKE', "%{$b}%")
                  ->orWhere('nombre', 'LIKE', "%{$b}%")
                  ->orWhere('documento', 'LIKE', "%{$b}%")
                  ->orWhere('asunto', 'LIKE', "%{$b}%")
                  ->orWhere('manifiesto', 'LIKE', "%{$b}%");
            });
        }
        if ($request->filled('tipo'))   { $query->where('tipo', $request->input('tipo')); }
        if ($request->filled('estado')) { $query->where('estado', $request->input('estado')); }

        $pqrs = $query->orderBy('created_at', 'desc')->paginate(8)->appends($request->all());

        $stats = [
            'total'      => SilogtranPqrs::count(),
            'radicado'   => SilogtranPqrs::where('estado', 'Radicado')->count(),
            'gestion'    => SilogtranPqrs::where('estado', 'En Gestion')->count(),
            'cerrado'    => SilogtranPqrs::where('estado', 'Cerrado')->count(),
        ];

        $categorias = SilogtranPqrs::CATEGORIAS;
        $usuario    = Auth::user();

        return view('silogtran_pqrs', compact('pqrs', 'stats', 'categorias', 'usuario'));
    }

    /**
     * Radica una nueva PQRS.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'tipo'           => ['required', Rule::in(SilogtranPqrs::TIPOS)],
            'categoria'      => ['required', Rule::in(SilogtranPqrs::CATEGORIAS)],
            'prioridad'      => ['required', Rule::in(['Baja', 'Media', 'Alta'])],
            'nombre'         => 'required|string|max:150',
            'tipo_documento' => ['required', Rule::in(['CC', 'CE', 'NIT', 'PAS'])],
            'documento'      => 'required|string|max:30',
            'email'          => 'required|email|max:150',
            'telefono'       => 'nullable|string|max:30',
            'manifiesto'     => 'nullable|string|max:50',
            'asunto'         => 'required|string|max:150',
            'descripcion'    => 'required|string|min:20|max:3000',
            'adjunto'        => 'nullable|file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx',
            'acepta'         => 'accepted',
        ], [
            'descripcion.min' => 'La descripción debe tener al menos 20 caracteres.',
            'acepta.accepted' => 'Debe aceptar la política de tratamiento de datos.',
            'adjunto.max'     => 'El archivo no puede superar 10 MB.',
        ]);

        unset($data['acepta']);

        if ($request->hasFile('adjunto')) {
            $destino = public_path('archivos_pqrs');
            if (!File::isDirectory($destino)) {
                File::makeDirectory($destino, 0755, true);
            }
            $archivo = $request->file('adjunto');
            $nombreArchivo = time() . '_' . preg_replace('/[^A-Za-z0-9_.-]/', '_', $archivo->getClientOriginalName());
            $archivo->move($destino, $nombreArchivo);
            $data['adjunto'] = $nombreArchivo;
        }

        $data['radicado'] = $this->generarRadicado($data['tipo']);
        $data['estado']   = 'Radicado';
        $data['user_id']  = Auth::id();

        $pqrs = SilogtranPqrs::create($data);

        // 1) Aviso al buzón de PQRS (definido en PQRS_MAIL_TO)
        $destinatarios = config('mail.pqrs_to', []);
        if (!empty($destinatarios)) {
            try {
                Mail::to($destinatarios)->send(new PqrsMail($pqrs, 'empresa'));
                Log::info("PQRS {$pqrs->radicado}: correo enviado a " . implode(', ', $destinatarios));
            } catch (\Throwable $e) {
                Log::error("PQRS {$pqrs->radicado} - correo empresa: " . $e->getMessage());
            }
        } else {
            Log::warning("PQRS {$pqrs->radicado}: PQRS_MAIL_TO no está configurado en el .env");
        }

        // 2) Acuse de recibo al solicitante
        if (config('mail.pqrs_acuse', true)) {
            try {
                Mail::to($pqrs->email)->send(new PqrsMail($pqrs, 'solicitante'));
                Log::info("PQRS {$pqrs->radicado}: acuse enviado a {$pqrs->email}");
            } catch (\Throwable $e) {
                Log::error("PQRS {$pqrs->radicado} - correo solicitante: " . $e->getMessage());
            }
        }

        return redirect()->route('silogtran.pqrs')
            ->with('pqrs_radicado', $pqrs->radicado)
            ->with('pqrs_tipo', $pqrs->tipo);
    }

    /**
     * Cambia el estado / registra respuesta de una PQRS.
     */
    public function gestionar(Request $request, $id)
    {
        // Solo Super Administrador / Administradores gestionan
        if (!in_array((int) Auth::user()->rol, [1, 2])) {
            return redirect()->route('silogtran.pqrs')
                ->with('error', 'No tienes permiso para gestionar PQRS.');
        }

        $data = $request->validate([
            'estado'    => ['required', Rule::in(SilogtranPqrs::ESTADOS)],
            'respuesta' => 'nullable|string|max:3000',
        ]);

        $pqrs = SilogtranPqrs::findOrFail($id);
        $estadoAnterior = $pqrs->estado;
        $respuestaAnterior = $pqrs->respuesta;
        $pqrs->update($data);

        // Notificar al solicitante solo si cambió el estado o la respuesta
        $mensaje = "La PQRS {$pqrs->radicado} fue actualizada a \"{$pqrs->estado}\".";
        if ($estadoAnterior !== $pqrs->estado || $respuestaAnterior !== $pqrs->respuesta) {
            try {
                Mail::to($pqrs->email)->send(new PqrsMail($pqrs, 'actualizacion'));
                $mensaje .= " Se notificó a {$pqrs->email}.";
            } catch (\Throwable $e) {
                Log::error("PQRS {$pqrs->radicado} - correo actualización: " . $e->getMessage());
                $mensaje .= ' (No se pudo enviar el correo al solicitante.)';
            }
        }

        return redirect()->route('silogtran.pqrs')->with('success', $mensaje);
    }

    /**
     * Radicado con formato: PQRS-P-20261117-0001
     */
    private function generarRadicado(string $tipo): string
    {
        $fecha  = now()->format('Ymd');
        $prefijo = 'PQRS-' . strtoupper(substr($tipo, 0, 1)) . '-' . $fecha . '-';
        $consecutivo = SilogtranPqrs::whereDate('created_at', now()->toDateString())->count() + 1;

        do {
            $radicado = $prefijo . str_pad($consecutivo, 4, '0', STR_PAD_LEFT);
            $consecutivo++;
        } while (SilogtranPqrs::where('radicado', $radicado)->exists());

        return $radicado;
    }
}