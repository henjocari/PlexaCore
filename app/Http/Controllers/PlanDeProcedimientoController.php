<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Models\PlanSeguimientoRegistro;
use App\Models\PlanSeguimientoHistorial;

class PlanDeProcedimientoController extends Controller
{
    /**
     * PLAN DE SEGUIMIENTO - SOSTENIBILIDAD PLEXA
     * Semanas (índices 0 a 29):
     * JULIO 0-4 | AGOSTO 5-9 | SEPTIEMBRE 10-14
     * OCTUBRE 15-19 | NOVIEMBRE 20-24 | DICIEMBRE 25-29
     */
    private function actividades(): Collection
    {
        $s2Mes = [1, 6, 11, 16, 21, 26];
        $s3Mes = [2, 7, 12, 17, 22, 27];
        $s4Mes = [3, 8, 13, 18, 23, 28];
        $s5Mes = [4, 9, 14, 19, 24, 29];
        $s1a4  = [0,1,2,3, 5,6,7,8, 10,11,12,13, 15,16,17,18, 20,21,22,23, 25,26,27,28];
        $todas = range(0, 29);

        return collect([
            // ================= ODS 3: SALUD Y BIENESTAR (HSE) - 13 =================
            ['id' => 'A-01', 'programa' => 'PROGRAMA MI DECISIÓN RIESGO CERO',
             'actividad' => 'Campaña "Mi Decisión Riesgo Cero" (Percepción y Autocuidado)',
             'ods_codigo' => 'ODS 3', 'ods_nombre' => 'ODS 3: Salud y Bienestar (HSE)', 'ods_corto' => 'ODS 3: Salud y Bienestar',
             'meta' => '1 Campaña al Mes', 'responsable' => 'HSE', 'frecuencia' => 'Mensual',
             'observaciones' => 'Campaña permanente de concientización mensual',
             'patron' => $s2Mes, 'inicial' => [1 => 'E']],

            ['id' => 'A-02', 'programa' => 'PROGRAMA MI DECISIÓN RIESGO CERO',
             'actividad' => 'Observaciones Planeadas de Comportamiento (OPC) en operaciones críticas',
             'ods_codigo' => 'ODS 3', 'ods_nombre' => 'ODS 3: Salud y Bienestar (HSE)', 'ods_corto' => 'ODS 3: Salud y Bienestar',
             'meta' => '4 OPC al Mes', 'responsable' => 'HSE', 'frecuencia' => 'Semanal',
             'observaciones' => 'Constante en sitio operativo',
             'patron' => $s1a4, 'inicial' => []],

            ['id' => 'A-03', 'programa' => 'PROGRAMA MI DECISIÓN RIESGO CERO',
             'actividad' => 'Jornadas de fatiga, higiene del sueño y pausas activas',
             'ods_codigo' => 'ODS 3', 'ods_nombre' => 'ODS 3: Salud y Bienestar (HSE)', 'ods_corto' => 'ODS 3: Salud y Bienestar',
             'meta' => '90% Asistencia', 'responsable' => 'HSE', 'frecuencia' => 'Bimensual',
             'observaciones' => 'Enfocado en conductores y operadores',
             'patron' => [1, 11, 21], 'inicial' => []],

            ['id' => 'A-04', 'programa' => 'PROGRAMA MI DECISIÓN RIESGO CERO',
             'actividad' => 'Campañas de hábitos saludables, salud mental y prevención psicoactiva',
             'ods_codigo' => 'ODS 3', 'ods_nombre' => 'ODS 3: Salud y Bienestar (HSE)', 'ods_corto' => 'ODS 3: Salud y Bienestar',
             'meta' => 'Jornada Mensual', 'responsable' => 'HSE', 'frecuencia' => 'Mensual',
             'observaciones' => 'Actividad recurrente de bienestar',
             'patron' => $s2Mes, 'inicial' => []],

            ['id' => 'A-05', 'programa' => 'PROGRAMA MI DECISIÓN RIESGO CERO',
             'actividad' => 'Promoción y reporte de condiciones inseguras y cuasi-accidentes',
             'ods_codigo' => 'ODS 3', 'ods_nombre' => 'ODS 3: Salud y Bienestar (HSE)', 'ods_corto' => 'ODS 3: Salud y Bienestar',
             'meta' => '+10% Reportes vs anterior', 'responsable' => 'HSE', 'frecuencia' => 'Permanente',
             'observaciones' => 'Campaña permanente con incentivos',
             'patron' => $todas, 'inicial' => []],

            ['id' => 'A-06', 'programa' => 'PROGRAMA MI DECISIÓN RIESGO CERO',
             'actividad' => 'Caminatas gerenciales de seguridad con participación de líderes',
             'ods_codigo' => 'ODS 3', 'ods_nombre' => 'ODS 3: Salud y Bienestar (HSE)', 'ods_corto' => 'ODS 3: Salud y Bienestar',
             'meta' => '1 Caminata al Mes', 'responsable' => 'HSE', 'frecuencia' => 'Mensual',
             'observaciones' => 'Programado junto a gerencia general',
             'patron' => $s2Mes, 'inicial' => []],

            ['id' => 'A-07', 'programa' => 'PROGRAMA MI DECISIÓN RIESGO CERO',
             'actividad' => 'Divulgación de lecciones aprendidas de incidentes internos y sector',
             'ods_codigo' => 'ODS 3', 'ods_nombre' => 'ODS 3: Salud y Bienestar (HSE)', 'ods_corto' => 'ODS 3: Salud y Bienestar',
             'meta' => 'Reporte Mensual', 'responsable' => 'HSE', 'frecuencia' => 'Mensual',
             'observaciones' => 'Prevención según ocurrencia',
             'patron' => $s2Mes, 'inicial' => []],

            ['id' => 'A-08', 'programa' => 'PROGRAMA MI DECISIÓN RIESGO CERO',
             'actividad' => 'Aplicación de evaluación de percepción de cultura HSE y planes',
             'ods_codigo' => 'ODS 3', 'ods_nombre' => 'ODS 3: Salud y Bienestar (HSE)', 'ods_corto' => 'ODS 3: Salud y Bienestar',
             'meta' => '1 Evaluación', 'responsable' => 'HSE', 'frecuencia' => 'Anual',
             'observaciones' => 'Encuesta programada en Septiembre',
             'patron' => [13], 'inicial' => []],

            ['id' => 'A-09', 'programa' => 'PROGRAMA MI DECISIÓN RIESGO CERO',
             'actividad' => 'Inspecciones enfocadas en Riesgos Críticos (GLP, alturas, espacios)',
             'ods_codigo' => 'ODS 3', 'ods_nombre' => 'ODS 3: Salud y Bienestar (HSE)', 'ods_corto' => 'ODS 3: Salud y Bienestar',
             'meta' => '100% Inspecciones', 'responsable' => 'HSE', 'frecuencia' => 'Mensual',
             'observaciones' => 'Ejecución mensual continua en campo',
             'patron' => $todas, 'inicial' => []],

            ['id' => 'A-10', 'programa' => 'PLANES DE EMERGENCIA',
             'actividad' => 'Ejecución de simulacros de atención de emergencias (GLP, volcamiento)',
             'ods_codigo' => 'ODS 3', 'ods_nombre' => 'ODS 3: Salud y Bienestar (HSE)', 'ods_corto' => 'ODS 3: Salud y Bienestar',
             'meta' => '1 Simulacro', 'responsable' => 'HSE', 'frecuencia' => 'Trimestral',
             'observaciones' => 'Frecuencia trimestral obligatoria',
             'patron' => [12, 28], 'inicial' => []],

            ['id' => 'A-11', 'programa' => 'PLANES DE EMERGENCIA',
             'actividad' => 'Reuniones de articulación con aliados (Ecopetrol, socorro, Bomberos)',
             'ods_codigo' => 'ODS 3', 'ods_nombre' => 'ODS 3: Salud y Bienestar (HSE)', 'ods_corto' => 'ODS 3: Salud y Bienestar',
             'meta' => '1 Reunión Semestral', 'responsable' => 'HSE', 'frecuencia' => 'Semestral',
             'observaciones' => 'Alineación de respuesta externa',
             'patron' => [21], 'inicial' => []],

            ['id' => 'A-12', 'programa' => 'PLANES DE EMERGENCIA',
             'actividad' => 'Capacitación de brigadistas en manejo de emergencias (GLP, primeros auxilios)',
             'ods_codigo' => 'ODS 3', 'ods_nombre' => 'ODS 3: Salud y Bienestar (HSE)', 'ods_corto' => 'ODS 3: Salud y Bienestar',
             'meta' => '100% Personal', 'responsable' => 'HSE', 'frecuencia' => 'Bimensual',
             'observaciones' => 'Frecuencia bimensual',
             'patron' => [1, 11, 21], 'inicial' => []],

            ['id' => 'A-13', 'programa' => 'PLANES DE EMERGENCIA',
             'actividad' => 'Inspección de equipos de emergencia, kits para derrames y alarmas',
             'ods_codigo' => 'ODS 3', 'ods_nombre' => 'ODS 3: Salud y Bienestar (HSE)', 'ods_corto' => 'ODS 3: Salud y Bienestar',
             'meta' => '100% Inspecciones', 'responsable' => 'HSE', 'frecuencia' => 'Mensual',
             'observaciones' => 'Garantizar operatividad total de equipos',
             'patron' => $todas, 'inicial' => []],

            // ============ ODS 12: PRODUCCIÓN Y CONSUMO RESPONSABLE - 10 ============
            ['id' => 'A-14', 'programa' => 'GESTIÓN INTEGRAL RESIDUOS',
             'actividad' => 'Cuantificación y aforo mensual de residuos (ordinarios, reciclables, RESPEL)',
             'ods_codigo' => 'ODS 12', 'ods_nombre' => 'ODS 12: Producción y Consumo Responsable', 'ods_corto' => 'ODS 12: Prod. y Consumo',
             'meta' => '1 Aforo Mensual', 'responsable' => 'Gestión Ambiental', 'frecuencia' => 'Mensual',
             'observaciones' => 'Ejecución mensual por sede',
             'patron' => $s2Mes, 'inicial' => []],

            ['id' => 'A-15', 'programa' => 'GESTIÓN INTEGRAL RESIDUOS',
             'actividad' => 'Verificación de cadena de custodia mediante manifiestos y licencias',
             'ods_codigo' => 'ODS 12', 'ods_nombre' => 'ODS 12: Producción y Consumo Responsable', 'ods_corto' => 'ODS 12: Prod. y Consumo',
             'meta' => '100% Actas', 'responsable' => 'Gestión Ambiental', 'frecuencia' => 'Mensual',
             'observaciones' => 'Asegurar trazabilidad legal de RESPEL',
             'patron' => $todas, 'inicial' => []],

            ['id' => 'A-16', 'programa' => 'GESTIÓN INTEGRAL RESIDUOS',
             'actividad' => 'Inspección a los centros de acopio de residuos peligrosos (RESPEL)',
             'ods_codigo' => 'ODS 12', 'ods_nombre' => 'ODS 12: Producción y Consumo Responsable', 'ods_corto' => 'ODS 12: Prod. y Consumo',
             'meta' => '100% Inspecciones', 'responsable' => 'Gestión Ambiental', 'frecuencia' => 'Mensual',
             'observaciones' => 'Evitar incidentes con RESPEL',
             'patron' => $todas, 'inicial' => []],

            ['id' => 'A-17', 'programa' => 'GESTIÓN INTEGRAL RESIDUOS',
             'actividad' => 'Inspección al cumplimiento del código de colores, acopio y rotulado',
             'ods_codigo' => 'ODS 12', 'ods_nombre' => 'ODS 12: Producción y Consumo Responsable', 'ods_corto' => 'ODS 12: Prod. y Consumo',
             'meta' => '0 Hallazgos', 'responsable' => 'Gestión Ambiental', 'frecuencia' => 'Semanal',
             'observaciones' => 'Revisión semanal en áreas operativas',
             'patron' => $todas, 'inicial' => []],

            ['id' => 'A-18', 'programa' => 'GESTIÓN INTEGRAL RESIDUOS',
             'actividad' => 'Actualización de inventario de residuos, RESPEL y registros',
             'ods_codigo' => 'ODS 12', 'ods_nombre' => 'ODS 12: Producción y Consumo Responsable', 'ods_corto' => 'ODS 12: Prod. y Consumo',
             'meta' => 'Matriz Actualizada', 'responsable' => 'Gestión Ambiental', 'frecuencia' => 'Bimestral',
             'observaciones' => 'Actualización bimestral/trimestral',
             'patron' => [3, 13, 23], 'inicial' => []],

            ['id' => 'A-19', 'programa' => 'ECONOMÍA CIRCULAR Y DE CUMPLIMIENTO',
             'actividad' => 'Logística Inversa Circular con Clientes (Retorno de Insumos)',
             'ods_codigo' => 'ODS 12', 'ods_nombre' => 'ODS 12: Producción y Consumo Responsable', 'ods_corto' => 'ODS 12: Prod. y Consumo',
             'meta' => '100% Retorno Material', 'responsable' => 'Gestión Ambiental', 'frecuencia' => 'Permanente',
             'observaciones' => 'Retorno para ciclos de re-manufactura',
             'patron' => $todas, 'inicial' => []],

            ['id' => 'A-20', 'programa' => 'ECONOMÍA CIRCULAR Y DE CUMPLIMIENTO',
             'actividad' => 'Elaboración y presentación de indicadores ambientales a alta dirección',
             'ods_codigo' => 'ODS 12', 'ods_nombre' => 'ODS 12: Producción y Consumo Responsable', 'ods_corto' => 'ODS 12: Prod. y Consumo',
             'meta' => '1 Presentación Mensual', 'responsable' => 'Gestión Ambiental', 'frecuencia' => 'Mensual',
             'observaciones' => 'Presentación mensual a Jefatura',
             'patron' => $s2Mes, 'inicial' => []],

            ['id' => 'A-21', 'programa' => 'ECONOMÍA CIRCULAR Y DE CUMPLIMIENTO',
             'actividad' => 'Evaluación de desempeño de gestores estratégicos (Cumplimiento/Oportunidad)',
             'ods_codigo' => 'ODS 12', 'ods_nombre' => 'ODS 12: Producción y Consumo Responsable', 'ods_corto' => 'ODS 12: Prod. y Consumo',
             'meta' => '1 Evaluac. Semestral', 'responsable' => 'Gestión Ambiental', 'frecuencia' => 'Semestral',
             'observaciones' => 'Indicadores de cumplimiento, trazabilidad y sostenibilidad',
             'patron' => [24], 'inicial' => []],

            ['id' => 'A-22', 'programa' => 'ECONOMÍA CIRCULAR Y DE CUMPLIMIENTO',
             'actividad' => 'Campañas de sensibilización sobre separación, RESPEL y economía circular',
             'ods_codigo' => 'ODS 12', 'ods_nombre' => 'ODS 12: Producción y Consumo Responsable', 'ods_corto' => 'ODS 12: Prod. y Consumo',
             'meta' => '90% Asistencia', 'responsable' => 'Gestión Ambiental', 'frecuencia' => 'Mensual',
             'observaciones' => 'Educar al personal operativo',
             'patron' => $s3Mes, 'inicial' => []],

            ['id' => 'A-23', 'programa' => 'ECONOMÍA CIRCULAR Y DE CUMPLIMIENTO',
             'actividad' => 'Reporte periódico del monitoreo de lodos de la PTAR (Gestión y Control de Lodos)',
             'ods_codigo' => 'ODS 12', 'ods_nombre' => 'ODS 12: Producción y Consumo Responsable', 'ods_corto' => 'ODS 12: Prod. y Consumo',
             'meta' => '1 Reporte Mensual', 'responsable' => 'Gestión Ambiental', 'frecuencia' => 'Mensual',
             'observaciones' => 'Garantizar estabilización, deshidratación y disposición final adecuada',
             'patron' => $s4Mes, 'inicial' => []],

            // ================ ODS 13: ACCIÓN POR EL CLIMA - 7 ================
            ['id' => 'A-24', 'programa' => 'TRANSICIÓN ENERGÉTICA Y FLOTA VERDE',
             'actividad' => 'Seguimiento al avance de adquisición/reconversión a tecnología GNV',
             'ods_codigo' => 'ODS 13', 'ods_nombre' => 'ODS 13: Acción por el Clima', 'ods_corto' => 'ODS 13: Acción Clima',
             'meta' => '1 Reporte Avance', 'responsable' => 'Gestión Ambiental', 'frecuencia' => 'Bimensual',
             'observaciones' => 'Seguimiento bimensual a proveedores',
             'patron' => [0, 10, 20], 'inicial' => []],

            ['id' => 'A-25', 'programa' => 'TRANSICIÓN ENERGÉTICA Y FLOTA VERDE',
             'actividad' => 'Seguimiento mensual al consumo de combustible y rendimiento (km/gal o km/m3)',
             'ods_codigo' => 'ODS 13', 'ods_nombre' => 'ODS 13: Acción por el Clima', 'ods_corto' => 'ODS 13: Acción Clima',
             'meta' => 'Medición Exacta', 'responsable' => 'Gestión Ambiental', 'frecuencia' => 'Mensual',
             'observaciones' => 'Habilitar reducciones efectivas en 2027',
             'patron' => $todas, 'inicial' => []],

            ['id' => 'A-26', 'programa' => 'TRANSICIÓN ENERGÉTICA Y FLOTA VERDE',
             'actividad' => 'IA predictiva, ruteo dinámico y avance certificación Giro Zero',
             'ods_codigo' => 'ODS 13', 'ods_nombre' => 'ODS 13: Acción por el Clima', 'ods_corto' => 'ODS 13: Acción Clima',
             'meta' => '1 Avance Giro Zero', 'responsable' => 'Gestión Ambiental', 'frecuencia' => 'Bimestral',
             'observaciones' => 'Optimizar rutas y proyectar certificación 2027',
             'patron' => [5, 15, 25], 'inicial' => []],

            ['id' => 'A-27', 'programa' => 'GESTIÓN DE EMISIONES Y COMPENSACIÓN',
             'actividad' => 'Consolidación de Inventario Anual de GEI 2026 para metas SBTi',
             'ods_codigo' => 'ODS 13', 'ods_nombre' => 'ODS 13: Acción por el Clima', 'ods_corto' => 'ODS 13: Acción Clima',
             'meta' => 'Línea Base Oficial', 'responsable' => 'Gestión Ambiental', 'frecuencia' => 'Anual',
             'observaciones' => 'Consolidar informe oficial a fin de año',
             'patron' => $s5Mes, 'inicial' => []],

            ['id' => 'A-28', 'programa' => 'GESTIÓN DE EMISIONES Y COMPENSACIÓN',
             'actividad' => 'Cálculo y análisis periódico de huella de carbono por transporte',
             'ods_codigo' => 'ODS 13', 'ods_nombre' => 'ODS 13: Acción por el Clima', 'ods_corto' => 'ODS 13: Acción Clima',
             'meta' => 'Cálculo Mensual', 'responsable' => 'Gestión Ambiental', 'frecuencia' => 'Mensual',
             'observaciones' => 'Corte trimestral y planes de mejora',
             'patron' => $s5Mes, 'inicial' => []],

            ['id' => 'A-29', 'programa' => 'GESTIÓN DE EMISIONES Y COMPENSACIÓN',
             'actividad' => 'Programa Integral Inserción Climática: Apadrinar ecosistemas locales',
             'ods_codigo' => 'ODS 13', 'ods_nombre' => 'ODS 13: Acción por el Clima', 'ods_corto' => 'ODS 13: Acción Clima',
             'meta' => 'Ecosistema Definido', 'responsable' => 'Gestión Ambiental', 'frecuencia' => 'Trimestral',
             'observaciones' => 'Evolución a compensación por ecosistemas locales',
             'patron' => [8, 18, 28], 'inicial' => []],

            ['id' => 'A-30', 'programa' => 'GESTIÓN DE EMISIONES Y COMPENSACIÓN',
             'actividad' => 'Campañas de educación ambiental sobre cambio climático y emisiones',
             'ods_codigo' => 'ODS 13', 'ods_nombre' => 'ODS 13: Acción por el Clima', 'ods_corto' => 'ODS 13: Acción Clima',
             'meta' => '90% Asistencia', 'responsable' => 'Gestión Ambiental', 'frecuencia' => 'Mensual',
             'observaciones' => 'Fomentar cultura climática',
             'patron' => $s4Mes, 'inicial' => []],
        ]);
    }

    /**
     * Vista principal (Cuadro de Mando + Cronograma).
     */
    public function index()
    {
        return view('plandeprocedimiento', [
            'actividades' => $this->actividades(),
            'meses'       => ['JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'],
        ]);
    }

    /**
     * API: devuelve el último estado guardado en la base de datos.
     */
    public function datos()
    {
        if (!PlanSeguimientoRegistro::exists()) {
            return response()->json(['estados' => null, 'meta' => null]);
        }

        $estados = $this->estadosActuales();
        $ultimo  = PlanSeguimientoHistorial::latest('id')->first();

        return response()->json([
            'estados' => $estados,
            'meta'    => [
                'guardado_por' => $ultimo->nombre_usuario ?? 'Usuario',
                'guardado_el'  => optional($ultimo->created_at)->format('d/m/Y H:i'),
                'cumplimiento' => $ultimo->cumplimiento_general ?? null,
            ],
        ]);
    }

    /**
     * API: guarda la matriz del cronograma en la base de datos
     * (tabla de estado actual + bitácora de historial).
     */
    public function guardar(Request $request)
    {
        $request->validate([
            'estados'   => ['required', 'array'],
            'estados.*' => ['array'],
        ]);

        $estados = $request->input('estados');
        $usuario = $this->nombreUsuario();
        $ahora   = now();
        $permitidos = ['P', 'E', 'EP', 'AT'];

        $resultado = DB::transaction(function () use ($estados, $usuario, $ahora, $permitidos) {

            // 1) Sincronizar estado actual (se reemplaza el snapshot anterior)
            PlanSeguimientoRegistro::query()->delete();

            $filas = [];
            foreach ($estados as $actividadId => $semanas) {
                if (!is_array($semanas)) continue;
                foreach ($semanas as $semana => $estado) {
                    $estado = trim((string) $estado);
                    if (!in_array($estado, $permitidos, true)) continue;
                    $filas[] = [
                        'actividad_id'  => (string) $actividadId,
                        'semana'        => (int) $semana,
                        'estado'        => $estado,
                        'usuario_id'    => auth()->id(),
                        'nombre_usuario' => $usuario,
                        'registrado_el' => $ahora,
                        'created_at'    => $ahora,
                        'updated_at'    => $ahora,
                    ];
                }
            }

            foreach (array_chunk($filas, 250) as $chunk) {
                PlanSeguimientoRegistro::insert($chunk);
            }

            // 2) Bitácora del guardado con el resumen calculado
            $resumen = $this->calcularResumen($estados);

            PlanSeguimientoHistorial::create([
                'usuario_id'            => auth()->id(),
                'nombre_usuario'        => $usuario,
                'cumplimiento_general'  => $resumen['general'],
                'celdas_registradas'    => count($filas),
                'resumen'               => $resumen,
            ]);

            return count($filas);
        });

        return response()->json([
            'ok'        => true,
            'mensaje'   => "Plan guardado en la base de datos ({$resultado} celdas registradas).",
            'meta'      => [
                'guardado_por' => $usuario,
                'guardado_el'  => $ahora->format('d/m/Y H:i'),
            ],
        ]);
    }

    /**
     * PDF con TEXTO REAL (Dompdf): Cuadro de Mando + Cronograma completo.
     */
    public function exportarPdf()
    {
        $actividades = $this->actividades()->values();
        $meses       = ['JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'];
        $estados     = $this->estadosActuales();
        $resumen     = $this->calcularResumen($estados);

        $ultimo = PlanSeguimientoHistorial::latest('id')->first();
        $meta = [
            'generado'    => now()->format('d/m/Y H:i'),
            'guardado_por' => $ultimo->nombre_usuario ?? 'Formato original',
            'guardado_el'  => optional($ultimo->created_at)->format('d/m/Y H:i') ?: 'Sin registros',
        ];

        $pdf = app('dompdf.wrapper')
            ->loadView('pdf.plandeprocedimiento', compact('actividades', 'meses', 'estados', 'resumen', 'meta'))
            ->setPaper('a3', 'landscape');

        // stream() = se abre en el navegador (texto seleccionable).
        // Si prefieres descarga directa, cambia ->stream(...) por ->download(...)
        return $pdf->stream('PLAN_DE_SEGUIMIENTO_SOSTENIBILIDAD_PLEXA_' . now()->format('Ymd_His') . '.pdf');
    }

    // ============================================================
    //                      HELPERS PRIVADOS
    // ============================================================

    /**
     * Estado actual: si hay registros en BD se toman de ahí;
     * si no, se devuelve el formato original (patrones P).
     */
    private function estadosActuales(): array
    {
        $actividades = $this->actividades();
        $registros   = PlanSeguimientoRegistro::all()->groupBy('actividad_id');

        $estados = [];
        foreach ($actividades as $act) {
            $fila = array_fill(0, 30, '');

            if ($registros->has($act['id'])) {
                // Estado guardado en BD (las celdas ausentes quedan vacías)
                foreach ($registros[$act['id']] as $r) {
                    if ($r->semana >= 0 && $r->semana < 30) {
                        $fila[$r->semana] = $r->estado ?? '';
                    }
                }
            } else {
                // Sin registros guardados: formato original
                foreach ($act['patron'] as $s) $fila[$s] = 'P';
                foreach ($act['inicial'] as $s => $st) $fila[$s] = $st;
            }

            $estados[$act['id']] = $fila;
        }

        return $estados;
    }

    /**
     * Misma lógica de cálculo del Excel / del JavaScript:
     * % actividad = E / programadas | % ODS = promedio | % general = promedio total.
     */
    private function calcularResumen(array $estados): array
    {
        $porActividad = [];
        $totales  = ['E' => 0, 'P' => 0, 'EP' => 0, 'AT' => 0];
        $sumaOds  = [];
        $conteoOds = [];

        foreach ($this->actividades() as $act) {
            $fila = $estados[$act['id']] ?? [];
            $c = ['E' => 0, 'P' => 0, 'EP' => 0, 'AT' => 0];

            for ($s = 0; $s < 30; $s++) {
                $st = $fila[$s] ?? '';
                if (isset($c[$st])) {
                    $c[$st]++;
                    $totales[$st]++;
                }
            }

            $prog = array_sum($c);
            $pct  = $prog > 0 ? round($c['E'] / $prog * 100, 1) : 0.0;
            $porActividad[$act['id']] = $pct;

            $ods = $act['ods_codigo'];
            $sumaOds[$ods]   = ($sumaOds[$ods] ?? 0) + $pct;
            $conteoOds[$ods] = ($conteoOds[$ods] ?? 0) + 1;
        }

        $porOds = [];
        foreach ($sumaOds as $ods => $suma) {
            $porOds[$ods] = round($suma / $conteoOds[$ods], 1);
        }

        $general = count($porActividad) > 0
            ? round(array_sum($porActividad) / count($porActividad), 1)
            : 0.0;

        return [
            'general'       => $general,
            'por_ods'       => $porOds,
            'totales'       => $totales,
            'por_actividad' => $porActividad,
        ];
    }

    /**
     * Nombre del usuario autenticado (ajustado a tu sistema de sesión).
     */
    private function nombreUsuario(): string
    {
        if (!auth()->check()) {
            return 'Sistema';
        }
        $u = auth()->user();
        return $u->name ?? $u->nombre ?? $u->NombreUsuario ?? $u->usuario ?? ('Usuario #' . auth()->id());
    }
}