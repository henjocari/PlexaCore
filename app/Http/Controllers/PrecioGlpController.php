<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class PrecioGlpController extends Controller
{
    public function precioglp()
    {
        // Traemos datos y usuario responsable
        $query = DB::table('2_precios_glp')
            ->leftJoin('usuarios', '2_precios_glp.user_id', '=', 'usuarios.cedula')
            ->select(
                '2_precios_glp.*', 
                'usuarios.nombre as nombre_user', 
                'usuarios.apellido as apellido_user'
            )
            ->where('2_precios_glp.estado', 1)
            ->orderBy('2_precios_glp.id', 'desc');

        $ultimoPrecio = $query->first();
        $historico = $query->paginate(5);

        $user = Auth::user();
        $esAdmin = ($user && in_array((int)$user->rol, [1, 2]));
        $puedeEditar = $esAdmin;

        return view('preciosglp', compact('ultimoPrecio', 'historico', 'esAdmin', 'puedeEditar'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'archivo_pdf' => 'required|file|mimes:pdf|max:20480', // Máx 20MB
        ]);

        try {
            if ($request->hasFile('archivo_pdf')) {
                $file = $request->file('archivo_pdf');
                
                // 1. Nombre limpio y legible
                $nombreOriginal = $file->getClientOriginalName();
                $nombreLimpio = preg_replace('/[^A-Za-z0-9.\-_]/', '_', $nombreOriginal);
                $nombreArchivo = time() . '_' . $nombreLimpio;

                // 2. LA SOLUCIÓN DEFINITIVA: Guardar en 'public/archivos_glp'
                // Esto pone el archivo en una carpeta normal, sin bloqueos.
                $destino = public_path('archivos_glp');
                
                // Creamos la carpeta si no existe
                if (!File::exists($destino)) {
                    File::makeDirectory($destino, 0755, true);
                }

                // Movemos el archivo físicamente
                $file->move($destino, $nombreArchivo);
                
                // 3. Guardar en Base de Datos
                DB::table('2_precios_glp')->insert([
                    'archivo_pdf'  => $nombreArchivo,
                    'fecha_inicio' => now(),
                    'fecha_fin'    => now()->addMonth(),
                    'user_id'      => Auth::user()->cedula ?? 0,
                    'estado'       => 1
                ]);

                return back()->with('success', 'PDF subido y visible.');
            }

            return back()->with('error', 'No se envió ningún archivo.');

        } catch (\Exception $e) {
            dd("ERROR: " . $e->getMessage());
        }
    }

    public function inactivar($id)
    {
        if (!in_array((int)auth()->user()->rol, [1, 2])) return back();
        DB::table('2_precios_glp')->where('id', $id)->update(['estado' => 0]);
        return back()->with('success', 'Eliminado.');
    }
}