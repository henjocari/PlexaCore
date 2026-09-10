<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Plan de Seguimiento - Sostenibilidad PLEXA</title>
    <style>
        body { font-family: "DejaVu Sans", sans-serif; font-size: 9px; color: #1f2937; margin: 0; }

        h1 { font-size: 15px; color: #0F4C81; margin: 0; }
        h2 { font-size: 12px; color: #0F4C81; margin: 0 0 3px 0; }
        .sub { font-size: 8px; color: #555555; margin: 1px 0; }

        .seccion-titulo {
            background-color: #0F4C81; color: #ffffff;
            font-weight: bold; font-size: 9px; padding: 3px 6px; margin: 10px 0 4px 0;
        }

        table { border-collapse: collapse; width: 100%; }
        td, th { border: 0.5pt solid #96a0b0; padding: 2px 3px; }

        .tabla-resumen th { background-color: #0F4C81; color: #ffffff; font-size: 8px; text-align: center; padding: 3px 4px; }
        .tabla-resumen td { font-size: 8.5px; }
        .fila-total td { background-color: #0F4C81; color: #ffffff; font-weight: bold; text-align: center; }
        .centro { text-align: center; }
        .punto { display: inline-block; width: 7px; height: 7px; border-radius: 50%; }

        /* ===== CRONOGRAMA ===== */
        .cronograma th { background-color: #0F4C81; color: #ffffff; font-size: 6.2px; text-align: center; padding: 2px; }
        .cronograma td { font-size: 6.2px; vertical-align: middle; }
        th.mes { font-size: 6.5px; letter-spacing: 0.5px; }
        th.sem { background-color: #36486b; font-size: 5.8px; }

        .programa { background-color: #eef2f7; color: #0F4C81; font-weight: bold; }
        .pct { text-align: center; font-weight: bold; background-color: #f4f6fa; }

        /* Estados: mismo código de colores del formato */
        .estado-P  { background-color: #d1e3f8; color: #0F4C81; font-weight: bold; text-align: center; }
        .estado-E  { background-color: #28a745; color: #ffffff; font-weight: bold; text-align: center; }
        .estado-EP { background-color: #f6c23e; color: #4a4113; font-weight: bold; text-align: center; }
        .estado-AT { background-color: #e74a3b; color: #ffffff; font-weight: bold; text-align: center; }

        .salto { page-break-before: always; }
        .cronograma tr { page-break-inside: avoid; }
    </style>
</head>
<body>

    {{-- ============ PÁGINA 1: CUADRO DE MANDO ============ --}}
    <h1>CUADRO DE MANDO - SOSTENIBILIDAD PLEXA</h1>
    <p class="sub">Monitoreo de Metas Estratégicas y Alineación ODS (2026)</p>
    <p class="sub">
        Generado: {{ $meta['generado'] }} &nbsp;|&nbsp; Versión 6.0 &nbsp;|&nbsp;
        Último registro en base de datos: {{ $meta['guardado_por'] }} ({{ $meta['guardado_el'] }})
    </p>

    <div class="seccion-titulo">CONSOLIDADO DE CUMPLIMIENTO POR ODS</div>
    <table class="tabla-resumen">
        <thead>
            <tr>
                <th align="left">Objetivo de Desarrollo Sostenible (ODS)</th>
                <th width="90">Actividades</th>
                <th width="110">% Cumplimiento</th>
            </tr>
        </thead>
        <tbody>
            @php
                $nombreOds = [
                    'ODS 3'  => 'ODS 3: Salud y Bienestar (HSE)',
                    'ODS 12' => 'ODS 12: Producción y Consumo Responsable',
                    'ODS 13' => 'ODS 13: Acción por el Clima',
                ];
                $colorOds = [
                    'ODS 3'  => '#4C9F38',
                    'ODS 12' => '#BF8B2E',
                    'ODS 13' => '#3F7E44',
                ];
            @endphp
            @foreach(['ODS 3', 'ODS 12', 'ODS 13'] as $ods)
                <tr>
                    <td><span class="punto" style="background-color: {{ $colorOds[$ods] }};"></span> {{ $nombreOds[$ods] }}</td>
                    <td class="centro">{{ $actividades->where('ods_codigo', $ods)->count() }}</td>
                    <td class="centro">{{ $resumen['por_ods'][$ods] ?? 0 }}%</td>
                </tr>
            @endforeach
            <tr class="fila-total">
                <td>PROMEDIO DE CUMPLIMIENTO GENERAL</td>
                <td>{{ $actividades->count() }}</td>
                <td>{{ $resumen['general'] }}%</td>
            </tr>
        </tbody>
    </table>

    <div class="seccion-titulo">DISTRIBUCIÓN DE ESTADOS DEL CRONOGRAMA Y GUÍA DE COLORES</div>
    <table class="tabla-resumen">
        <thead>
            <tr>
                <th width="110">Estado</th>
                <th width="70">Celdas</th>
                <th align="left">Descripción</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="estado-E">E - Ejecutado</td>
                <td class="centro">{{ $resumen['totales']['E'] }}</td>
                <td>Completado (Actividad ejecutada de manera exitosa y conforme)</td>
            </tr>
            <tr>
                <td class="estado-P">P - Planificado</td>
                <td class="centro">{{ $resumen['totales']['P'] }}</td>
                <td>Planificado (Celda en azul claro para registrar datos de avance en campo)</td>
            </tr>
            <tr>
                <td class="estado-EP">EP - En Proceso</td>
                <td class="centro">{{ $resumen['totales']['EP'] }}</td>
                <td>En Progreso (Actividad en fase de desarrollo o ejecución intermedia)</td>
            </tr>
            <tr>
                <td class="estado-AT">A - Atrasado</td>
                <td class="centro">{{ $resumen['totales']['AT'] }}</td>
                <td>Atrasado / Alerta (Actividad rezagada frente al cronograma o desvío operacional)</td>
            </tr>
        </tbody>
    </table>

    {{-- ============ PÁGINA 2: CRONOGRAMA ============ --}}
    <div class="salto"></div>

    <h2>PLAN DE SEGUIMIENTO: CRONOGRAMA DE ACTIVIDADES ESTRATÉGICAS (ODS)</h2>
    <p class="sub">Monitoreo semanal del cumplimiento físico de programas y metas de sostenibilidad de PLEXA</p>

    <table class="cronograma">
        <thead>
            <tr>
                <th colspan="9"></th>
                @foreach($meses as $mes)
                    <th class="mes" colspan="5">{{ $mes }}</th>
                @endforeach
            </tr>
            <tr>
                <th width="80">Programa Estratégico</th>
                <th width="24">ID</th>
                <th width="200">Actividad Específica</th>
                <th width="58">ODS</th>
                <th width="58">Meta del Indicador</th>
                <th width="44">Responsable</th>
                <th width="38">Frecuencia</th>
                <th width="115">Observaciones / Temario</th>
                <th width="28">%</th>
                @for($i = 0; $i < 6; $i++)
                    <th class="sem" width="20">S1</th><th class="sem" width="20">S2</th><th class="sem" width="20">S3</th><th class="sem" width="20">S4</th><th class="sem" width="20">S5</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @php
                $etiquetas = ['' => '', 'P' => 'P', 'E' => 'E', 'EP' => 'EP', 'AT' => 'A'];
                $programaAnterior = null;
            @endphp
            @foreach($actividades as $act)
                <tr>
                    <td class="programa">{{ $act['programa'] !== $programaAnterior ? $act['programa'] : '' }}</td>
                    @php $programaAnterior = $act['programa']; @endphp
                    <td class="centro">{{ $act['id'] }}</td>
                    <td>{{ $act['actividad'] }}</td>
                    <td class="centro">{{ $act['ods_corto'] }}</td>
                    <td class="centro">{{ $act['meta'] }}</td>
                    <td class="centro">{{ $act['responsable'] }}</td>
                    <td class="centro">{{ $act['frecuencia'] }}</td>
                    <td>{{ $act['observaciones'] }}</td>
                    <td class="pct">{{ $resumen['por_actividad'][$act['id']] ?? 0 }}%</td>
                    @for($s = 0; $s < 30; $s++)
                        @php $est = $estados[$act['id']][$s] ?? ''; @endphp
                        <td class="{{ $est !== '' ? 'estado-'.$est : '' }}">{{ $etiquetas[$est] ?? '' }}</td>
                    @endfor
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>