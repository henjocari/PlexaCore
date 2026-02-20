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
    // =========================================================================
    // VISTA 1: FORMULARIO DE SOLICITUD (EMPLEADO)
    // =========================================================================
    public function verSolicitar()
    {
        $user = Auth::user();
        
        // Muestra los tickets del usuario logueado
        $misTickets = DB::table('3_tickets')
            ->where('user_id', $user->cedula)
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('solicitar_viaje', compact('misTickets'));
    }

    // =========================================================================
    // VISTA 2: GESTIÓN DE VIAJES (ADMINISTRADOR) - CON FILTROS
    // =========================================================================
    public function verGestion(Request $request)
    {
        $user = Auth::user();
        
        // 1. Verificación de permisos (Roles 1 y 2)
        if(!in_array((int)$user->rol, [1, 2])) {
            return redirect()->route('tickets.solicitar')->with('error', 'No tienes permiso para acceder a gestión.');
        }

        // 2. Consulta Base
        $query = DB::table('3_tickets')
            ->join('usuarios', '3_tickets.user_id', '=', 'usuarios.cedula')
            ->select('3_tickets.*', 'usuarios.Nombre', 'usuarios.Apellido', 'usuarios.email');

        // --- FILTRO 1: BÚSQUEDA GENERAL ---
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

        // --- FILTRO 2: ESTADO ---
        if ($request->filled('estado')) {
            $query->where('3_tickets.estado', '=', $request->input('estado'));
        }

        // --- FILTRO 3: FECHAS ---
        if ($request->filled('fecha_inicio')) {
            $query->whereDate('3_tickets.fecha_viaje', '>=', $request->input('fecha_inicio'));
        }
        if ($request->filled('fecha_fin')) {
            $query->whereDate('3_tickets.fecha_viaje', '<=', $request->input('fecha_fin'));
        }

        // 3. Ordenamiento y Paginación
        $tickets = $query->orderBy('3_tickets.created_at', 'desc')
                         ->paginate(10)
                         ->appends($request->all());

        return view('gestion_viajes', compact('tickets'));
    }

    // =========================================================================
    // FUNCIÓN: GUARDAR NUEVA SOLICITUD
    // =========================================================================
    public function store(Request $request)
    {
        // 1. VALIDAMOS TODOS LOS CAMPOS
        $request->validate([
            'beneficiario_nombre'    => 'required|string|max:255',
            'beneficiario_cedula'    => 'required|numeric',
            'beneficiario_fecha_nac' => 'required|date',
            'origen'                 => 'required|string', 
            'destino'                => 'required|string', 
            'fecha_viaje'            => 'required|date',
            'tipo_viaje'             => 'required|string',
            'fecha_regreso'          => 'nullable|date|after_or_equal:fecha_viaje',
        ]);

        if ($request->tipo_viaje == 'Ida y Vuelta' && !$request->fecha_regreso) {
            return back()->withErrors(['fecha_regreso' => 'La fecha de regreso es obligatoria para viajes Ida y Vuelta.']);
        }

        // 2. CREAMOS EL TICKET EN BD
        $ticket = Ticket::create([
            'user_id'                => Auth::user()->cedula,
            'beneficiario_nombre'    => $request->beneficiario_nombre,
            'beneficiario_cedula'    => $request->beneficiario_cedula,
            'beneficiario_fecha_nac' => $request->beneficiario_fecha_nac,
            'origen'                 => $request->origen,
            'destino'                => $request->destino,
            'fecha_viaje'            => $request->fecha_viaje,
            'tipo_viaje'             => $request->tipo_viaje, 
            'fecha_regreso'          => ($request->tipo_viaje == 'Ida y Vuelta') ? $request->fecha_regreso : null,
            'descripcion'            => $request->descripcion,
            'estado'                 => 2 
        ]);

        // 3. PREPARAMOS EL CORREO
        $nombreEmpleado = Auth::user()->Nombre . ' ' . Auth::user()->Apellido;
        $urlParaAprobar = route('tickets.gestion'); 

        $datos = [
            'empleado'      => $nombreEmpleado,
            'pasajero'      => $request->beneficiario_nombre,
            'origen'        => $request->origen,
            'destino'       => $request->destino,
            'fecha'         => $request->fecha_viaje,
            'fecha_ida'     => $request->fecha_viaje,
            'fecha_regreso' => $request->fecha_regreso,
            'tipo'          => $request->tipo_viaje,
            'url'           => $urlParaAprobar
        ];

        // ====================================================
        // --- ENVÍO DE CORREOS (MODO LOCAL/PRUEBA RESEND) ---
        // ====================================================
        // Como Resend gratis solo deja enviar a tu propio correo, forzamos la salida hacia ti.
        // Cuando vayas a subir la web a producción, cambias $miCorreoDePrueba por $emailJefe y Auth::user()->email
        
        $miCorreoDePrueba = 'roisroisomg@gmail.com'; 

        // 1. Simular correo al Jefe
        try {
            Mail::to($miCorreoDePrueba)->send(new SolicitudViajeMail($datos, 'jefe'));
        } catch (\Exception $e) {
            \Log::error("Error enviando correo al jefe: " . $e->getMessage());
        }

        // 2. Simular correo al Empleado (Confirmación)
        try {
            Mail::to($miCorreoDePrueba)->send(new SolicitudViajeMail($datos, 'empleado'));
        } catch (\Exception $e) {
            \Log::error("Error enviando correo al empleado: " . $e->getMessage());
        }

        return back()->with('success', 'Solicitud enviada exitosamente.');
    }

    // =========================================================================
    // FUNCIÓN: GESTIONAR (APROBAR / RECHAZAR)
    // =========================================================================
    public function gestionar(Request $request, $id)
    {
        $ticket = Ticket::find($id);
        
        if(!$ticket) {
            return back()->with('error', 'El ticket no existe.');
        }

        $rutaAdjunto = null;

        // --- CASO: APROBAR ---
        if ($request->accion == 'aprobar') {
            $request->validate([
                'archivo_tikete' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240'
            ], [
                'archivo_tikete.required' => 'Es OBLIGATORIO subir el tiquete para aprobar.',
                'archivo_tikete.mimes'    => 'El archivo debe ser formato PDF, JPG o PNG únicamente.',
                'archivo_tikete.max'      => 'El archivo pesa mucho (Máximo 10MB).'
            ]);

            if($request->hasFile('archivo_tikete')){
                $file = $request->file('archivo_tikete');
                $nombre = time().'_TIK_'.$ticket->id.'.'.$file->getClientOriginalExtension();
                $path = public_path('archivos_tickets');
                
                if(!File::exists($path)) File::makeDirectory($path, 0755, true);
                
                $file->move($path, $nombre);
                $ticket->archivo_tikete = $nombre;
                $rutaAdjunto = $path.'/'.$nombre;
            }

            $ticket->estado = 1; 

        // --- CASO: RECHAZAR ---
        } else {
            $ticket->estado = 0; 
            $rutaAdjunto = null;
        }

        $ticket->save();

        // --- NOTIFICAR AL USUARIO (PRUEBA LOCAL) ---
        $user = DB::table('usuarios')->where('cedula', $ticket->user_id)->first();
        
        if ($user && $user->email) {
            $nombreUsuario = $user->Nombre; 
            $datos = [
                'estado' => $ticket->estado, 
                'nombre' => $nombreUsuario, 
                'mensaje' => $request->input('mensaje_admin', ''), 
                'ruta_archivo' => $rutaAdjunto 
            ];
            
            try {
                // NOTA: Para pruebas locales, también forzaremos tu correo en la respuesta.
                $miCorreoDePrueba = 'roisroisomg@gmail.com'; 
                Mail::to($miCorreoDePrueba)->send(new RespuestaViajeMail($datos));
            } catch (\Exception $e) {
                \Log::error("Error enviando respuesta de viaje: " . $e->getMessage());
            }
        }

        return back()->with('success', 'La solicitud ha sido gestionada correctamente.');
    }
}