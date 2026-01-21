<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CarruselController extends Controller
{
    public function carrusel()
    {
        // Usamos la tabla 3_carrusel
        $slides = DB::table('3_carrusel')
            ->where('estado', 1)
            ->orderBy('orden', 'asc')
            ->get();

        $user = Auth::user();
        $esAdmin = ($user && in_array((int)$user->rol, [1, 2]));

        return view('carrusel', compact('slides', 'esAdmin'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'titulo' => 'nullable|string|max:100',
            'orden'  => 'required|integer'
        ]);

        $accion = $request->input('accion');
        $idEditar = $request->input('id_editar');

        // Datos a guardar
        $datos = [
            'titulo'    => $request->input('titulo'),
            'subtitulo' => $request->input('subtitulo'),
            'boton'     => $request->input('boton') ?? 'Leer Más',
            'url'       => $request->input('url') ?? '#',
            'orden'     => $request->input('orden'),
            // AQUÍ EL CAMBIO: Usamos 'cedula' según tu imagen
            'cedula'    => Auth::user()->cedula ?? 0, 
            'fecha'     => now()
        ];

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $nombreArchivo = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/carrusel', $nombreArchivo);
            $datos['imagen'] = $nombreArchivo;
        }

        if ($accion == 'editar' && $idEditar) {
            DB::table('3_carrusel')->where('id', $idEditar)->update($datos);
            $mensaje = 'Actualizado correctamente.';
        } else {
            if (!$request->hasFile('imagen')) {
                return back()->with('error', 'La imagen es obligatoria.');
            }
            $datos['estado'] = 1;
            DB::table('3_carrusel')->insert($datos);
            $mensaje = 'Nuevo slide creado.';
        }

        return back()->with('success', $mensaje);
    }

    public function updateImagen(Request $request, $id)
    {
        $request->validate(['imagen' => 'required|image|max:10240']);

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $nombreArchivo = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/carrusel', $nombreArchivo);

            // Actualizamos imagen y cédula
            DB::table('3_carrusel')->where('id', $id)->update([
                'imagen' => $nombreArchivo,
                'cedula' => Auth::user()->cedula ?? 0
            ]);

            return back()->with('success', 'Imagen actualizada.');
        }
        return back()->with('error', 'Error al subir.');
    }

    public function inactivar($id)
    {
        if (!in_array((int)auth()->user()->rol, [1, 2])) return back();
        DB::table('3_carrusel')->where('id', $id)->update(['estado' => 0]);
        return back()->with('success', 'Slide eliminado.');
    }
}