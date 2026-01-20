<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PrecioGlpController extends Controller
{
    public function precioglp()
    {
        // 1. OBTENER EL VIGENTE (El más reciente activo)
        // Lo sacamos aparte para el VISOR GRANDE
        $ultimoPrecio = DB::table('2_precios_glp')
            ->leftJoin('usuarios', '2_precios_glp.user_id', '=', 'usuarios.cedula')
            ->select('2_precios_glp.*', 'usuarios.Nombre as nombre_usuario')
            ->where('2_precios_glp.estado', 1)
            ->orderBy('2_precios_glp.id', 'desc')
            ->first();

        // 2. OBTENER EL HISTORIAL (El resto de activos)
        // Usamos paginate(8) para que corte cada 8 registros
        $query = DB::table('2_precios_glp')
            ->leftJoin('usuarios', '2_precios_glp.user_id', '=', 'usuarios.cedula')
            ->select('2_precios_glp.*', 'usuarios.Nombre as nombre_usuario')
            ->where('2_precios_glp.estado', 1)
            ->orderBy('2_precios_glp.id', 'desc');

        // Si existe un vigente, lo sacamos de la lista de abajo para no repetirlo
        if ($ultimoPrecio) {
            $query->where('2_precios_glp.id', '!=', $ultimoPrecio->id);
        }

        $historico = $query->paginate(8); // <--- Aquí activamos la paginación

        // 3. PERMISOS (Solo Admin Roles 1 y 2 editan siempre)
        $user = Auth::user();
        $esAdmin = ($user && in_array((int)$user->rol, [1, 2]));

        $puedeEditar = false;
        if ($esAdmin) {
            $puedeEditar = true;
        } elseif ($ultimoPrecio) {
            $fechaCarga = Carbon::parse($ultimoPrecio->fecha_inicio);
            if ($fechaCarga->diffInHours(now()) < 24) {
                $puedeEditar = true;
            }
        } else {
            $puedeEditar = true;
        }

        return view('preciosglp', compact('ultimoPrecio', 'historico', 'puedeEditar', 'esAdmin'));
    }

    public function store(Request $request)
    {
        $request->validate(['archivo_pdf' => 'required|mimes:pdf|max:10240']);

        if ($request->hasFile('archivo_pdf')) {
            $user = Auth::user();
            $file = $request->file('archivo_pdf');
            
            // Guardamos el nombre EXACTO del archivo (Ej: "ReporteDic.pdf")
            $nombreArchivo = $file->getClientOriginalName();
            
            // Guardamos en la carpeta pública
            $file->storeAs('public/precios', $nombreArchivo);

            $accion = $request->input('accion');
            
            // Lógica de Edición (Reemplazar actual)
            if ($accion == 'editar') {
                $ultimoPrecio = DB::table('2_precios_glp')->where('estado', 1)->orderBy('id', 'desc')->first();
                if ($ultimoPrecio) {
                    DB::table('2_precios_glp')->where('id', $ultimoPrecio->id)->update([
                        'archivo_pdf'  => $nombreArchivo,
                        'fecha_inicio' => now(),
                        'user_id'      => $user->cedula
                    ]);
                    return back()->with('success', 'Archivo actualizado correctamente.');
                }
            }

            // Lógica de Nuevo Registro
            $proximoId = (DB::table('2_precios_glp')->max('id') ?? 0) + 1;

            DB::table('2_precios_glp')->insert([
                'id'           => $proximoId,
                'archivo_pdf'  => $nombreArchivo,
                'fecha_inicio' => now(),
                'fecha_fin'    => now()->addMonth(),
                'user_id'      => $user->cedula,
                'estado'       => 1
            ]);

            return back()->with('success', 'Documento subido: ' . $nombreArchivo);
        }
    }

    public function inactivar($id)
    {
        if (!in_array((int)auth()->user()->rol, [1, 2])) {
            return back()->with('error', 'No tienes permisos.');
        }
        DB::table('2_precios_glp')->where('id', $id)->update(['estado' => 0]);
        return back()->with('success', 'Registro ocultado.');
    }
}