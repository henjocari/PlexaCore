<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/favicon.png') }}">
    <title>Plexa Core - Plan de Seguimiento de Sostenibilidad</title>

    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <style>
        :root {
            --primary-blue: #0F4C81;
            --border-color: #e3e6f0;
            --ods3: #4C9F38;
            --ods12: #BF8B2E;
            --ods13: #3F7E44;
        }

        .fondo-plexa {
            background: #f8f9fc url("{{ asset('img/Logo_plexa.svg') }}") center/70% no-repeat fixed;
            min-height: 100vh;
            padding-bottom: 3rem;
        }

        /* El cronograma es muy ancho: ampliamos el limite del contenedor */
        .form-container-limit { max-width: 1200px; margin: 0 auto; }
        .plan-container-limit { max-width: 1780px; }

        .card-custom {
            background-color: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(4px);
            border-radius: 12px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .table-custom th {
            background-color: var(--primary-blue);
            color: #ffffff;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            vertical-align: middle;
            text-align: center;
        }

        .table-custom td { vertical-align: middle; background-color: #ffffff; }

        .section-header {
            border-left: 4px solid var(--primary-blue);
            padding-left: 10px;
            color: var(--primary-blue);
            font-weight: 700;
        }

        .badge-version {
            background-color: #eaecf4; color: var(--primary-blue);
            font-weight: 700; padding: 6px 12px; border-radius: 20px; font-size: 0.75rem;
        }

        .fila-total td {
            background-color: var(--primary-blue) !important;
            color: #ffffff !important;
        }

        .punto-ods {
            display: inline-block; width: 12px; height: 12px;
            border-radius: 50%; margin-right: 6px;
        }

        /* ============ CRONOGRAMA ============ */
        .tabla-cronograma { min-width: 2500px; font-size: 0.72rem; margin-bottom: 0; }
        .tabla-cronograma thead th { font-size: 0.66rem; padding: 4px 6px; }
        .th-info { min-width: 70px; }
        .col-programa { min-width: 130px; }
        .th-mes     { background: #0F4C81 !important; letter-spacing: .08em; }
        .th-mes-alt { background: #0b3a63 !important; letter-spacing: .08em; }
        .th-sem     { background: #36486b !important; padding: 2px !important; font-size: 0.6rem; }

        .celda-programa {
            background: #eef2f7; color: var(--primary-blue);
            font-weight: 700; font-size: 0.66rem; vertical-align: middle !important;
        }
        .celda-id, .celda-meta, .celda-frec, .celda-resp {
            font-size: 0.66rem; text-align: center; white-space: nowrap;
        }
        .celda-actividad { min-width: 200px; max-width: 260px; }
        .celda-obs { min-width: 160px; max-width: 225px; font-size: 0.65rem; }
        .celda-ods { font-size: 0.62rem; font-weight: 700; white-space: nowrap; }
        .ods-ODS3  { color: var(--ods3); }
        .ods-ODS12 { color: #9a6f16; }
        .ods-ODS13 { color: #2c6e33; }
        .celda-pct { text-align: center; min-width: 60px; background: #f4f6fa; }

        .celda-estado {
            width: 38px; min-width: 38px; max-width: 38px; height: 27px;
            text-align: center; vertical-align: middle !important; cursor: pointer;
            font-size: 0.68rem; font-weight: 800; user-select: none; transition: transform .06s;
        }
        .celda-estado:hover { outline: 2px solid var(--primary-blue); outline-offset: -2px; }
        .celda-estado:active { transform: scale(.9); }
        #cronograma-body tr:hover td:not(.celda-estado) { background-color: #f1f4f9; }

        /* Código de colores del Excel */
        .estado-P  { background-color: #d1e3f8 !important; color: var(--primary-blue); }
        .estado-E  { background-color: #28a745 !important; color: #ffffff; }
        .estado-EP { background-color: #f6c23e !important; color: #4a4113; }
        .estado-AT { background-color: #e74a3b !important; color: #ffffff; }

        .demo-estado {
            display: inline-block; width: 34px; text-align: center;
            border-radius: 4px; padding: 3px 0; font-weight: 800; font-size: .75rem;
        }

        .toast-plan {
            position: fixed; bottom: 25px; right: 25px; z-index: 3000;
            padding: 12px 20px; border-radius: 8px; color: #fff; font-weight: 600;
            transition: opacity .4s; box-shadow: 0 .5rem 1rem rgba(0,0,0,.25); max-width: 360px;
            opacity: 0; pointer-events: none;
        }
        .toast-plan.success { background: #1cc88a; }
        .toast-plan.warning { background: #f6c23e; color: #333; }
        .toast-plan.info    { background: var(--primary-blue); }

        @media print {
            @page { size: A3 landscape; margin: 6mm; }
            body { background: white !important; color: black !important; }
            #accordionSidebar, #content-wrapper > div > nav, footer, .no-print { display: none !important; }
            #content-wrapper, #content, .container-fluid { padding: 0 !important; margin: 0 !important; width: 100% !important; }
            .card-custom { box-shadow: none !important; border: none !important; background: transparent !important; }
            .table-custom th, .estado-P, .estado-E, .estado-EP, .estado-AT, .th-mes, .th-mes-alt, .th-sem, .fila-total td {
                -webkit-print-color-adjust: exact; print-color-adjust: exact;
            }
            .table-responsive { overflow: visible !important; }
        }
    </style>
</head>

<body id="page-top">

<div id="wrapper">

    @include('layouts.menu')

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            @include('layouts.cabecera')

            <div class="container-fluid fondo-plexa py-4">
                <div class="form-container-limit plan-container-limit">

                    <!-- TÍTULO DE LA PÁGINA + ACCIONES -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800 px-3 py-2 rounded shadow-sm bg-white border-left-primary">
                            <i class="fas fa-leaf mr-2 text-primary"></i> Plan de Seguimiento - Sostenibilidad
                        </h1>
                        <div class="no-print mt-3 mt-sm-0">
                            <a href="#resumen-ejecutivo" class="btn btn-sm btn-outline-secondary shadow-sm">
                                <i class="fas fa-tachometer-alt mr-1"></i> Cuadro de Mando
                            </a>
                            <a href="#plan-de-seguimiento" class="btn btn-sm btn-outline-secondary shadow-sm ml-1">
                                <i class="fas fa-calendar-alt mr-1"></i> Cronograma
                            </a>
                            <button type="button" class="btn btn-sm btn-warning shadow-sm ml-1" onclick="reiniciarPlan(this)">
                                <i class="fas fa-undo mr-1"></i> Reiniciar
                            </button>
                            <button type="button" class="btn btn-sm btn-success shadow-sm ml-1" onclick="guardarPlan(this)">
                                <i class="fas fa-save mr-1"></i> Guardar Registro
                                <span id="badge-cambios" class="badge badge-danger ml-1" style="display:none">•</span>
                            </button>
                            <button type="button" class="btn btn-sm btn-primary shadow-sm ml-1" onclick="exportarPDFVectorial()">
                                <i class="fas fa-print mr-1"></i> Imprimir / PDF
                            </button>
                        </div>
                    </div>

                    {{-- Mensajes de Notificación --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                            <strong class="d-block mb-1"><i class="fas fa-exclamation-triangle mr-1"></i> Por favor corrige los siguientes errores:</strong>
                            <ul class="mb-0 pl-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                    @endif

                    <div id="documento-plan">

                        {{-- ================================================================= --}}
                        {{-- HOJA 1: RESUMEN EJECUTIVO (Cuadro de Mando)                       --}}
                        {{-- ================================================================= --}}
                        <div class="card card-custom mb-4" id="resumen-ejecutivo">
                            <div class="card-body p-4">

                                <div class="d-flex flex-wrap align-items-center justify-content-between border-bottom pb-3 mb-4">
                                    <div>
                                        <h4 class="font-weight-bold text-primary mb-1">CUADRO DE MANDO - SOSTENIBILIDAD PLEXA</h4>
                                        <p class="text-muted small mb-0 font-weight-bold">Monitoreo de Metas Estratégicas y Alineación ODS (2026)</p>
                                    </div>
                                    <div class="text-md-right mt-3 mt-md-0">
                                        <span class="badge-version">Generado: {{ date('Y-m-d') }}</span>
                                        <span class="badge-version ml-1">Versión 6.0 (Botón de Regreso a Resumen Ejecutivo)</span>
                                    </div>
                                </div>

                                {{-- ÍNDICE DEL RASTREADOR (equivale a los hipervínculos del Excel) --}}
                                <h6 class="section-header mb-3">ÍNDICE DEL RASTREADOR</h6>
                                <div class="table-responsive mb-4">
                                    <table class="table table-bordered table-sm table-custom">
                                        <thead>
                                            <tr>
                                                <th style="width: 22%">Hoja</th>
                                                <th>Descripción</th>
                                                <th class="no-print" style="width: 18%">Enlace</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="font-weight-bold">Resumen Ejecutivo</td>
                                                <td class="small">Cuadro de mando, consolidado de cumplimiento y visualización gráfica.</td>
                                                <td class="no-print"><a href="#resumen-ejecutivo" class="font-weight-bold text-primary">Ir a Resumen →</a></td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">Plan de Seguimiento</td>
                                                <td class="small">Cronograma semanal detallado de las 30 actividades estratégicas.</td>
                                                <td class="no-print"><a href="#plan-de-seguimiento" class="font-weight-bold text-primary">Ir a Plan →</a></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                {{-- TARJETAS KPI --}}
                                <div class="row mb-4">
                                    <div class="col-xl-3 col-md-6 mb-4">
                                        <div class="card border-left-success shadow h-100 py-2">
                                            <div class="card-body">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">ODS 3: Salud y Bienestar (HSE)</div>
                                                        <div class="row no-gutters align-items-center">
                                                            <div class="col-auto"><div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><span id="kpi-pct-3">0.0%</span></div></div>
                                                            <div class="col"><div class="progress progress-sm mr-2"><div class="progress-bar bg-success" id="kpi-bar-3" role="progressbar" style="width:0%"></div></div></div>
                                                        </div>
                                                        <div class="small text-gray-500 mt-1"><i class="fas fa-tasks mr-1"></i><span id="kpi-act-3">13</span> actividades</div>
                                                    </div>
                                                    <div class="col-auto"><i class="fas fa-heartbeat fa-2x text-gray-300"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6 mb-4">
                                        <div class="card border-left-warning shadow h-100 py-2">
                                            <div class="card-body">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">ODS 12: Prod. y Consumo Responsable</div>
                                                        <div class="row no-gutters align-items-center">
                                                            <div class="col-auto"><div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><span id="kpi-pct-12">0.0%</span></div></div>
                                                            <div class="col"><div class="progress progress-sm mr-2"><div class="progress-bar bg-warning" id="kpi-bar-12" role="progressbar" style="width:0%"></div></div></div>
                                                        </div>
                                                        <div class="small text-gray-500 mt-1"><i class="fas fa-tasks mr-1"></i><span id="kpi-act-12">10</span> actividades</div>
                                                    </div>
                                                    <div class="col-auto"><i class="fas fa-recycle fa-2x text-gray-300"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6 mb-4">
                                        <div class="card border-left-info shadow h-100 py-2">
                                            <div class="card-body">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">ODS 13: Acción por el Clima</div>
                                                        <div class="row no-gutters align-items-center">
                                                            <div class="col-auto"><div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><span id="kpi-pct-13">0.0%</span></div></div>
                                                            <div class="col"><div class="progress progress-sm mr-2"><div class="progress-bar bg-info" id="kpi-bar-13" role="progressbar" style="width:0%"></div></div></div>
                                                        </div>
                                                        <div class="small text-gray-500 mt-1"><i class="fas fa-tasks mr-1"></i><span id="kpi-act-13">7</span> actividades</div>
                                                    </div>
                                                    <div class="col-auto"><i class="fas fa-cloud-sun fa-2x text-gray-300"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6 mb-4">
                                        <div class="card border-left-primary shadow h-100 py-2">
                                            <div class="card-body">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Promedio de Cumplimiento General</div>
                                                        <div class="row no-gutters align-items-center">
                                                            <div class="col-auto"><div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><span id="kpi-pct-general">0.0%</span></div></div>
                                                            <div class="col"><div class="progress progress-sm mr-2"><div class="progress-bar" id="kpi-bar-general" role="progressbar" style="width:0%"></div></div></div>
                                                        </div>
                                                        <div class="small text-gray-500 mt-1"><i class="fas fa-tasks mr-1"></i><span id="kpi-act-general">30</span> actividades</div>
                                                    </div>
                                                    <div class="col-auto"><i class="fas fa-chart-line fa-2x text-gray-300"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- CONSOLIDADO DE CUMPLIMIENTO POR ODS --}}
                                <h6 class="section-header mb-3">CONSOLIDADO DE CUMPLIMIENTO POR ODS</h6>
                                <div class="table-responsive mb-4">
                                    <table class="table table-bordered table-sm table-custom" id="tabla-consolidado">
                                        <thead>
                                            <tr>
                                                <th>Objetivo de Desarrollo Sostenible (ODS)</th>
                                                <th style="width: 16%">Actividades</th>
                                                <th style="width: 20%">% Cumplimiento</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><span class="punto-ods" style="background:#4C9F38"></span>ODS 3: Salud y Bienestar (HSE)</td>
                                                <td class="text-center font-weight-bold" id="cell-act-3">13</td>
                                                <td class="text-center font-weight-bold" id="cell-pct-3">0.0%</td>
                                            </tr>
                                            <tr>
                                                <td><span class="punto-ods" style="background:#BF8B2E"></span>ODS 12: Producción y Consumo Responsable</td>
                                                <td class="text-center font-weight-bold" id="cell-act-12">10</td>
                                                <td class="text-center font-weight-bold" id="cell-pct-12">0.0%</td>
                                            </tr>
                                            <tr>
                                                <td><span class="punto-ods" style="background:#3F7E44"></span>ODS 13: Acción por el Clima</td>
                                                <td class="text-center font-weight-bold" id="cell-act-13">7</td>
                                                <td class="text-center font-weight-bold" id="cell-pct-13">0.0%</td>
                                            </tr>
                                            <tr class="fila-total">
                                                <td>PROMEDIO DE CUMPLIMIENTO GENERAL</td>
                                                <td class="text-center" id="cell-act-general">30</td>
                                                <td class="text-center" id="cell-pct-general">0.0%</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                {{-- VISUALIZACIÓN GRÁFICA --}}
                                <div class="row mb-4">
                                    <div class="col-lg-7 mb-4">
                                        <div class="card shadow-sm h-100"><div class="card-body">
                                            <h6 class="section-header mb-3">% de Cumplimiento por ODS</h6>
                                            <canvas id="graficoBarrasODS" height="115"></canvas>
                                        </div></div>
                                    </div>
                                    <div class="col-lg-5 mb-4">
                                        <div class="card shadow-sm h-100"><div class="card-body">
                                            <h6 class="section-header mb-3">Distribución de Estados del Cronograma</h6>
                                            <canvas id="graficoDonaEstados" height="150"></canvas>
                                            <div class="row text-center mt-3 small font-weight-bold text-gray-700">
                                                <div class="col-3"><i class="fas fa-circle mr-1" style="color:#28a745"></i>Ejec.: <span id="contador-E">0</span></div>
                                                <div class="col-3"><i class="fas fa-circle mr-1" style="color:#d1e3f8"></i>Plan.: <span id="contador-P">0</span></div>
                                                <div class="col-3"><i class="fas fa-circle mr-1" style="color:#f6c23e"></i>Proc.: <span id="contador-EP">0</span></div>
                                                <div class="col-3"><i class="fas fa-circle mr-1" style="color:#e74a3b"></i>Atras.: <span id="contador-AT">0</span></div>
                                            </div>
                                        </div></div>
                                    </div>
                                </div>

                                {{-- GUÍA DE ESTADOS Y CÓDIGO DE COLORES (igual al Excel) --}}
                                <h6 class="section-header mb-3">Guía de Estados y Código de Colores</h6>
                                <div class="row mb-2">
                                    <div class="col-md-3 col-6 mb-2">
                                        <div class="p-3 border rounded h-100 bg-white">
                                            <span class="demo-estado estado-P">P</span> <strong>Planificado</strong>
                                            <p class="small text-muted mb-0 mt-2">Planificado (Celda en azul claro para registrar datos de avance en campo)</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6 mb-2">
                                        <div class="p-3 border rounded h-100 bg-white">
                                            <span class="demo-estado estado-E">E</span> <strong>Ejecutado</strong>
                                            <p class="small text-muted mb-0 mt-2">Completado (Actividad ejecutada de manera exitosa y conforme)</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6 mb-2">
                                        <div class="p-3 border rounded h-100 bg-white">
                                            <span class="demo-estado estado-EP">EP</span> <strong>En Proceso</strong>
                                            <p class="small text-muted mb-0 mt-2">En Progreso (Actividad en fase de desarrollo o ejecución intermedia)</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6 mb-2">
                                        <div class="p-3 border rounded h-100 bg-white">
                                            <span class="demo-estado estado-AT">A</span> <strong>Atrasado</strong>
                                            <p class="small text-muted mb-0 mt-2">Atrasado / Alerta (Actividad rezagada frente al cronograma o desvío operacional)</p>
                                        </div>
                                    </div>
                                </div>

                                <p class="text-muted small mb-0">
                                    <i class="fas fa-database mr-1"></i> Fuente de datos actual: <strong id="fuente-datos">Formato original</strong>
                                </p>
                            </div>
                        </div>

                        {{-- ================================================================= --}}
                        {{-- HOJA 2: PLAN DE SEGUIMIENTO (Cronograma)                          --}}
                        {{-- ================================================================= --}}
                        <div class="card card-custom mb-4" id="plan-de-seguimiento">
                            <div class="card-body p-4">

                                {{-- BOTÓN DE REGRESO (Versión 6.0) --}}
                                <a href="#resumen-ejecutivo" class="btn btn-sm btn-outline-primary shadow-sm mb-3 no-print">
                                    <i class="fas fa-arrow-left mr-1"></i> « Regresar al Resumen Ejecutivo (Cuadro de Mando)
                                </a>

                                <h4 class="font-weight-bold text-primary mb-1">PLAN DE SEGUIMIENTO: CRONOGRAMA DE ACTIVIDADES ESTRATÉGICAS (ODS)</h4>
                                <p class="text-muted small mb-0 font-weight-bold">Monitoreo semanal del cumplimiento físico de programas y metas de sostenibilidad de PLEXA</p>

                                <hr>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm table-custom tabla-cronograma" id="tabla-cronograma">
                                        <thead>
                                            <tr>
                                                <th rowspan="2" class="th-info col-programa">Programa Estratégico</th>
                                                <th rowspan="2" class="th-info">ID</th>
                                                <th rowspan="2" class="th-info">Actividad Específica</th>
                                                <th rowspan="2" class="th-info">Objetivo de Desarrollo Sostenible (ODS)</th>
                                                <th rowspan="2" class="th-info">Meta del Indicador de Desempeño</th>
                                                <th rowspan="2" class="th-info">Responsable</th>
                                                <th rowspan="2" class="th-info">Frecuencia</th>
                                                <th rowspan="2" class="th-info">Observaciones / Temario de Campo</th>
                                                <th rowspan="2" class="th-info">%</th>
                                                @foreach($meses as $i => $mes)
                                                    <th colspan="5" class="th-mes {{ $i % 2 ? 'th-mes-alt' : '' }}">{{ $mes }}</th>
                                                @endforeach
                                            </tr>
                                            <tr>
                                                @for($i = 0; $i < 6; $i++)
                                                    <th class="th-sem">S1</th><th class="th-sem">S2</th><th class="th-sem">S3</th><th class="th-sem">S4</th><th class="th-sem">S5</th>
                                                @endfor
                                            </tr>
                                        </thead>
                                        <tbody id="cronograma-body"></tbody>
                                    </table>
                                </div>

                                <p class="text-muted small mt-3 no-print mb-0">
                                    <i class="fas fa-info-circle mr-1 text-primary"></i>
                                    <strong>Clic izquierdo</strong> en una celda para avanzar el estado (Planificado → Ejecutado → En Proceso → Atrasado → Limpiar).
                                    <strong>Clic derecho</strong> para retroceder. Los porcentajes y el cuadro de mando se recalculan automáticamente.
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            @include('layouts.pie')

        </div>
    </div>
</div>

<div id="toast-plan" class="toast-plan"></div>

<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

<script>
    // ======================= CONFIGURACIÓN =======================
    const ACTIVIDADES  = @json($actividades);
    const MESES        = ['JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'];
    const TOTAL_SEMANAS = 30;
    const CLAVE_LOCAL  = 'plan-seguimiento-plexa';

    const RUTA_DATOS   = @json(\Route::has('plandeprocedimiento.datos') ? route('plandeprocedimiento.datos') : null);
    const RUTA_GUARDAR = @json(\Route::has('plandeprocedimiento.guardar') ? route('plandeprocedimiento.guardar') : null);
    const CSRF         = document.querySelector('meta[name="csrf-token"]').content;

    // Estados del cronograma (mismo código de colores del Excel)
    const CICLO         = ['', 'P', 'E', 'EP', 'AT'];
    const ETIQUETAS     = { '': '', 'P': 'P', 'E': 'E', 'EP': 'EP', 'AT': 'A' };
    const NOMBRE_ESTADO = { '': 'Sin programar', 'P': 'Planificado', 'E': 'Ejecutado', 'EP': 'En Proceso', 'AT': 'Atrasado' };
    const COLORES_ODS   = { 'ODS 3': '#4C9F38', 'ODS 12': '#BF8B2E', 'ODS 13': '#3F7E44' };
    const IDS_ODS       = { 'ODS 3': '3', 'ODS 12': '12', 'ODS 13': '13' };

    let matriz = {};
    let chartBarras = null, chartDona = null;
    let hayCambios = false;

    // ======================= UTILIDADES =======================
    function esc(s) {
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
                         .replace(/"/g,'&quot;').replace(/'/g,'&#39;');
    }

    function toast(msg, tipo) {
        const el = document.getElementById('toast-plan');
        el.textContent = msg;
        el.className = 'toast-plan ' + tipo;
        el.style.opacity = 1;
        clearTimeout(el._t);
        el._t = setTimeout(() => { el.style.opacity = 0; }, 3200);
    }

    function marcarCambios(v) {
        hayCambios = v;
        document.getElementById('badge-cambios').style.display = v ? 'inline-block' : 'none';
    }

    // ======================= MATRIZ DE ESTADOS =======================
    function matrizInicial() {
        const m = {};
        ACTIVIDADES.forEach(act => {
            m[act.id] = new Array(TOTAL_SEMANAS).fill('');
            (act.patron || []).forEach(s => m[act.id][s] = 'P');
            Object.entries(act.inicial || {}).forEach(([s, st]) => { m[act.id][s] = st; });
        });
        return m;
    }

    function aplicarEstadosExternos(estados) {
        Object.keys(estados).forEach(id => {
            if (!matriz[id]) return;
            const arr = estados[id];
            for (let s = 0; s < TOTAL_SEMANAS; s++) matriz[id][s] = arr[s] || '';
        });
    }

    // ======================= RENDER CRONOGRAMA =======================
    function renderCronograma() {
        const tbody = document.getElementById('cronograma-body');
        let html = '';
        let programaActual = null;

        ACTIVIDADES.forEach(act => {
            html += `<tr id="fila-${act.id}">`;
            if (act.programa !== programaActual) {
                const cantidad = ACTIVIDADES.filter(a => a.programa === act.programa).length;
                html += `<td rowspan="${cantidad}" class="celda-programa">${esc(act.programa)}</td>`;
                programaActual = act.programa;
            }
            const claseOds = 'ods-' + act.ods_codigo.split(' ').join('');
            html += `
                <td class="celda-id font-weight-bold">${act.id}</td>
                <td class="celda-actividad" title="${esc(act.actividad)}">${esc(act.actividad)}</td>
                <td class="celda-ods ${claseOds}">${esc(act.ods_corto)}</td>
                <td class="celda-meta">${esc(act.meta)}</td>
                <td class="celda-resp">${esc(act.responsable)}</td>
                <td class="celda-frec">${esc(act.frecuencia)}</td>
                <td class="celda-obs">${esc(act.observaciones)}</td>`;

            for (let s = 0; s < TOTAL_SEMANAS; s++) {
                const est = matriz[act.id][s] || '';
                const mesIdx = Math.floor(s / 5);
                const titulo = `${act.id} — ${MESES[mesIdx]} S${(s % 5) + 1} — ${NOMBRE_ESTADO[est]}`;
                html += `<td class="celda-estado ${est ? 'estado-' + est : ''}" data-id="${act.id}" data-semana="${s}" title="${esc(titulo)}">${ETIQUETAS[est]}</td>`;
            }
            html += `<td class="celda-pct font-weight-bold" id="pct-${act.id}">0.0%</td></tr>`;
        });

        tbody.innerHTML = html;
    }

    // ======================= INTERACCIÓN (edición de celdas) =======================
    const cuerpo = document.getElementById('cronograma-body');

    cuerpo.addEventListener('click', e => {
        const td = e.target.closest('.celda-estado');
        if (!td) return;
        cambiarEstado(td, 1);
    });

    cuerpo.addEventListener('contextmenu', e => {
        const td = e.target.closest('.celda-estado');
        if (!td) return;
        e.preventDefault();
        cambiarEstado(td, -1);
    });

    function cambiarEstado(td, direccion) {
        const id = td.dataset.id;
        const s = parseInt(td.dataset.semana, 10);
        const idx = CICLO.indexOf(matriz[id][s] || '');
        matriz[id][s] = CICLO[(idx + direccion + CICLO.length) % CICLO.length];
        aplicarEstadoCelda(td, matriz[id][s], id, s);
        programarRecalculo();
        marcarCambios(true);
    }

    function aplicarEstadoCelda(td, est, id, s) {
        CICLO.forEach(st => td.classList.remove('estado-' + st));
        if (est) td.classList.add('estado-' + est);
        td.textContent = ETIQUETAS[est];
        const mesIdx = Math.floor(s / 5);
        td.setAttribute('title', `${id} — ${MESES[mesIdx]} S${(s % 5) + 1} — ${NOMBRE_ESTADO[est]}`);
    }

    let recalcTimer = null;
    function programarRecalculo() {
        clearTimeout(recalcTimer);
        recalcTimer = setTimeout(actualizarResumen, 120);
        try { localStorage.setItem(CLAVE_LOCAL, JSON.stringify(matriz)); } catch (e) {}
    }

    // ======================= CÁLCULO DE CUMPLIMIENTO (lógica del Excel) =======================
    // % actividad = Ejecutadas / (Planificadas + Ejecutadas + En Proceso + Atrasadas)
    // % ODS        = promedio de las % de sus actividades
    // % General    = promedio de las 30 actividades
    function calcularResultados() {
        const res = {};
        const tot = { e: 0, p: 0, ep: 0, at: 0 };
        ACTIVIDADES.forEach(a => {
            let e = 0, p = 0, ep = 0, at = 0;
            matriz[a.id].forEach(st => {
                if (st === 'E') e++; else if (st === 'P') p++;
                else if (st === 'EP') ep++; else if (st === 'AT') at++;
            });
            const prog = e + p + ep + at;
            res[a.id] = { e, p, ep, at, prog, pct: prog ? (e / prog) * 100 : 0 };
            tot.e += e; tot.p += p; tot.ep += ep; tot.at += at;
        });
        return { res, tot };
    }

    // ======================= ACTUALIZAR RESUMEN EJECUTIVO =======================
    function actualizarResumen() {
        const { res, tot } = calcularResultados();

        // % por actividad (columna %)
        ACTIVIDADES.forEach(a => {
            const r = res[a.id];
            const td = document.getElementById('pct-' + a.id);
            if (!td) return;
            td.textContent = r.pct.toFixed(1) + '%';
            td.style.color = r.pct >= 75 ? '#1e7e34' : (r.pct >= 40 ? '#b78a12' : (r.prog > 0 ? '#c0392b' : '#858796'));
        });

        // Consolidado por ODS
        const ods = {};
        let sumaTotal = 0;
        ACTIVIDADES.forEach(a => {
            const k = a.ods_codigo;
            if (!ods[k]) ods[k] = { nombre: a.ods_nombre, total: 0, suma: 0 };
            ods[k].total++;
            ods[k].suma += res[a.id].pct;
            sumaTotal += res[a.id].pct;
        });
        Object.values(ods).forEach(o => o.pct = o.total ? o.suma / o.total : 0);
        const general = ACTIVIDADES.length ? sumaTotal / ACTIVIDADES.length : 0;

        Object.keys(ods).forEach(k => {
            const n = IDS_ODS[k];
            const pct = ods[k].pct;
            document.getElementById('kpi-pct-' + n).textContent = pct.toFixed(1) + '%';
            document.getElementById('kpi-act-' + n).textContent = ods[k].total;
            document.getElementById('kpi-bar-' + n).style.width = pct.toFixed(1) + '%';
            document.getElementById('cell-pct-' + n).textContent = pct.toFixed(1) + '%';
            document.getElementById('cell-act-' + n).textContent = ods[k].total;
        });
        document.getElementById('kpi-pct-general').textContent = general.toFixed(1) + '%';
        document.getElementById('kpi-act-general').textContent = ACTIVIDADES.length;
        document.getElementById('kpi-bar-general').style.width = general.toFixed(1) + '%';
        document.getElementById('cell-pct-general').textContent = general.toFixed(1) + '%';
        document.getElementById('cell-act-general').textContent = ACTIVIDADES.length;

        // Contadores de estados
        document.getElementById('contador-E').textContent  = tot.e;
        document.getElementById('contador-P').textContent  = tot.p;
        document.getElementById('contador-EP').textContent = tot.ep;
        document.getElementById('contador-AT').textContent = tot.at;

        // Gráficos
        if (chartBarras) {
            chartBarras.data.datasets[0].data = [ods['ODS 3'].pct, ods['ODS 12'].pct, ods['ODS 13'].pct, general];
            chartBarras.update();
        }
        if (chartDona) {
            chartDona.data.datasets[0].data = [tot.e, tot.p, tot.ep, tot.at];
            chartDona.update();
        }
    }

    // ======================= GRÁFICOS =======================
    function inicializarCharts() {
        if (typeof Chart === 'undefined') return;

        chartBarras = new Chart(document.getElementById('graficoBarrasODS'), {
            type: 'bar',
            data: {
                labels: ['ODS 3: Salud y Bienestar', 'ODS 12: Prod. y Consumo', 'ODS 13: Acción por el Clima', 'PROMEDIO GENERAL'],
                datasets: [{
                    label: '% de Cumplimiento',
                    data: [0, 0, 0, 0],
                    backgroundColor: ['#4C9F38', '#BF8B2E', '#3F7E44', '#0F4C81'],
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, max: 100, ticks: { callback: v => v + '%' } } }
            }
        });

        chartDona = new Chart(document.getElementById('graficoDonaEstados'), {
            type: 'doughnut',
            data: {
                labels: ['Ejecutadas', 'Planificadas', 'En Proceso', 'Atrasadas'],
                datasets: [{ data: [0, 0, 0, 0], backgroundColor: ['#28a745', '#d1e3f8', '#f6c23e', '#e74a3b'], borderWidth: 2 }]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
        });
    }

    // ======================= GUARDAR / CARGAR / REINICIAR =======================
    async function guardarPlan(btn) {
        const original = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Guardando...';
        try {
            localStorage.setItem(CLAVE_LOCAL, JSON.stringify(matriz));
            let msg = 'Plan guardado localmente en este navegador.';
            if (RUTA_GUARDAR) {
                const resp = await fetch(RUTA_GUARDAR, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                    body: JSON.stringify({ estados: matriz })
                });
                const data = await resp.json();
                if (resp.ok) msg = data.mensaje || 'Plan guardado correctamente.';
            }
            marcarCambios(false);
            toast(msg, 'success');
        } catch (e) {
            toast('No se pudo contactar el servidor. El plan quedó guardado solo en este navegador.', 'warning');
        }
        btn.disabled = false;
        btn.innerHTML = original;
    }

    async function reiniciarPlan(btn) {
        if (!confirm('¿Reiniciar el cronograma a los valores originales del formato?\nSe perderán los cambios registrados.')) return;
        const original = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Reiniciando...';
        matriz = matrizInicial();
        try { localStorage.removeItem(CLAVE_LOCAL); } catch (e) {}
        if (RUTA_GUARDAR) {
            try {
                await fetch(RUTA_GUARDAR, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                    body: JSON.stringify({ estados: matriz })
                });
            } catch (e) {}
        }
        renderCronograma();
        actualizarResumen();
        marcarCambios(false);
        toast('Cronograma reiniciado a los valores del formato original.', 'info');
        btn.disabled = false;
        btn.innerHTML = original;
    }

    async function cargarPlan() {
        matriz = matrizInicial();
        let fuente = 'Formato original';
        if (RUTA_DATOS) {
            try {
                const resp = await fetch(RUTA_DATOS, { headers: { 'Accept': 'application/json' } });
                const data = await resp.json();
                if (resp.ok && data.estados) { aplicarEstadosExternos(data.estados); fuente = 'Registro guardado en el servidor'; }
            } catch (e) {}
        }
        if (fuente === 'Formato original') {
            const local = localStorage.getItem(CLAVE_LOCAL);
            if (local) {
                try { aplicarEstadosExternos(JSON.parse(local)); fuente = 'Copia local del navegador'; } catch (e) {}
            }
        }
        return fuente;
    }

    // ======================= EXPORTAR PDF =======================
    function exportarPDFVectorial() {
        const elemento = document.getElementById('documento-plan');
        html2pdf().set({
            margin: [5, 5, 5, 5],
            filename: 'PLAN_DE_SEGUIMIENTO_SOSTENIBILIDAD_PLEXA.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, useCORS: true, scrollY: 0 },
            jsPDF: { unit: 'mm', format: 'a3', orientation: 'landscape' }
        }).from(elemento).save();
    }

    // ======================= NAVEGACIÓN (anclas = hipervínculos del Excel) =======================
    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', e => {
            const destino = document.querySelector(a.getAttribute('href'));
            if (destino) { e.preventDefault(); destino.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
        });
    });

    window.addEventListener('beforeunload', e => {
        if (hayCambios) { e.preventDefault(); e.returnValue = ''; }
    });

    // ======================= INICIO =======================
    (async function iniciar() {
        const fuente = await cargarPlan();
        renderCronograma();
        inicializarCharts();
        actualizarResumen();
        document.getElementById('fuente-datos').textContent = fuente;
    })();
</script>

</body>
</html>