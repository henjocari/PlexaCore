<?php

namespace App\Http\Controllers;

use App\Models\InspeccionQuimica;
use Illuminate\Http\Request;

class InspeccionQuimicaController extends Controller
{
    public function create()
    {
        return view('inspeccionesquimicas');
    }

    public function store(Request $request)
    {
        $request->validate([
            'sede' => 'required|string|max:255',
            'fecha' => 'required|date',
            'area' => 'required|string|max:255',
            'evaluador' => 'required|string|max:255',
            'tipo_inspeccion' => 'required|string',
        ]);

        // Estructurar Inventario
        $inventario = [];
        if ($request->has('inv_nombre')) {
            foreach ($request->inv_nombre as $key => $val) {
                if (!empty($val)) {
                    $inventario[] = [
                        'nombre' => $val,
                        'uso' => $request->inv_uso[$key] ?? '',
                        'presentacion' => $request->inv_vol[$key] ?? '',
                        'fds' => $request->inv_fds[$key] ?? '',
                        'etiqueta' => $request->inv_etiqueta[$key] ?? '',
                    ];
                }
            }
        }

        // Estructurar Acciones
        $acciones = [];
        if ($request->has('act_hallazgo')) {
            foreach ($request->act_hallazgo as $key => $val) {
                if (!empty($val)) {
                    $acciones[] = [
                        'hallazgo' => $val,
                        'accion' => $request->act_accion[$key] ?? '',
                        'responsable' => $request->act_resp[$key] ?? '',
                    ];
                }
            }
        }

        InspeccionQuimica::create([
            'codigo_formato' => $request->codigo_formato ?? 'HSEQ-F-001',
            'sede' => $request->sede,
            'fecha' => $request->fecha,
            'area' => $request->area,
            'evaluador' => $request->evaluador,
            'tipo_inspeccion' => $request->tipo_inspeccion,
            'inventario' => $inventario,
            'checklist' => [
                'cumple' => $request->chk_cumple ?? [],
                'observaciones' => $request->chk_obs ?? [],
            ],
            'acciones' => $acciones,
            'firma_evaluador' => $request->firma_evaluador,
            'firma_responsable' => $request->firma_responsable,
        ]);

        return redirect()->back()->with('success', '¡Inspección guardada correctamente en la base de datos!');
    }

    public function index()
    {
        $inspecciones = InspeccionQuimica::latest()->get();
        return view('inspecciones_historico', compact('inspecciones'));
    }
}