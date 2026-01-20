<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CarruselController extends Controller
{
    /**
     * Muestra la vista principal con el carrusel y el panel de administración.
     */
    public function index()
    {
        // Traemos las imágenes activas (estado = 1) ordenadas por 'orden'
        $slides = DB::table('3_carrusel')
            ->where('estado', 1)
            ->orderBy('orden', 'asc')
            ->get();

        // Verificamos si es Admin (Roles 1 y 2) para mostrar el panel de abajo
        $user = Auth::user();
        $esAdmin = ($user && in_array((int)$user->rol, [1, 2]));

        return view('carrusel', compact('slides', 'esAdmin'));
    }

    /**
     * Guarda un nuevo slide o edita los textos de uno existente.
     */
    public function store(Request $request)
    {
        // Validamos textos e imagen (solo si es nuevo)
        $request->validate([
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // Máx 5MB
            'titulo' => 'nullable|string|max:100',
            'orden'  => 'required|integer'
        ]);

        $user = Auth::user();
        $accion = $request->input('accion'); // 'nuevo' o 'editar'
        $idEditar = $request->input('id_editar');

        // Datos comunes
        $datos = [
            'titulo'    => $request->input('titulo'),
            'subtitulo' => $request->input('subtitulo'),
            'boton'     => $request->input('boton') ?? 'Leer Más',
            'url'       => $request->input('url') ?? '#',
            'orden'     => $request->input('orden'),
            'user_id'   => $user->cedula,
            'fecha'     => now()
        ];

        // SUBIDA DE IMAGEN (Solo si viene desde el formulario principal)
        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            // Nombre único con tiempo
            $nombreArchivo = time() . '_' . $file->getClientOriginalName();
            // Guardar en storage/app/public/carrusel (CON 'S')
            $file->storeAs('public/carrusel', $nombreArchivo);
            $datos['imagen'] = $nombreArchivo;
        }

        // LÓGICA DE GUARDADO
        if ($accion == 'editar' && $idEditar) {
            // Actualizamos textos (y la imagen si se subió una nueva en el form grande)
            DB::table('3_carrusel')->where('id', $idEditar)->update($datos);
            $mensaje = 'Slide actualizado correctamente.';
        } else {
            // Si es nuevo, la imagen es obligatoria
            if (!$request->hasFile('imagen')) {
                return back()->with('error', 'Debes subir una imagen para crear un slide.');
            }
            $datos['estado'] = 1;
            DB::table('3_carrusel')->insert($datos);
            $mensaje = 'Nuevo slide creado exitosamente.';
        }

        return back()->with('success', $mensaje);
    }

    /**
     * NUEVA FUNCIÓN: Actualiza SOLO la imagen rápidamente desde el botón naranja.
     */
    public function updateImagen(Request $request, $id)
    {
        // Validamos que sea una imagen válida
        $request->validate([
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240', // Máx 10MB
        ]);

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $nombreArchivo = time() . '_' . $file->getClientOriginalName();
            
            // Guardamos la nueva imagen
            $file->storeAs('public/carrusel', $nombreArchivo);

            // Actualizamos solo el campo 'imagen' en la BD
            DB::table('3_carrusel')->where('id', $id)->update([
                'imagen' => $nombreArchivo,
                'user_id' => Auth::user()->cedula // Registramos quién hizo el cambio
            ]);

            return back()->with('success', 'Imagen actualizada correctamente.');
        }

        return back()->with('error', 'No se seleccionó ninguna imagen.');
    }

    /**
     * Borrado lógico (Inactivar)
     */
    public function inactivar($id)
    {
        if (!in_array((int)auth()->user()->rol, [1, 2])) {
            return back()->with('error', 'No tienes permisos.');
        }
        
        // Cambiamos estado a 0
        DB::table('3_carrusel')->where('id', $id)->update(['estado' => 0]);
        
        return back()->with('success', 'Imagen eliminada del carrusel.');
    }
}