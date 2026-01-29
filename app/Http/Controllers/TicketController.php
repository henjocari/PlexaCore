<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\SolicitudViajeMail;
use App\Mail\RespuestaViajeMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


class TicketController extends Controller
{
    // VISTA 1: SOLICITAR
    public function verSolicitar()
    {
        $user = Auth::user();
        
        $misTickets = DB::table('3_tickets')
            ->where('user_id', $user->cedula)
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('solicitar_viaje', compact('misTickets'));
    }

    // VISTA 2: GESTIONAR
    public function verGestion()
    {
        $user = Auth::user();

        if(!in_array((int)$user->rol, [1, 2])) {
            return redirect()->route('tickets.solicitar')->with('error', 'No tienes permiso.');
        }

        $tickets = DB::table('3_tickets')
            ->join('usuarios', '3_tickets.user_id', '=', 'usuarios.cedula')
            ->select('3_tickets.*', 'usuarios.Nombre', 'usuarios.Apellido', 'usuarios.email')
            ->orderBy('3_tickets.created_at', 'desc')
            ->paginate(10);

        return view('gestion_viajes', compact('tickets'));
    }

    // GUARDAR SOLICITUD (CORREGIDO PARA "Ida y Vuelta")
    public function store(Request $request)
    {
        // 1. Validación
        $request->validate([
            'origen' => 'required', 
            'destino' => 'required', 
            'fecha_viaje' => 'required|date',
            'tipo_viaje' => 'required',
            'fecha_regreso' => 'nullable|date|after_or_equal:fecha_viaje',
        ]);

        // Validación extra: Si dice "Ida y Vuelta", debe tener fecha regreso
        if ($request->tipo_viaje == 'Ida y Vuelta' && !$request->fecha_regreso) {
            return back()->withErrors(['fecha_regreso' => 'La fecha de regreso es obligatoria.']);
        }

        // 2. Crear el Ticket en la BD
        $ticket = Ticket::create([
            'user_id'     => Auth::user()->cedula,
            'origen'      => $request->origen,
            'destino'     => $request->destino,
            'fecha_viaje' => $request->fecha_viaje,
            
            // SE GUARDA TEXTUAL "Ida y Vuelta" o "Solo Ida"
            'tipo_viaje'  => $request->tipo_viaje, 
            
            // Si es Ida y Vuelta, guardamos la fecha, si no, NULL
            'fecha_regreso' => ($request->tipo_viaje == 'Ida y Vuelta') ? $request->fecha_regreso : null,
            
            'descripcion' => $request->descripcion,
            'estado'      => 2 
        ]);

        // 3. Preparar datos para el correo
        $nombreEmpleado = Auth::user()->Nombre . ' ' . Auth::user()->Apellido;
        $urlParaAprobar = route('tickets.gestion'); 

        $datos = [
            'empleado' => $nombreEmpleado,
            'origen'   => $request->origen,
            'destino'  => $request->destino,
            'fecha'    => $request->fecha_viaje,
            'fecha_ida' => $request->fecha_viaje,
            'fecha_regreso' => $request->fecha_regreso,
            'tipo'     => $request->tipo_viaje, // Ya viene con el texto correcto
            'url'      => $urlParaAprobar
        ];

        // 4. ENVIAR CORREOS (Usando tu correo para pruebas)
        try {
            Mail::to('royssimarra@gmail.com')->send(new SolicitudViajeMail($datos, 'jefe'));
            Mail::to('royssimarra@gmail.com')->send(new SolicitudViajeMail($datos, 'empleado'));
        } catch (\Exception $e) {
            // Ignoramos error de correo para no detener el proceso
        }

        return back()->with('success', 'Solicitud enviada y notificada al jefe.');
    }

    // GESTIONAR
    public function gestionar(Request $request, $id)
    {
        $ticket = Ticket::find($id);
        
        if ($request->accion == 'aprobar') {
            $request->validate(['archivo_tikete' => 'required|file|mimes:pdf,jpg,png']);
            
            $nombre = time().'_TIK_'.$ticket->id.'.'.$request->file('archivo_tikete')->getClientOriginalExtension();
            $path = public_path('archivos_tickets');
            if(!File::exists($path)) File::makeDirectory($path, 0755, true);
            
            $request->file('archivo_tikete')->move($path, $nombre);
            
            $ticket->archivo_tikete = $nombre;
            $ticket->estado = 1; 
            $rutaAdjunto = $path.'/'.$nombre;
        } else {
            $ticket->estado = 0; 
            $rutaAdjunto = null;
        }

        $ticket->mensaje_admin = $request->mensaje_admin;
        $ticket->save();

        // Buscamos al usuario dueño del ticket
        $user = DB::table('usuarios')->where('cedula', $ticket->user_id)->first();
        
        if ($user && $user->email) {
            $nombreUsuario = $user->Nombre; 

            $datos = [
                'estado' => $ticket->estado, 
                'nombre' => $nombreUsuario, 
                'mensaje' => $request->mensaje_admin, 
                'ruta_archivo' => $rutaAdjunto
            ];
            
            try {
                // Forzamos envío a tu correo para pruebas
                Mail::to('royssimarra@gmail.com')->send(new RespuestaViajeMail($datos));
            } catch (\Exception $e) {
                // Ignorar error de correo
            }
        }

        return back()->with('success', 'Gestionado correctamente.');
    }
}