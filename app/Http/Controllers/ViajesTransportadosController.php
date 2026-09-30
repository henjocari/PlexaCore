<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InformeGerencialExport;

class ViajesTransportadosController extends Controller
{
    public function index(Request $request)
    {
        // =====================================================================
        // 2. CONSULTAS LOCALES Y FILTROS DINÁMICOS
        // =====================================================================
        $fechaInicial = $request->filled('fecha_inicial') ? $request->fecha_inicial : Carbon::now()->startOfMonth()->format('Y-m-d');
        $fechaFinal = $request->filled('fecha_final') ? $request->fecha_final : Carbon::now()->format('Y-m-d');

        $baseQuery = DB::table('silog_manifiestos')
            ->whereRaw("UPPER(estado) != 'ANULADO'")
            ->whereBetween('fecha', [$fechaInicial, $fechaFinal]);

        // FILTRO NUEVO: Manifiesto (Se aplica a la base para filtrar todo el dashboard)
        if ($request->filled('manifiesto')) {
            $baseQuery->where('manifiesto', 'LIKE', '%' . trim($request->manifiesto) . '%');
        }

        // A. Opciones dinámicas (Ignoran espacios ocultos usando LIKE)
        $qOperaciones = clone $baseQuery;
        if ($request->filled('producto')) $qOperaciones->where('producto', 'LIKE', '%' . trim($request->producto) . '%');
        if ($request->filled('poseedor')) $qOperaciones->where('poseedor', 'LIKE', '%' . trim($request->poseedor) . '%');
        
        $qProductos = clone $baseQuery;
        if ($request->filled('tipo_operacion')) $qProductos->where('tipoOperacion', 'LIKE', '%' . trim($request->tipo_operacion) . '%');
        if ($request->filled('poseedor')) $qProductos->where('poseedor', 'LIKE', '%' . trim($request->poseedor) . '%');
        
        $qPoseedores = clone $baseQuery;
        if ($request->filled('tipo_operacion')) $qPoseedores->where('tipoOperacion', 'LIKE', '%' . trim($request->tipo_operacion) . '%');
        if ($request->filled('producto')) $qPoseedores->where('producto', 'LIKE', '%' . trim($request->producto) . '%');

        $filtrosOpciones = [
            'operaciones' => $qOperaciones->distinct()->pluck('tipoOperacion')->filter()->sort(),
            'productos'   => $qProductos->distinct()->pluck('producto')->filter()->sort(),
            'poseedores'  => $qPoseedores->distinct()->pluck('poseedor')->filter()->sort(),
        ];

        // Consulta de datos final
        $queryDatos = clone $baseQuery;
        if ($request->filled('tipo_operacion')) $queryDatos->where('tipoOperacion', 'LIKE', '%' . trim($request->tipo_operacion) . '%');
        if ($request->filled('producto')) $queryDatos->where('producto', 'LIKE', '%' . trim($request->producto) . '%');
        if ($request->filled('poseedor')) $queryDatos->where('poseedor', 'LIKE', '%' . trim($request->poseedor) . '%');

        $manifiestos = $queryDatos->get();

        // =====================================================================
        // 3. CÁLCULOS KPI Y DATOS PARA GRÁFICOS
        // =====================================================================
        $viajes = $manifiestos->count();
        $galones = $manifiestos->sum('cantidad');
        $barriles = $galones > 0 ? $galones / 42 : 0;
        $facturacion = $manifiestos->sum('fleteRemesa');
        $costoTercero = $manifiestos->sum('fleteNeto');
        
        $margen = $facturacion - $costoTercero;
        $porcentajeMargen = $facturacion > 0 ? ($margen / $facturacion) * 100 : 0;

        // Gráfico 1: Viajes por día
        $viajesPorDia = $manifiestos->groupBy('fecha')->map->count()->sortKeys();

        // Gráfico 2: Comportamiento de estados
        $viajesActivos = $manifiestos->where('estado', 'ACTIVO')->count();
        $viajesCumplidos = $manifiestos->where('estado', 'CUMPLIDO')->count(); // Cambio aquí
        $viajesLiquidados = 0; 
        $viajesFacturados = 0;

        // TOP 5 Manifiestos con mejor margen (Excluyendo flota propia PLEXA)
        $topManifiestos = $manifiestos->reject(function ($m) {
            return trim($m->poseedor ?? '') === '860515802-PLEXA SAS ESP';
        })->map(function ($m) {
            $facturacion = (float)($m->fleteRemesa ?? 0);
            $costo = (float)($m->fleteNeto ?? 0);
            $m_margen = $facturacion - $costo;
            $m_pct = $facturacion > 0 ? ($m_margen / $facturacion) * 100 : 0;
            
            return [
                'manifiesto' => $m->manifiesto,
                'origen' => $m->origen ?? 'N/A',
                'destino' => $m->destino ?? 'N/A',
                'margen' => $m_margen,
                'margen_format' => '$ ' . number_format($m_margen, 0, ',', '.'),
                'margen_pct_format' => number_format($m_pct, 1, ',', '.') . '%'
            ];
        })->sortByDesc('margen')->take(5)->values();

        // TOP 5 Rutas con mejor margen total (Agrupación)
        $topRutas = $manifiestos->groupBy(function ($m) {
            return ($m->origen ?? 'SIN ORIGEN') . ' a ' . ($m->destino ?? 'SIN DESTINO');
        })->map(function ($viajesRuta, $nombreRuta) {
            $facturacion = $viajesRuta->sum('fleteRemesa');
            $costo = $viajesRuta->sum('fleteNeto');
            $ruta_margen = $facturacion - $costo;
            $ruta_pct = $facturacion > 0 ? ($ruta_margen / $facturacion) * 100 : 0;

            return [
                'ruta' => $nombreRuta,
                'cantidad_viajes' => $viajesRuta->count(),
                'margen' => $ruta_margen,
                'margen_format' => '$ ' . number_format($ruta_margen, 0, ',', '.'),
                'margen_pct_format' => number_format($ruta_pct, 1, ',', '.') . '%'
            ];
        })->sortByDesc('margen')->take(5)->values();

        $dashboardData = [
            'viajes' => number_format($viajes, 0, ',', '.'),
            'barriles' => number_format($barriles, 2, ',', '.'),
            'galones' => number_format($galones, 2, ',', '.'),
            'facturacion' => '$ ' . number_format($facturacion, 0, ',', '.'),
            'costo_tercero' => '$ ' . number_format($costoTercero, 0, ',', '.'),
            'margen' => '$ ' . number_format($margen, 0, ',', '.'),
            'margen_pct' => number_format($porcentajeMargen, 1, ',', '.') . '%',
            'chart_diario' => [
                'fechas' => array_values($viajesPorDia->keys()->toArray()),
                'totales' => array_values($viajesPorDia->values()->toArray()),
            ],
            'chart_estados' => [
                $viajes,              
                $viajesActivos,       
                $viajesCumplidos,    
                $viajesLiquidados,   
                $viajesFacturados    
            ],
            'top_manifiestos' => $topManifiestos,
            'top_rutas' => $topRutas
        ];

        return view('viajes-transportados', compact('dashboardData', 'filtrosOpciones', 'fechaInicial', 'fechaFinal'));
    }
    public function exportarExcel(Request $request)
    {
        // 1. Replicamos las fechas y consultas base (Igual que en tu index)
        $fechaInicial = $request->filled('fecha_inicial') ? $request->fecha_inicial : Carbon::now()->startOfMonth()->format('Y-m-d');
        $fechaFinal = $request->filled('fecha_final') ? $request->fecha_final : Carbon::now()->format('Y-m-d');

        $baseQuery = DB::table('silog_manifiestos')
            ->whereRaw("UPPER(estado) != 'ANULADO'")
            ->whereBetween('fecha', [$fechaInicial, $fechaFinal]);

        if ($request->filled('manifiesto')) $baseQuery->where('manifiesto', 'LIKE', '%' . trim($request->manifiesto) . '%');
        if ($request->filled('tipo_operacion')) $baseQuery->where('tipoOperacion', 'LIKE', '%' . trim($request->tipo_operacion) . '%');
        if ($request->filled('producto')) $baseQuery->where('producto', 'LIKE', '%' . trim($request->producto) . '%');
        if ($request->filled('poseedor')) $baseQuery->where('poseedor', 'LIKE', '%' . trim($request->poseedor) . '%');

        $manifiestos = $baseQuery->get();

        // 2. Cálculos consolidados para la Hoja 1
        $facturacion = $manifiestos->sum('fleteRemesa');
        $costo = $manifiestos->sum('fleteNeto');
        
        $consolidado = [
            'periodo' => $fechaInicial . ' al ' . $fechaFinal,
            'viajes' => $manifiestos->count(),
            'galones' => round($manifiestos->sum('cantidad'), 2),
            'barriles' => round($manifiestos->sum('cantidad') / 42, 2),
            'facturacion' => $facturacion,
            'costo' => $costo,
            'margen' => $facturacion - $costo,
        ];

        // 3. Descarga el Excel
        return Excel::download(new InformeGerencialExport($consolidado, $manifiestos), 'Informe_Gerencial_'.$fechaInicial.'.xlsx');
    }
}