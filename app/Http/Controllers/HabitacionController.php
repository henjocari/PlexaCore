<?php

namespace App\Http\Controllers;

use App\Models\Habitacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth; // ✅ Importación para obtener el usuario
use App\Models\Conductor;
use App\Models\HistorialHabitacion; // ✅ Importación del nuevo modelo
use App\Exports\HistorialHabitacionesExport;
use Maatwebsite\Excel\Facades\Excel;


class HabitacionController extends Controller
{
    // 1️⃣ Leer todas las habitaciones
    public function hotel()
    {
        $habitaciones = Habitacion::with('hconductor')->orderBy('numero')->get();
        $conductores = Conductor::orderBy('nombre')->get(); 
    
        // ✅ Nueva consulta para el historial con el orden solicitado:
        // 1. Los que tienen check_out NULL (N/A) van primero (IS NULL DESC).
        // 2. Luego se ordenan por check_in de forma descendente (los más nuevos arriba).
        $registros = \App\Models\HistorialHabitacion::orderByRaw('check_out IS NULL DESC')
            ->orderBy('check_in', 'desc')
            ->get();
    
        return view('hotel', compact('habitaciones', 'conductores', 'registros'));
    }

    // 2️⃣ Leer una habitación por número
    public function show($numero)
    {
        $habitacion = Habitacion::where('numero', $numero)->first(); // Asumo que buscas por 'numero'
        if (!$habitacion) {
            return redirect()->back()->with('error', 'Habitación no encontrada');
        }
        return view('detalle', compact('habitacion'));
    }

    // 3️⃣ Crear una habitación
    public function store(Request $request)
    {
        $data = $request->validate([
            'numero' => 'required|string|unique:habitaciones,numero',
            'estado' => 'nullable|int',
            'conductor' => 'nullable|string',
        ]);

        $habitacion = Habitacion::create($data);
        Log::info("Se creó una habitación número: {$habitacion->numero}");
        return redirect()->back()->with('success', 'Habitación creada');
    }

    // 4️⃣ Actualizar una habitación (asignar o desasignar conductor)
            public function update(Request $request, $numero)
{
    try {
        $habitacion = Habitacion::where('numero', $numero)->first(); 
        
        if (!$habitacion) {
            return response()->json([
                'success' => false, 
                'error' => 'Habitación no encontrada'
            ], 404);
        }

        // Guardamos el conductor anterior (por si se desasigna)
        $conductor_que_sale = $habitacion->conductor;

        // Validar datos
        $data = $request->validate([
            'estado' => 'required|string|in:Disponible,Ocupada',
            'conductor' => 'nullable|string',
        ]);

        // Actualizamos habitación
        $habitacion->estado = $data['estado'];
        $habitacion->conductor = $data['conductor'];
        $habitacion->save();

        $accion = empty($data['conductor']) ? 'desasignada' : 'asignada';

        // Obtenemos datos del conductor (nuevo o anterior)
        $conductor_id = empty($data['conductor']) ? $conductor_que_sale : $data['conductor'];
        $conductor = Conductor::where('cedula', $conductor_id)->first();

        // 💾 Crear registro en historial según el tipo de acción
        if ($habitacion->estado == 'Ocupada') {
            // ===> CHECK-IN
            HistorialHabitacion::create([
                'habitacion'         => $habitacion->numero,
                'estado'             => 'Ocupada',
                'conductor'          => $conductor_id,
                'fecha'              => now(),
                'c_conductor'        => $conductor->cedula ?? 'N/A',
                'n_conductor'        => $conductor->nombre ?? 'N/A',
                'usuario'            => Auth::check() ? Auth::id() : 0,
                'usuario_check_in'   => Auth::check() ? Auth::user()->Nombre.' '.Auth::user()->Apellido  : 'Sistema',
                'check_in'           => now(),
                
            ]);

        } else {
            // ===> CHECK-OUT
            // Buscar el último registro del mismo conductor y habitación SIN check_out
            $ultimo = HistorialHabitacion::where('habitacion', $habitacion->numero)
                ->where('conductor', $conductor_que_sale)
                ->whereNull('check_out')
                ->latest('check_in')
                ->first();

            if ($ultimo) {
                $inicio = \Carbon\Carbon::parse($ultimo->check_in);
                $fin = now();

                // Calculamos la diferencia total en segundos (ej: 82 segundos)
                $segundosTotales = $inicio->diffInSeconds($fin);

                $ultimo->update([
                    'check_out'         => $fin,
                    'usuario_check_out' => Auth::check() ? Auth::user()->Nombre.' '.Auth::user()->Apellido : 'Sistema',
                    'tiempo_uso'        => $segundosTotales, // Guardamos el número entero
                ]);
            } else {
                // Si no existe un check_in previo, creamos un registro básico
                HistorialHabitacion::create([
                    'habitacion'         => $habitacion->numero,
                    'estado'             => 'Disponible',
                    'conductor'          => $conductor_que_sale,
                    'usuario'            => Auth::check() ? Auth::id() : 0,
                    'fecha'              => now(),
                    'c_conductor'        => $conductor->cedula ?? 'N/A',
                    'n_conductor'        => $conductor->nombre ?? 'N/A',
                    'check_out'          => now(),
                    'usuario_check_out'  => Auth::check() ? Auth::user()->Nombre.' '.Auth::user()->Apellido : 'Sistema',
                    'tiempo_uso'         => null,
                ]);
            }
        }

        Log::info("Habitación #{$numero} {$accion}. Conductor: {$conductor_id}");

        return response()->json([
            'success' => true,
            'message' => "Habitación {$accion} correctamente",
        ]);

    } catch (\Exception $e) {
        Log::error("Error al actualizar habitación #{$numero}: " . $e->getMessage());
        return response()->json([
            'success' => false,
            'error' => 'Error del servidor: ' . $e->getMessage()
        ], 500);
    }
}

    // 5️⃣ Borrar una habitación
        public function destroy($numero)
    {
        $habitacion = Habitacion::where('numero', $numero)->first(); // Asumo que buscas por 'numero'
        if (!$habitacion) {
            return redirect()->back()->with('error', 'Habitación no encontrada');
        }

        $habitacion->delete();
        Log::info("Se eliminó la habitación número: $numero");
        return redirect()->back()->with('success', 'Habitación eliminada');
    }
    
    // 6️⃣ Ver historial de habitaciones
        public function historial()
        {
            // Traer todos los registros más recientes primero
            $historial = \App\Models\HistorialHabitacion::latest()->get();

            // Retornar la vista (asegúrate que se llama igual)
            return view('historialhabitaciones', compact('historial'));
        }
        public function HistorialHabitacion()
    {
        $historial = \App\Models\HistorialHabitacion::latest()->get();
        return view('historialhabitaciones', compact('historial'));
    }
}

    