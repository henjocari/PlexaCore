<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Mail\SolicitudViajeMail;
use App\Mail\RespuestaViajeMail;

class TicketController extends Controller
{
    public function verSolicitar()
    {
        $user = Auth::user();
        $misTickets = DB::table('3_tickets')
            ->where('user_id', $user->cedula)
            ->orderBy('created_at', 'desc')
            ->paginate(5);
        return view('solicitar_viaje', compact('misTickets'));
    }

    public function verGestion(Request $request)
    {
        $user = Auth::user();
        if(!in_array((int)$user->rol, [1, 2])) {
            return redirect()->route('tickets.solicitar')->with('error', 'No tienes permiso para acceder a gestión.');
        }

        $query = DB::table('3_tickets')
            ->join('usuarios', '3_tickets.user_id', '=', 'usuarios.cedula')
            ->select('3_tickets.*', 'usuarios.Nombre', 'usuarios.Apellido', 'usuarios.email');

        if ($request->filled('busqueda')) {
            $search = $request->input('busqueda');
            $query->where(function($q) use ($search) {
                $q->where('usuarios.Nombre', 'LIKE', "%{$search}%")
                  ->orWhere('usuarios.Apellido', 'LIKE', "%{$search}%")
                  ->orWhere('3_tickets.origen', 'LIKE', "%{$search}%")
                  ->orWhere('3_tickets.destino', 'LIKE', "%{$search}%")
                  ->orWhere('3_tickets.beneficiario_nombre', 'LIKE', "%{$search}%")
                  ->orWhere('3_tickets.beneficiario_cedula', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('estado')) { $query->where('3_tickets.estado', '=', $request->input('estado')); }
        if ($request->filled('fecha_inicio')) { $query->whereDate('3_tickets.fecha_viaje', '>=', $request->input('fecha_inicio')); }
        if ($request->filled('fecha_fin')) { $query->whereDate('3_tickets.fecha_viaje', '<=', $request->input('fecha_fin')); }

        $tickets = $query->orderBy('3_tickets.created_at', 'desc')->paginate(10)->appends($request->all());
        return view('gestion_viajes', compact('tickets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'beneficiario_nombre'    => 'required|string|max:255',
            'beneficiario_cedula'    => 'required|numeric',
            'beneficiario_fecha_nac' => 'required|date',
            'origen'                 => 'required|string', 
            'destino'                => 'required|string', 
            'fecha_viaje'            => 'required|date',
            'jornada_ida'            => 'required|string',
            'tipo_viaje'             => 'required|string',
            'fecha_regreso'          => 'nullable|date|after_or_equal:fecha_viaje',
            'jornada_regreso'        => 'nullable|string',
            'hospedaje'              => 'nullable|string',
        ]);

        if ($request->tipo_viaje == 'Ida y Vuelta' && !$request->fecha_regreso) {
            return back()->withErrors(['fecha_regreso' => 'La fecha de regreso es obligatoria para viajes Ida y Vuelta.']);
        }
        if ($request->tipo_viaje == 'Ida y Vuelta' && !$request->jornada_regreso) {
            return back()->withErrors(['jornada_regreso' => 'La jornada de regreso es obligatoria.']);
        }

        $ticket = Ticket::create([
            'user_id'                => Auth::user()->cedula,
            'beneficiario_nombre'    => $request->beneficiario_nombre,
            'beneficiario_cedula'    => $request->beneficiario_cedula,
            'beneficiario_fecha_nac' => $request->beneficiario_fecha_nac,
            'origen'                 => $request->origen,
            'destino'                => $request->destino,
            'fecha_viaje'            => $request->fecha_viaje,
            'jornada_ida'            => $request->jornada_ida,
            'tipo_viaje'             => $request->tipo_viaje, 
            'fecha_regreso'          => ($request->tipo_viaje == 'Ida y Vuelta') ? $request->fecha_regreso : null,
            'jornada_regreso'        => ($request->tipo_viaje == 'Ida y Vuelta') ? $request->jornada_regreso : null,
            'hospedaje'              => $request->hospedaje,
            'descripcion'            => $request->descripcion,
            'estado'                 => 2 
        ]);

        $datos = [
            'empleado'        => Auth::user()->Nombre . ' ' . Auth::user()->Apellido,
            'pasajero'        => $request->beneficiario_nombre,
            'origen'          => $request->origen,
            'destino'         => $request->destino,
            'fecha_ida'       => $request->fecha_viaje,
            'jornada_ida'     => $request->jornada_ida,
            'fecha_regreso'   => $request->fecha_regreso,
            'jornada_regreso' => $request->jornada_regreso,
            'hospedaje'       => $request->hospedaje,
            'tipo'            => $request->tipo_viaje,
            'url'             => route('tickets.gestion')
        ];

        $miCorreoDePrueba = 'roisroisomg@gmail.com'; 

        try { Mail::to($miCorreoDePrueba)->send(new SolicitudViajeMail($datos, 'jefe')); } catch (\Exception $e) {}
        try { Mail::to($miCorreoDePrueba)->send(new SolicitudViajeMail($datos, 'empleado')); } catch (\Exception $e) {}

        return back()->with('success', 'Solicitud enviada exitosamente.');
    }

    public function gestionar(Request $request, $id)
    {
        $ticket = Ticket::find($id);
        if(!$ticket) return back()->with('error', 'El ticket no existe.');

        $rutaAdjunto = null;

        if ($request->accion == 'aprobar') {
            $request->validate([
                'archivo_tikete'     => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'archivos_hoteles.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240' // Validación para múltiples archivos
            ], [
                'archivo_tikete.required'  => 'Es OBLIGATORIO subir el tiquete para aprobar.',
                'archivo_tikete.mimes'     => 'El tiquete debe ser formato PDF, JPG o PNG.',
                'archivos_hoteles.*.mimes' => 'Las reservas de hotel deben ser PDF, JPG o PNG.',
                'archivos_hoteles.*.max'   => 'Uno de los archivos de hotel es muy pesado (Max 10MB).'
            ]);

            $path = public_path('archivos_tickets');
            if(!File::exists($path)) File::makeDirectory($path, 0755, true);

            // 1. GUARDAR TIQUETE (1 Solo Archivo)
            if($request->hasFile('archivo_tikete')){
                $file = $request->file('archivo_tikete');
                $nombre = time().'_TIK_'.$ticket->id.'.'.$file->getClientOriginalExtension();
                $file->move($path, $nombre);
                $ticket->archivo_tikete = $nombre;
                $rutaAdjunto = $path.'/'.$nombre;
            }

            // 2. GUARDAR RESERVAS DE HOTELES (Múltiples Archivos)
            if($request->hasFile('archivos_hoteles')){
                $nombresHoteles = [];
                foreach($request->file('archivos_hoteles') as $indice => $fileHotel) {
                    $nombreH = time().'_HOTEL_'.$ticket->id.'_'.$indice.'.'.$fileHotel->getClientOriginalExtension();
                    $fileHotel->move($path, $nombreH);
                    $nombresHoteles[] = $nombreH;
                }
                // Los guardamos todos juntos separados por coma
                $ticket->archivos_hoteles = implode(',', $nombresHoteles);
            }

            $ticket->estado = 1; 

        } else {
            $ticket->estado = 0; 
            $rutaAdjunto = null;
        }

        $ticket->save();

        $user = DB::table('usuarios')->where('cedula', $ticket->user_id)->first();
        if ($user && $user->email) {
            $datos = [
                'estado' => $ticket->estado, 
                'nombre' => $user->Nombre, 
                'mensaje' => $request->input('mensaje_admin', ''), 
                'ruta_archivo' => $rutaAdjunto 
            ];
            try {
                $miCorreoDePrueba = 'roisroisomg@gmail.com'; 
                Mail::to($miCorreoDePrueba)->send(new RespuestaViajeMail($datos));
            } catch (\Exception $e) {}
        }

        return back()->with('success', 'La solicitud ha sido gestionada correctamente.');
    }
}