<?php

namespace App\Http\Controllers;

use App\Models\Habitacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth; // ✅ Importación para obtener el usuario
use App\Models\Conductor;
use App\Models\HistorialHabitacion; // ✅ Importación del nuevo modelo

class HabitacionController extends Controller
{
    // 1️⃣ Leer todas las habitaciones
    public function hotel()
    {
        $habitaciones = Habitacion::with('hconductor')->orderBy('numero')->get();
        $conductores = Conductor::orderBy('nombre')->get(); // ✅ Trae todos los conductores

        return view('hotel', compact('habitaciones', 'conductores'));
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

                // 💡 CAPTURAR LA CÉDULA DEL CONDUCTOR ANTES DE MODIFICAR
                $conductor_que_sale = $habitacion->conductor;

                // Validar datos
                $data = $request->validate([
                    'estado' => 'required|string|in:Disponible,Ocupada',
                    'conductor' => 'nullable|string',
                ]);

                // Actualizar habitación
                $habitacion->estado = $data['estado'];
                $habitacion->conductor = $data['conductor'];
                
                $accion = empty($data['conductor']) ? 'desasignada' : 'asignada';
                
                $habitacion->save();

                // 🔔 REGISTRAR EN HISTORIAL
                // Si estamos DESASIGNANDO, guardamos quién estaba (conductor_que_sale)
                // Si estamos ASIGNANDO, guardamos el nuevo conductor
                $conductor_para_historial = empty($data['conductor']) 
                    ? $conductor_que_sale  // ✅ Guardamos quien SALIÓ
                    : $data['conductor'];   // ✅ Guardamos quien ENTRÓ

                HistorialHabitacion::create([
                    'habitacion' => $habitacion->numero, 
                    'estado' => $habitacion->estado, 
                    'conductor' => $conductor_para_historial, // ✅ SIEMPRE tiene valor
                    'usuario' => Auth::check() ? Auth::id() : 0,
                    'fecha' => now(),
                ]);

                Log::info("Habitación #{$numero} {$accion}. Conductor en historial: {$conductor_para_historial}");

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
    
}