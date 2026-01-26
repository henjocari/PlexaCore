<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\NuevoTicketMailable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class TicketController extends Controller
{
    /**
     * VISTA PRINCIPAL (Solo Activos)
     */
    public function index()
    {
        $user = Auth::user();
        $esPapelera = false; // Variable para saber que estamos en la lista normal

        if(in_array((int)$user->rol, [1, 2])) {
            $tickets = DB::table('3_tickets')
                ->join('usuarios', '3_tickets.user_id', '=', 'usuarios.cedula')
                ->select('3_tickets.*', 'usuarios.nombre', 'usuarios.apellido')
                ->where('3_tickets.estado', 1) // SOLO ACTIVOS
                ->orderBy('3_tickets.created_at', 'desc')
                ->paginate(10);
        } else {
            $tickets = DB::table('3_tickets')
                ->join('usuarios', '3_tickets.user_id', '=', 'usuarios.cedula')
                ->select('3_tickets.*', 'usuarios.nombre', 'usuarios.apellido')
                ->where('3_tickets.user_id', $user->cedula)
                ->where('3_tickets.estado', 1) // SOLO ACTIVOS
                ->orderBy('3_tickets.created_at', 'desc')
                ->paginate(10);
        }

        return view('tickets', compact('tickets', 'esPapelera'));
    }

    /**
     * VISTA PAPELERA (Solo Inactivos)
     */
    public function papelera()
    {
        $user = Auth::user();
        $esPapelera = true; // Variable para activar el modo Papelera en la vista

        if(in_array((int)$user->rol, [1, 2])) {
            $tickets = DB::table('3_tickets')
                ->join('usuarios', '3_tickets.user_id', '=', 'usuarios.cedula')
                ->select('3_tickets.*', 'usuarios.nombre', 'usuarios.apellido')
                ->where('3_tickets.estado', 0) // SOLO INACTIVOS (BORRADOS)
                ->orderBy('3_tickets.updated_at', 'desc') // Ordenar por fecha de borrado
                ->paginate(10);
        } else {
            $tickets = DB::table('3_tickets')
                ->join('usuarios', '3_tickets.user_id', '=', 'usuarios.cedula')
                ->select('3_tickets.*', 'usuarios.nombre', 'usuarios.apellido')
                ->where('3_tickets.user_id', $user->cedula)
                ->where('3_tickets.estado', 0) // SOLO INACTIVOS (BORRADOS)
                ->orderBy('3_tickets.updated_at', 'desc')
                ->paginate(10);
        }

        return view('tickets', compact('tickets', 'esPapelera'));
    }

    /**
     * GUARDAR TICKET
     */
    public function store(Request $request)
    {
        $request->validate([
            'archivo_tikete'  => 'required|file|mimes:pdf,jpg,jpeg,png|max:20480',
            'archivo_soporte' => 'required|file|mimes:pdf,jpg,jpeg,png|max:20480',
            'descripcion'     => 'nullable|string|max:500',
        ]);

        try {
            $destinoPath = public_path('archivos_tickets');
            if (!File::exists($destinoPath)) {
                File::makeDirectory($destinoPath, 0755, true);
            }

            $tiketeName  = time() . '_tik_' . $request->file('archivo_tikete')->getClientOriginalName();
            $soporteName = time() . '_sop_' . $request->file('archivo_soporte')->getClientOriginalName();

            $request->file('archivo_tikete')->move($destinoPath, $tiketeName);
            $request->file('archivo_soporte')->move($destinoPath, $soporteName);

            $ticket = Ticket::create([
                'user_id'         => Auth::user()->cedula,
                'archivo_tikete'  => $tiketeName,
                'archivo_soporte' => $soporteName,
                'descripcion'     => $request->descripcion,
                'estado'          => 1
            ]);

            try {
                $datosCorreo = [
                    'empleado'    => Auth::user()->nombre . ' ' . Auth::user()->apellido,
                    'descripcion' => $request->descripcion ?? 'Sin descripción',
                    'fecha'       => now()->format('d/m/Y H:i'),
                    'id_ticket'   => $ticket->id
                ];
                
                // CAMBIA ESTE CORREO
                Mail::to('jefe@plexa.co')->send(new NuevoTicketMailable($datosCorreo));

            } catch (\Exception $eMail) { }

            return back()->with('success', 'Ticket enviado correctamente.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al guardar: ' . $e->getMessage());
        }
    }

    /**
     * ENVIAR A LA PAPELERA
     */
    public function inactivar($id)
    {
        $ticket = Ticket::find($id);
        if ($ticket) {
            $ticket->estado = 0;
            $ticket->save();
            return back()->with('success', 'Ticket movido a la papelera.');
        }
        return back()->with('error', 'Ticket no encontrado.');
    }

    /**
     * RESTAURAR DE LA PAPELERA
     */
    public function activar($id)
    {
        $ticket = Ticket::find($id);
        if ($ticket) {
            $ticket->estado = 1;
            $ticket->save();
            return back()->with('success', 'Ticket restaurado exitosamente.');
        }
        return back()->with('error', 'Ticket no encontrado.');
    }
}