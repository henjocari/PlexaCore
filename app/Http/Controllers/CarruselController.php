<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CarruselController extends Controller
{
    // --- VISTA PRINCIPAL ---
    public function carrusel()
    {
        $slides = DB::table('1_carrusel')
            ->where('estado', 1)
            ->orderBy('orden', 'asc')
            ->get();

        $slidesInactivos = DB::table('1_carrusel')
            ->where('estado', 0)
            ->orderBy('id', 'desc')
            ->get();

        $user = Auth::user();
        $esAdmin = ($user && in_array((int)$user->rol, [1, 2]));

        return view('carrusel', compact('slides', 'slidesInactivos', 'esAdmin'));
    }

    // --- GUARDAR NUEVO SLIDE ---
    public function store(Request $request)
    {
        if ($request->accion == 'editar') {
            return $this->update($request);
        }

        $request->validate([
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'titulo' => 'required|string|max:100',
        ]);

        try {
            $user = Auth::user();

            $datos = [
                'titulo'    => $request->titulo,
                'subtitulo' => $request->subtitulo,
                'boton'     => $request->boton ?? 'Ver Más',
                'url'       => $request->url ?? '#',
                'orden'     => $request->orden ?? 10,
                'estado'    => 1,
                'cedula'    => $user->cedula ?? 0, 
                'fecha'     => now() 
            ];

            // GUARDADO CON URL COMPLETA (Para que Python la vea)
            if ($request->hasFile('imagen')) {
                $file = $request->file('imagen');
                $nombreLimpio = preg_replace('/[^A-Za-z0-9.\-_]/', '_', $file->getClientOriginalName());
                $nombreArchivo = time() . '_' . $nombreLimpio;
                
                // 1. Guardar físicamente en carpeta pública
                $destino = public_path('imagenes_carrusel');
                if (!file_exists($destino)) { @mkdir($destino, 0777, true); }
                $file->move($destino, $nombreArchivo);
                
                // 2. AQUÍ ESTÁ EL TRUCO: Guardamos la URL completa (http://...)
                // Usamos 'url()' para generar el enlace completo
                $datos['imagen'] = url('imagenes_carrusel/' . $nombreArchivo);
            }

            DB::table('1_carrusel')->insert($datos);

            return back()->with('success', 'Slide creado correctamente.');

        } catch (\Exception $e) {
            dd("ERROR TÉCNICO: " . $e->getMessage());
        }
    }

    // --- ACTUALIZAR ---
    public function update(Request $request)
    {
        $id = $request->id_editar;
        
        $datos = [
            'titulo'    => $request->titulo,
            'subtitulo' => $request->subtitulo,
            'boton'     => $request->boton,
            'url'       => $request->url,
        ];

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $nombreLimpio = preg_replace('/[^A-Za-z0-9.\-_]/', '_', $file->getClientOriginalName());
            $nombreArchivo = time() . '_' . $nombreLimpio;
            
            $file->move(public_path('imagenes_carrusel'), $nombreArchivo);
            
            // Guardamos URL completa
            $datos['imagen'] = url('imagenes_carrusel/' . $nombreArchivo);
        }

        DB::table('1_carrusel')->where('id', $id)->update($datos);

        return back()->with('success', 'Slide actualizado.');
    }

    // --- CAMBIO RÁPIDO DE FOTO ---
    public function updateImagen(Request $request, $id)
    {
        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $nombreLimpio = preg_replace('/[^A-Za-z0-9.\-_]/', '_', $file->getClientOriginalName());
            $nombreArchivo = time() . '_' . $nombreLimpio;
            
            $file->move(public_path('imagenes_carrusel'), $nombreArchivo);
            
            // Guardamos URL completa
            $nuevaUrl = url('imagenes_carrusel/' . $nombreArchivo);
            
            DB::table('1_carrusel')->where('id', $id)->update(['imagen' => $nuevaUrl]);
            return back()->with('success', 'Imagen actualizada.');
        }
        return back();
    }

    // --- PAPELERA ---
    public function inactivar($id)
    {
        DB::table('1_carrusel')->where('id', $id)->update(['estado' => 0]);
        return back()->with('success', 'Enviado a papelera.');
    }

    public function activar($id)
    {
        DB::table('1_carrusel')->where('id', $id)->update(['estado' => 1]);
        return back()->with('success', 'Restaurado.');
    }
}