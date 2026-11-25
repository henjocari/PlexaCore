<?php

namespace App\Http\Controllers;

use App\Support\NotificadorPqrs;
use App\Models\SilogtranPqrs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Validation\Rule;

class SilogtranPqrsController extends Controller
{
    /* =====================================================================
     *  VISTA INTERNA (PlexaCore, con login)
     * ===================================================================== */

    /**
     * Formulario PQRS + listado de solicitudes radicadas.
     */
    public function index(Request $request)
    {
        return view('silogtran_pqrs', $this->datosVista($request, null) + [
            'usuario' => Auth::user(),
            'embed'   => false,
        ]);
    }

    /**
     * Radica una nueva PQRS desde PlexaCore.
     */
    public function store(Request $request)
    {
        $data = $request->validate($this->reglas(), $this->mensajes());

        [$pqrs, $correo] = $this->radicar($request, $data, null, Auth::id());

        return redirect()->route('silogtran.pqrs')
            ->with('pqrs_radicado', $pqrs->radicado)
            ->with('pqrs_tipo', $pqrs->tipo)
            ->with('aviso_correo', $correo->resumen($this->esGestor()));
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
        $aviso = null;
        if ($estadoAnterior !== $pqrs->estado || $respuestaAnterior !== $pqrs->respuesta) {
            $correo = NotificadorPqrs::actualizada($pqrs);
            if ($correo->todoOk()) {
                $mensaje .= " Se notificó a {$pqrs->email}.";
            } else {
                $aviso = $correo->resumen(true);
            }
        }

        return redirect()->route('silogtran.pqrs')->with('success', $mensaje)->with('aviso_correo', $aviso);
    }

    /* =====================================================================
     *  VISTA EMBEBIDA EN SILOGTRAN (sin login, filtrada por NIT)
     *  GET  /silogtran/pqrs/embed?nit=900123456[&exp=...][&firma=...]
     * ===================================================================== */

    public function embed(Request $request)
    {
        $nit = $this->nitAutorizado($request);

        // Tras radicar se vuelve aquí con ?radicado=...; solo se muestra si es de este NIT
        $radicadoOk = null;
        if ($request->filled('radicado')) {
            $radicadoOk = SilogtranPqrs::where('nit_cliente', $nit)
                ->where('radicado', $request->input('radicado'))
                ->first();
        }

        return $this->respuestaEmbed($request, $nit, [
            'radicadoOk' => $radicadoOk,
        ]);
    }

    public function embedStore(Request $request)
    {
        $nit = $this->nitAutorizado($request);

        // Dentro de un iframe el navegador no envía la cookie de sesión, así que los
        // errores no se pueden "flashear" con redirect: se vuelve a pintar la vista.
        $validator = Validator::make($request->all(), $this->reglas(), $this->mensajes());
        if ($validator->fails()) {
            $request->flash();
            return $this->respuestaEmbed($request, $nit, [
                'errors' => (new ViewErrorBag)->put('default', $validator->errors()),
            ], 422);
        }

        [$pqrs, $correo] = $this->radicar($request, $validator->validated(), $nit, null);

        // En el iframe no hay sesión: si el acuse no salió, se avisa con ?correo=0
        return redirect()->route('silogtran.pqrs.embed', $this->parametrosEmbed($request) + [
            'radicado' => $pqrs->radicado,
        ] + ($correo->falloSolicitante() ? ['correo' => 0] : []));
    }

    /* =====================================================================
     *  SEGUIMIENTO DESDE EL CORREO (enlace firmado, sin login)
     *  GET /pqrs/seguimiento/{radicado}?signature=...
     * ===================================================================== */

    public function seguimiento(string $radicado)
    {
        $pqrs = SilogtranPqrs::where('radicado', $radicado)->firstOrFail();

        return response()
            ->view('pqrs_seguimiento', ['p' => $pqrs])
            ->header('X-Robots-Tag', 'noindex, nofollow');
    }

    /**
     * Enlace permanente y firmado a la página de seguimiento de una PQRS.
     */
    public static function urlSeguimiento(SilogtranPqrs $pqrs): string
    {
        return \Illuminate\Support\Facades\URL::signedRoute('pqrs.seguimiento', ['radicado' => $pqrs->radicado]);
    }

    /* =====================================================================
     *  AUXILIARES
     * ===================================================================== */

    /**
     * Valida el NIT de la URL y, si hay clave configurada, su firma.
     */
    private function nitAutorizado(Request $request): string
    {
        $nit = preg_replace('/[^0-9\-]/', '', (string) $request->query('nit', $request->input('nit', '')));

        if (!preg_match('/^[0-9]{5,15}(-[0-9])?$/', $nit)) {
            abort(400, 'NIT de cliente no válido.');
        }

        $secreto = (string) config('silogtran.embed_secret');
        if ($secreto !== '') {
            $exp   = (string) $request->query('exp', $request->input('exp', ''));
            $firma = (string) $request->query('firma', $request->input('firma', ''));
            $base  = $exp !== '' ? "{$nit}|{$exp}" : $nit;

            if (!hash_equals(hash_hmac('sha256', $base, $secreto), $firma)) {
                Log::warning("PQRS embed: firma inválida para NIT {$nit} desde {$request->ip()}");
                abort(403, 'Acceso no autorizado.');
            }
            if ($exp !== '' && (!ctype_digit($exp) || (int) $exp < time())) {
                abort(403, 'El enlace venció. Vuelve a ingresar desde Silogtran.');
            }
        }

        return $nit;
    }

    /** Parámetros que deben viajar en cada enlace/formulario de la vista embebida. */
    private function parametrosEmbed(Request $request): array
    {
        return array_filter([
            'nit'   => $request->input('nit'),
            'exp'   => $request->input('exp'),
            'firma' => $request->input('firma'),
        ], fn ($v) => $v !== null && $v !== '');
    }

    private function respuestaEmbed(Request $request, string $nit, array $extra = [], int $status = 200)
    {
        $vista = view('silogtran_pqrs', array_merge($this->datosVista($request, $nit), [
            'usuario'      => null,
            'embed'        => true,
            'nitCliente'   => $nit,
            'embedParams'  => $this->parametrosEmbed($request),
            'radicadoOk'   => null,
        ], $extra));

        // Solo Silogtran (y el propio sitio) pueden mostrar esta página en un iframe
        $origenes = trim((string) config('silogtran.embed_origins'));

        return response($vista, $status)
            ->header('Content-Security-Policy', "frame-ancestors 'self'" . ($origenes !== '' ? " {$origenes}" : ''));
    }

    /**
     * Listado, filtros y contadores. Con $nit solo se ven las PQRS de ese cliente.
     */
    private function datosVista(Request $request, ?string $nit): array
    {
        $base = fn () => SilogtranPqrs::query()->when($nit, fn ($q) => $q->where('nit_cliente', $nit));

        $query = $base();

        if ($request->filled('busqueda')) {
            $b = $request->input('busqueda');
            $query->where(function ($q) use ($b) {
                $q->where('radicado', 'LIKE', "%{$b}%")
                  ->orWhere('nombre', 'LIKE', "%{$b}%")
                  ->orWhere('documento', 'LIKE', "%{$b}%")
                  ->orWhere('nit_cliente', 'LIKE', "%{$b}%")
                  ->orWhere('asunto', 'LIKE', "%{$b}%")
                  ->orWhere('manifiesto', 'LIKE', "%{$b}%");
            });
        }
        if ($request->filled('tipo'))   { $query->where('tipo', $request->input('tipo')); }
        if ($request->filled('estado')) { $query->where('estado', $request->input('estado')); }

        $pqrs = $query->orderBy('created_at', 'desc')->paginate(8)
            ->appends($request->except(['radicado', '_token', 'page']));

        $stats = [
            'total'    => $base()->count(),
            'radicado' => $base()->where('estado', 'Radicado')->count(),
            'gestion'  => $base()->where('estado', 'En Gestion')->count(),
            'cerrado'  => $base()->where('estado', 'Cerrado')->count(),
        ];

        return [
            'pqrs'       => $pqrs,
            'stats'      => $stats,
            'categorias' => SilogtranPqrs::CATEGORIAS,
        ];
    }

    private function reglas(): array
    {
        return [
            'tipo'           => ['required', Rule::in(SilogtranPqrs::TIPOS)],
            'categoria'      => ['required', Rule::in(SilogtranPqrs::CATEGORIAS)],
            'prioridad'      => ['required', Rule::in(['Baja', 'Media', 'Alta'])],
            'nombre'         => 'required|string|max:150',
            'tipo_documento' => ['required', Rule::in(['CC', 'CE', 'NIT', 'PAS'])],
            'documento'      => 'required|string|max:30',
            'email'          => 'required|email|max:150',
            'telefono'       => 'nullable|string|max:30',
            'pais'           => 'required|string|max:80',
            'departamento'   => 'required|string|max:80',
            'ciudad'         => 'required|string|max:80',
            'direccion'      => 'nullable|string|max:200',
            'producto_servicio' => ['required', Rule::in(SilogtranPqrs::PRODUCTOS)],
            'manifiesto'     => 'nullable|string|max:50',
            'asunto'         => 'required|string|max:150',
            'descripcion'    => 'required|string|min:20|max:3000',
            'adjunto'        => 'nullable|file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx',
            'acepta'         => 'accepted',
        ];
    }

    private function mensajes(): array
    {
        return [
            'descripcion.min' => 'La descripción debe tener al menos 20 caracteres.',
            'acepta.accepted' => 'Debe aceptar la política de tratamiento de datos.',
            'adjunto.max'     => 'El archivo no puede superar 10 MB.',
            'pais.required'         => 'Indica el país.',
            'departamento.required' => 'Indica el departamento.',
            'ciudad.required'       => 'Indica la ciudad.',
            'producto_servicio.required' => 'Selecciona el producto o servicio.',
        ];
    }

    /**
     * Guarda la PQRS, el adjunto y envía los correos (buzón + acuse al solicitante).
     *
     * @return array{0: SilogtranPqrs, 1: NotificadorPqrs}
     */
    private function radicar(Request $request, array $data, ?string $nit, $userId): array
    {
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

        $data['radicado']    = $this->generarRadicado($data['tipo']);
        $data['estado']      = 'Radicado';
        $data['user_id']     = $userId;
        $data['nit_cliente'] = $nit;

        $pqrs = SilogtranPqrs::create($data);

        // Buzón interno (PQRS_MAIL_TO) + acuse al solicitante
        return [$pqrs, NotificadorPqrs::radicada($pqrs)];
    }

    private function esGestor(): bool
    {
        return in_array((int) (Auth::user()->rol ?? 0), [1, 2]);
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
