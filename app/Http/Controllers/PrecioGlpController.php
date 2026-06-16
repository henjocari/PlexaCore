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
        // 1. Preparamos la consulta
        $query = DB::table('2_precios_glp')
            ->leftJoin('usuarios', '2_precios_glp.user_id', '=', 'usuarios.cedula')
            ->select('2_precios_glp.*', 'usuarios.nombre as nombre_user', 'usuarios.apellido as apellido_user')
            ->where('2_precios_glp.estado', 1)
            ->orderBy('2_precios_glp.id', 'desc');

        // 2. Obtenemos datos
        $ultimoPrecio = $query->first();
        $historico = $query->paginate(5);
        
        // 3. Verificamos permisos
        $user = Auth::user();
        
        // Definimos la variable con el nombre EXACTO que pide la vista ($puedeEditar)
        $puedeEditar = ($user && in_array((int)$user->rol, [1, 2]));

        // 4. Enviamos TODO a la vista
        return view('preciosglp', compact('ultimoPrecio', 'historico', 'puedeEditar')); 
    }

    public function store(Request $request)
    {
        $request->validate([
            // Aumentado a 100MB (102400 KB)
            'archivo_pdf' => 'required|file|mimes:pdf|max:102400',
        ]);

        try {
            if ($request->hasFile('archivo_pdf')) {
                $file = $request->file('archivo_pdf');
                
                // OBTENEMOS EL NOMBRE ORIGINAL
                $nombreOriginal = $file->getClientOriginalName();
                $nombreArchivo = $nombreOriginal; 

                // Ruta de destino
                $destino = public_path('archivos_glp');
                
                if (!file_exists($destino)) {
                    @mkdir($destino, 0777, true);
                }

                // Guardar archivo
                $file->move($destino, $nombreArchivo);
                
                // Guardar en BD
                DB::table('2_precios_glp')->insert([
                    'archivo_pdf'  => $nombreArchivo,
                    'fecha_inicio' => now(),
                    'fecha_fin'    => now()->addMonth(),
                    'user_id'      => Auth::user()->cedula ?? 0,
                    'estado'       => 1
                ]);

                return back()->with('success', 'PDF subido correctamente: ' . $nombreArchivo);
            }
            return back()->with('error', 'Falta el archivo.');

        } catch (\Exception $e) {
            dd("ERROR: " . $e->getMessage());
        }
    }

    public function inactivar($id)
    {
        // Solo admin o rol 2 pueden borrar
        if (!in_array((int)auth()->user()->rol, [1, 2])) return back();
        
        DB::table('2_precios_glp')->where('id', $id)->update(['estado' => 0]);
        return back()->with('success', 'Documento eliminado.');
    }

    // =======================================================
    // MÉTODO PARA MOSTRAR EL PDF A NEWPLEXA (VISOR)
    // =======================================================
    public function verPDF($archivo)
    {
        $ruta = public_path('archivos_glp/' . $archivo); 

        if (!file_exists($ruta)) {
            abort(404, 'El documento PDF no fue encontrado en el servidor.');
        }

        return response()->file($ruta, [
            'Content-Type' => 'application/pdf',
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, OPTIONS'
        ]);
    }

    // =======================================================
    // MÉTODO PARA FORZAR LA DESCARGA DEL PDF (BOTÓN AZUL)
    // =======================================================
    public function descargarPDF($archivo)
    {
        $ruta = public_path('archivos_glp/' . $archivo); 

        if (!file_exists($ruta)) {
            abort(404, 'El documento PDF no fue encontrado.');
        }

        return response()->download($ruta, $archivo, [
            'Access-Control-Allow-Origin' => '*'
        ]);
    }
}