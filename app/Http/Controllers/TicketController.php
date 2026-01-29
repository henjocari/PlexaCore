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
            // OJO AQUÍ: Seleccionamos con las mayúsculas correctas
            ->select('3_tickets.*', 'usuarios.Nombre', 'usuarios.Apellido', 'usuarios.email')
            ->orderBy('3_tickets.created_at', 'desc')
            ->paginate(10);

        return view('gestion_viajes', compact('tickets'));
    }

    // GUARDAR SOLICITUD
    public function store(Request $request)
{
    $request->validate(['origen'=>'required', 'destino'=>'required', 'fecha_viaje'=>'required|date']);

    // 1. Crear el Ticket en la BD
    $ticket = Ticket::create([
        'user_id'     => Auth::user()->cedula,
        'origen'      => $request->origen,
        'destino'     => $request->destino,
        'fecha_viaje' => $request->fecha_viaje,
        'descripcion' => $request->descripcion,
        'estado'      => 2 
    ]);

    // 2. Preparar datos para el correo
    $nombreEmpleado = Auth::user()->Nombre . ' ' . Auth::user()->Apellido;
    
    // GENERAMOS EL LINK DIRECTO A LA GESTIÓN
    // Esto crea algo como: http://localhost:8000/gestion-viajes
    $urlParaAprobar = route('tickets.gestion'); 

    $datos = [
        'empleado' => $nombreEmpleado,
        'destino'  => $request->destino,
        'fecha'    => $request->fecha_viaje,
        'url'      => $urlParaAprobar // <--- Pasamos el link aquí
    ];

    // 3. ENVIAR LOS DOS CORREOS
    // IMPORTANTE: Si sigues en modo prueba (sin dominio verificado), 
    // AMBOS deben ser tu correo personal para que no falle.
    
    // --- CORREO 1: PARA EL JEFE (Quien aprueba) ---
    // En producción sería: Mail::to('jefe@plexa.co')...
    Mail::to('royssimarra@gmail.com')->send(new SolicitudViajeMail($datos, 'jefe'));

    // --- CORREO 2: PARA EL EMPLEADO (Confirmación) ---
    // En producción sería: Mail::to(Auth::user()->email)...
    Mail::to('royssimarra@gmail.com')->send(new SolicitudViajeMail($datos, 'empleado'));

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
            // CORRECCIÓN: Accedemos a 'Nombre' con mayúscula, tal cual está en tu base de datos
            $nombreUsuario = $user->Nombre; 

            $datos = [
                'estado' => $ticket->estado, 
                'nombre' => $nombreUsuario, 
                'mensaje' => $request->mensaje_admin, 
                'ruta_archivo' => $rutaAdjunto
            ];
            
            try {
                Mail::to($user->email)->send(new RespuestaViajeMail($datos));
            } catch (\Exception $e) {
                // Si falla el correo, no rompemos el proceso
            }
        }

        return back()->with('success', 'Gestionado correctamente.');
    }
}