<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PrecioGlpController extends Controller
{
    public function index()
{
    // 1. Obtenemos todos los registros ordenados por el más reciente
    $todosLosPrecios = DB::table('2_precios_glp')->orderBy('id', 'desc')->get();
    
    // 2. El primero es el Vigente
    $ultimoPrecio = $todosLosPrecios->first();
    
    // 3. Los demás son el Histórico (empezamos desde el segundo registro)
    $historico = $todosLosPrecios->slice(1);

    // Solución para el error visual: Accedemos al rol de forma segura
    $user = auth()->user();
    $esAdmin = false;
    
    if ($user && isset($user->roles)) {
        // IDs 1 o 2 para administradores en Plexa
        $esAdmin = in_array((int)$user->roles, [1, 2]); 
    }

    $puedeEditar = false;
    if ($ultimoPrecio) {
        $fechaCarga = \Carbon\Carbon::parse($ultimoPrecio->fecha_inicio);
        $menosDe24Horas = $fechaCarga->diffInHours(now()) < 24;

        if ($menosDe24Horas || $esAdmin) {
            $puedeEditar = true;
        }
    } else {
        $puedeEditar = true; 
    }

    return view('preciosglp', compact('ultimoPrecio', 'historico', 'puedeEditar'));
}

    // Función para procesar la subida del archivo
    public function store(Request $request)
    {
        $request->validate([
            'archivo_pdf' => 'required|mimes:pdf|max:5120',
        ]);

        if ($request->hasFile('archivo_pdf')) {
            $file = $request->file('archivo_pdf');
            $nombreArchivo = $file->getClientOriginalName();
            
            // Guardar en storage/app/public/precios
            $file->storeAs('public/precios', $nombreArchivo);

            // Insertar en la base de datos
            DB::table('2_precios_glp')->insert([
                'archivo_pdf'  => $nombreArchivo,
                'fecha_inicio' => now(),
                'fecha_fin'    => now()->addMonth(),
                'user_id'      => Auth::id(),
            ]);

            return back()->with('success', 'Archivo cargado con éxito.');
        }

        return back()->with('error', 'Error al subir el archivo.');
    }
}