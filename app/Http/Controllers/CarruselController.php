<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CarruselController extends Controller
{
    public function carrusel()
    {
        // 1. Slides Activos (Tabla 1_carrusel)
        $slides = DB::table('1_carrusel')
            ->where('estado', 1)
            ->orderBy('orden', 'asc')
            ->get();

        // 2. Slides Inactivos (Papelera - Tabla 1_carrusel)
        $slidesInactivos = DB::table('1_carrusel')
            ->where('estado', 0)
            ->orderBy('id', 'desc')
            ->get();

        $user = Auth::user();
        $esAdmin = ($user && in_array((int)$user->rol, [1, 2]));

        return view('carrusel', compact('slides', 'slidesInactivos', 'esAdmin'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'imagen' => 'nullable|image|max:10240', // Máx 10MB
            'titulo' => 'nullable|string|max:100',
            'orden'  => 'required|integer'
        ]);

        try {
            $accion = $request->input('accion');
            $idEditar = $request->input('id_editar');

            $datos = [
                'titulo'    => $request->input('titulo'),
                'subtitulo' => $request->input('subtitulo'),
                'boton'     => $request->input('boton') ?? 'Leer Más',
                'url'       => $request->input('url') ?? '#',
                'orden'     => $request->input('orden'),
                'fecha'     => now(),
                'cedula'    => Auth::user()->cedula ?? 0 
            ];

            // Subida de imagen con nombre seguro
            if ($request->hasFile('imagen')) {
                $file = $request->file('imagen');
                $nombreArchivo = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/carrusel', $nombreArchivo);
                $datos['imagen'] = $nombreArchivo;
            }

            // Guardar en tabla 1_carrusel
            if ($accion == 'editar' && $idEditar) {
                DB::table('1_carrusel')->where('id', $idEditar)->update($datos);
                $mensaje = 'Actualizado correctamente.';
            } else {
                if (!$request->hasFile('imagen')) {
                    return back()->with('error', 'Sube una imagen.');
                }
                $datos['estado'] = 1;
                DB::table('1_carrusel')->insert($datos);
                $mensaje = 'Slide creado en tabla 1.';
            }

            return back()->with('success', $mensaje);

        } catch (\Exception $e) {
            // Muestra el error técnico si falla algo
            dd("ERROR: " . $e->getMessage());
        }
    }

    public function updateImagen(Request $request, $id)
    {
        $request->validate(['imagen' => 'required|image|max:10240']);

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            // Nombre corto y seguro
            $nombreArchivo = 'img_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/carrusel', $nombreArchivo);

            // Actualiza solo la imagen en 1_carrusel
            DB::table('1_carrusel')->where('id', $id)->update(['imagen' => $nombreArchivo]);
            
            return back()->with('success', 'Imagen cambiada.');
        }
        return back()->with('error', 'Error al subir.');
    }

    // MANDAR A PAPELERA
    public function inactivar($id)
    {
        if (!in_array((int)auth()->user()->rol, [1, 2])) return back();
        
        DB::table('1_carrusel')->where('id', $id)->update(['estado' => 0]);
        
        return back()->with('success', 'Movido a la papelera.');
    }

    // RESTAURAR DE PAPELERA
    public function activar($id)
    {
        if (!in_array((int)auth()->user()->rol, [1, 2])) return back();
        
        DB::table('1_carrusel')->where('id', $id)->update(['estado' => 1]);
        
        return back()->with('success', 'Restaurado correctamente.');
    }
}