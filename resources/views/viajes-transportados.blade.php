<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Informe Gerencial - Viajes Transportados</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('img/favicon.png') }}">
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    
    <!-- LIBRERÍA SELECT2 (Buscador en listas) -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body id="page-top">
    <div id="wrapper">
        @include('layouts.menu')

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                @include('layouts.cabecera')

                <div class="container-fluid pt-4">
                    <style>
                        .bg-plexa-dark { background-color: #114372 !important; }
                        .bg-plexa-green { background-color: #43b04a !important; }
                        .bg-plexa-lightblue { background-color: #0076b6 !important; }
                        .bg-plexa-lightgreen { background-color: #5cb85c !important; }
                        .text-plexa-dark { color: #114372 !important; }

                        .cabezote-fondo {
                            background-color: #114372;
                            border-radius: 0.35rem 0.35rem 0 0;
                            min-height: 180px;
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                            padding: 1.5rem 2rem;
                            position: relative;
                            overflow: hidden;
                        }

                        .camion-difuminado {
                            position: absolute;
                            top: 50%;
                            right: 260px;
                            transform: translateY(-50%);
                            width: 450px;
                            height: 140px; 
                            background-image: url('{{ asset('img/tractomula_sola.jpg') }}');
                            background-size: cover;
                            background-position: center;
                            -webkit-mask-image: linear-gradient(to right, transparent 0%, black 25%, black 75%, transparent 100%);
                            mask-image: linear-gradient(to right, transparent 0%, black 25%, black 75%, transparent 100%);
                            opacity: 0.8;
                            z-index: 1;
                        }

                        /* ESTILOS PARA ADAPTAR SELECT2 A BOOTSTRAP */
                        .select2-container .select2-selection--single {
                            height: 31px !important;
                            border: 1px solid #d1d3e2 !important;
                            border-radius: 0.2rem !important;
                        }
                        .select2-container--default .select2-selection--single .select2-selection__rendered {
                            line-height: 31px !important;
                            font-size: 0.875rem !important;
                            color: #6e707e !important;
                            padding-left: 0.5rem !important;
                        }
                        .select2-container--default .select2-selection--single .select2-selection__arrow {
                            height: 31px !important;
                        }
                        .select2-container--default .select2-search--dropdown .select2-search__field {
                            border: 1px solid #378E77 !important;
                            outline: none !important;
                        }
                        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
                            background-color: #114372 !important;
                            color: white !important;
                        }
                    </style>
                    <div id="reporte-pdf">
                        <!-- CABEZOTE PRINCIPAL -->
                        <div class="card shadow mb-4 border-0">
                            <div class="cabezote-fondo">
                                <div class="camion-difuminado"></div>
                                <div class="text-white" style="z-index: 2; position: relative;">
                                    <h1 class="h2 font-weight-bold mb-0" style="letter-spacing: 1px;">INFORME GERENCIAL</h1>
                                    <h2 class="h4 mb-3" style="font-weight: 300;">DE VIAJES TRANSPORTADOS</h2>
                                    <p class="mb-0 text-sm font-weight-bold">Periodo operativo: {{ \Carbon\Carbon::parse($fechaInicial)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($fechaFinal)->format('d/m/Y') }}</p>
                                    <small class="text-light">Fuente: SEGUIMIENTO DIARIO</small>
                                </div>
                                <div class="text-right d-none d-md-block pr-2" style="z-index: 2; position: relative;">
                                    <h3 class="font-weight-bolder text-white mb-0" style="line-height: 1.1; font-style: italic; letter-spacing: 1px; text-shadow: 2px 2px 6px rgba(0,0,0,0.6);">
                                        MOVEMOS<br>ENERGÍA<br>QUE IMPULSA<br>EL FUTURO
                                    </h3>
                                </div>
                            </div>

                            <!-- BARRA DE FECHAS Y CORTE -->
                            <div class="bg-light p-3 d-flex flex-wrap justify-content-start align-items-center border-bottom">
                                <div class="bg-white text-dark py-1 px-3 border rounded mr-3 mb-2 shadow-sm d-flex align-items-center">
                                    <i class="far fa-calendar-alt fa-2x text-primary mr-2" style="color: #114372 !important;"></i>
                                    <div>
                                        <small class="text-muted d-block" style="line-height: 1;">Desde</small>
                                        <span class="font-weight-bold h6 mb-0">{{ \Carbon\Carbon::parse($fechaInicial)->format('d.m.Y') }}</span>
                                    </div>
                                </div>
                                <div class="bg-white text-dark py-1 px-3 border rounded mr-3 mb-2 shadow-sm d-flex align-items-center">
                                    <i class="far fa-calendar-alt fa-2x text-primary mr-2" style="color: #114372 !important;"></i>
                                    <div>
                                        <small class="text-muted d-block" style="line-height: 1;">Hasta</small>
                                        <span class="font-weight-bold h6 mb-0">{{ \Carbon\Carbon::parse($fechaFinal)->format('d.m.Y') }}</span>
                                    </div>
                                </div>
                                <div class="border-left pl-3 mr-3 mb-2" style="height: 40px;"></div>
                                <div class="bg-white text-dark py-1 px-3 border rounded mb-2 shadow-sm d-flex align-items-center">
                                    <i class="far fa-file-alt fa-2x text-primary mr-2" style="color: #114372 !important;"></i>
                                    <div>
                                        <small class="text-muted d-block" style="line-height: 1;">Corte del informe:</small>
                                        <span class="font-weight-bold h6 mb-0">{{ \Carbon\Carbon::now()->format('d.m.Y') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- FORMULARIO DE FILTROS DINÁMICOS -->
                            <div class="card-body py-3">
                                <form method="POST" action="{{ route('viajes.transportados') }}" class="row g-2 align-items-end m-0">
                                    @csrf
                                    <div class="col" style="min-width: 130px;">
                                        <label class="form-label text-xs font-weight-bold mb-1">Desde</label>
                                        <input type="date" name="fecha_inicial" class="form-control form-control-sm" value="{{ $fechaInicial }}">
                                    </div>
                                    <div class="col" style="min-width: 130px;">
                                        <label class="form-label text-xs font-weight-bold mb-1">Hasta</label>
                                        <input type="date" name="fecha_final" class="form-control form-control-sm" value="{{ $fechaFinal }}">
                                    </div>
                                    <div class="col" style="min-width: 120px;">
                                        <label class="form-label text-xs font-weight-bold mb-1">Manifiesto</label>
                                        <input type="text" name="manifiesto" class="form-control form-control-sm" placeholder="Ej: 12345" value="{{ request('manifiesto') }}" onkeypress="if(event.keyCode == 13) this.form.submit();">
                                    </div>
                                    <div class="col" style="min-width: 140px;">
                                        <label class="form-label text-xs font-weight-bold mb-1">Tipo Oper.</label>
                                        <select name="tipo_operacion" class="form-control form-control-sm select2-buscador">
                                            <option value="">Todos</option>
                                            @foreach($filtrosOpciones['operaciones'] as $opcion)
                                                <option value="{{ trim($opcion) }}" {{ trim(request('tipo_operacion')) == trim($opcion) ? 'selected' : '' }}>{{ trim($opcion) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col" style="min-width: 140px;">
                                        <label class="form-label text-xs font-weight-bold mb-1">Producto</label>
                                        <select name="producto" class="form-control form-control-sm select2-buscador">
                                            <option value="">Todos</option>
                                            @foreach($filtrosOpciones['productos'] as $opcion)
                                                <option value="{{ trim($opcion) }}" {{ trim(request('producto')) == trim($opcion) ? 'selected' : '' }}>{{ trim($opcion) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col" style="min-width: 160px;">
                                        <label class="form-label text-xs font-weight-bold mb-1">Poseedor</label>
                                        <select name="poseedor" class="form-control form-control-sm select2-buscador">
                                            <option value="">Todos</option>
                                            @foreach($filtrosOpciones['poseedores'] as $opcion)
                                                <option value="{{ trim($opcion) }}" {{ trim(request('poseedor')) == trim($opcion) ? 'selected' : '' }}>{{ trim($opcion) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-auto d-flex gap-2">
                                        <button type="submit" class="btn btn-primary btn-sm px-3 bg-plexa-dark border-0 mr-1" title="Aplicar filtros">
                                            <i class="fas fa-filter"></i>
                                        </button>
                                        <!-- Botón Excel (Usa el mismo form pero va a otra ruta) -->
                                        <button type="submit" formaction="{{ route('viajes.excel') }}" class="btn btn-success btn-sm px-3 border-0 mr-1" title="Exportar a Excel">
                                            <i class="fas fa-file-excel"></i>
                                        </button>
                                        <!-- Botón PDF (Ejecuta JS) -->
                                        <button type="button" onclick="exportarPDF()" class="btn btn-danger btn-sm px-3 border-0" title="Descargar PDF">
                                            <i class="fas fa-file-pdf"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- 6 TARJETAS KPI -->
                        <div class="row mb-4">
                            <div class="col">
                                <div class="card shadow h-100 border-0">
                                    <div class="card-header bg-plexa-dark text-white text-center py-2 border-0">
                                        <i class="fas fa-truck mb-1 d-block fa-lg"></i>
                                        <div class="text-xs font-weight-bold text-uppercase">VIAJES EFECTIVOS</div>
                                    </div>
                                    <div class="card-body text-center p-3 d-flex align-items-center justify-content-center">
                                        <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $dashboardData['viajes'] }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card shadow h-100 border-0">
                                    <div class="card-header bg-plexa-green text-white text-center py-2 border-0">
                                        <i class="fas fa-database mb-1 d-block fa-lg"></i>
                                        <div class="text-xs font-weight-bold text-uppercase">BARRILES</div>
                                    </div>
                                    <div class="card-body text-center p-3 d-flex align-items-center justify-content-center">
                                        <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $dashboardData['barriles'] }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card shadow h-100 border-0">
                                    <div class="card-header bg-plexa-lightblue text-white text-center py-2 border-0">
                                        <i class="fas fa-tint mb-1 d-block fa-lg"></i>
                                        <div class="text-xs font-weight-bold text-uppercase">GALONES</div>
                                    </div>
                                    <div class="card-body text-center p-3 d-flex align-items-center justify-content-center">
                                        <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $dashboardData['galones'] }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card shadow h-100 border-0">
                                    <div class="card-header bg-plexa-lightgreen text-white text-center py-2 border-0">
                                        <i class="fas fa-coins mb-1 d-block fa-lg"></i>
                                        <div class="text-xs font-weight-bold text-uppercase">FACTURACIÓN EMPRESA</div>
                                    </div>
                                    <div class="card-body text-center p-3 d-flex align-items-center justify-content-center">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dashboardData['facturacion'] }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card shadow h-100 border-0">
                                    <div class="card-header bg-plexa-dark text-white text-center py-2 border-0">
                                        <i class="fas fa-hand-holding-usd mb-1 d-block fa-lg"></i>
                                        <div class="text-xs font-weight-bold text-uppercase">COSTO TERCERO</div>
                                    </div>
                                    <div class="card-body text-center p-3 d-flex align-items-center justify-content-center">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dashboardData['costo_tercero'] }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card shadow h-100 border-0">
                                    <div class="card-header bg-plexa-green text-white text-center py-2 border-0">
                                        <i class="fas fa-chart-line mb-1 d-block fa-lg"></i>
                                        <div class="text-xs font-weight-bold text-uppercase">MARGEN</div>
                                    </div>
                                    <div class="card-body text-center p-3">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dashboardData['margen'] }}</div>
                                        <div class="text-sm font-weight-bold text-plexa-dark mt-1">({{ $dashboardData['margen_pct'] }})</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- LECTURA GERENCIAL Y GRÁFICO ESTADOS -->
                        <div class="row">
                            <div class="col-lg-5 mb-4">
                                <div class="card shadow h-100 border-0">
                                    <div class="card-header py-3 bg-plexa-dark text-white" style="border-radius: 0.35rem 0.35rem 0 0;">
                                        <h6 class="m-0 font-weight-bold"><i class="fas fa-file-alt mr-2"></i>LECTURA GERENCIAL</h6>
                                    </div>
                                    <div class="card-body d-flex flex-column justify-content-center">
                                        <div class="d-flex align-items-start mb-4">
                                            <i class="fas fa-check-circle text-success fa-2x mt-1 mr-3"></i>
                                            <p class="mb-0 text-gray-800 font-weight-bold text-lg">
                                                Se completaron <span class="text-plexa-dark">{{ $dashboardData['viajes'] }}</span> viajes y se movilizaron <span class="text-plexa-dark">{{ $dashboardData['barriles'] }}</span> barriles.
                                            </p>
                                        </div>
                                        <div class="d-flex align-items-start">
                                            <i class="fas fa-check-circle text-success fa-2x mt-1 mr-3"></i>
                                            <p class="mb-0 text-gray-800 font-weight-bold text-lg">
                                                La facturación alcanzó <span class="text-success">{{ $dashboardData['facturacion'] }}</span>; el margen fue <span class="text-success">{{ $dashboardData['margen'] }} ({{ $dashboardData['margen_pct'] }})</span>.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-7 mb-4">
                                <div class="card shadow h-100 border-0">
                                    <div class="card-header py-3 bg-light" style="border-radius: 0.35rem 0.35rem 0 0;">
                                        <h6 class="m-0 font-weight-bold text-plexa-dark"><i class="fas fa-chart-bar mr-2"></i>COMPORTAMIENTO DE MANIFIESTOS</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-bar pt-2 pb-2">
                                            <canvas id="chartComportamiento" style="max-height: 250px;"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- NUEVO GRÁFICO: VIAJES POR DÍA -->
                        <div class="row">
                            <div class="col-12 mb-4">
                                <div class="card shadow h-100 border-0">
                                    <div class="card-header py-3 bg-light" style="border-radius: 0.35rem 0.35rem 0 0;">
                                        <h6 class="m-0 font-weight-bold text-plexa-dark"><i class="fas fa-calendar-day mr-2"></i>VIAJES REALIZADOS POR DÍA</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-bar pt-2 pb-2">
                                            <canvas id="chartDiario" style="max-height: 300px;"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TOP 5 MANIFIESTOS Y RUTAS -->
                        <div class="row">
                            <div class="col-lg-6 mb-4">
                                <div class="card shadow h-100 border-0">
                                    <div class="card-header py-3 bg-plexa-dark text-white" style="border-radius: 0.35rem 0.35rem 0 0;">
                                        <h6 class="m-0 font-weight-bold"><i class="fas fa-trophy text-warning mr-2"></i>TOP 5: MANIFIESTOS MÁS RENTABLES</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover mb-0 text-sm align-middle">
                                                <thead class="bg-light text-plexa-dark">
                                                    <tr>
                                                        <th class="pl-4">Manifiesto</th>
                                                        <th>Origen <i class="fas fa-arrow-right mx-1"></i> Destino</th>
                                                        <th class="text-right pr-4">Margen</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($dashboardData['top_manifiestos'] as $topM)
                                                    <tr>
                                                        <td class="pl-4 font-weight-bold text-plexa-dark align-middle">{{ $topM['manifiesto'] }}</td>
                                                        <td class="align-middle">{{ $topM['origen'] }} <i class="fas fa-arrow-right mx-1 text-muted"></i> {{ $topM['destino'] }}</td>
                                                        <td class="text-right pr-4 align-middle">
                                                            <span class="text-success font-weight-bold">{{ $topM['margen_format'] }}</span><br>
                                                            <small class="text-plexa-dark font-weight-bold">({{ $topM['margen_pct_format'] }})</small>
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    <tr><td colspan="3" class="text-center text-muted py-3">No hay datos en este periodo</td></tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-4">
                                <div class="card shadow h-100 border-0">
                                    <div class="card-header py-3 bg-plexa-green text-white" style="border-radius: 0.35rem 0.35rem 0 0;">
                                        <h6 class="m-0 font-weight-bold"><i class="fas fa-route mr-2"></i>TOP 5: RUTAS CON MAYOR MARGEN</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover mb-0 text-sm align-middle">
                                                <thead class="bg-light text-plexa-dark">
                                                    <tr>
                                                        <th class="pl-4">Ruta (Origen a Destino)</th>
                                                        <th class="text-center">Viajes</th>
                                                        <th class="text-right pr-4">Margen Acumulado</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($dashboardData['top_rutas'] as $topR)
                                                    <tr>
                                                        <td class="pl-4 font-weight-bold align-middle">{{ $topR['ruta'] }}</td>
                                                        <td class="text-center align-middle"><span class="badge badge-light border shadow-sm px-2 py-1">{{ $topR['cantidad_viajes'] }}</span></td>
                                                        <td class="text-right pr-4 align-middle">
                                                            <span class="text-success font-weight-bold">{{ $topR['margen_format'] }}</span><br>
                                                            <small class="text-plexa-dark font-weight-bold">({{ $topR['margen_pct_format'] }})</small>
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    <tr><td colspan="3" class="text-center text-muted py-3">No hay datos en este periodo</td></tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- FIN CONTENEDOR PDF -->
                    <!-- FOOTER / BANNER INFERIOR -->
                    <div class="row">
                        <div class="col-12 mb-4">
                            <div class="card shadow border-0 bg-plexa-dark text-white">
                                <div class="card-body d-flex justify-content-between align-items-center py-3 px-4">
                                    <div class="d-flex align-items-center">
                                        <i class="fab fa-envira fa-2x mr-3 text-success"></i>
                                        <div style="line-height: 1.2;">
                                            <span class="d-block font-weight-bold">TRANSPORTE SEGURO,</span>
                                            <span class="font-weight-light">OPERACIONES EFICIENTES</span>
                                        </div>
                                    </div>
                                    <div class="text-right" style="line-height: 1.2;">
                                        <span class="d-block font-weight-bold">COMPROMETIDOS</span>
                                        <span class="font-weight-light">CON RESULTADOS</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            @include('layouts.pie')
        </div>
    </div>

    <!-- SCRIPTS CORE -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
    <script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>
    
    <!-- LIBRERÍA SELECT2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        function exportarPDF() {
            const elemento = document.getElementById('reporte-pdf');
            const opciones = {
                margin:       [5, 5, 5, 5], // Márgenes pequeños en mm
                filename:     'Informe_Gerencial.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { 
                    scale: 1.5, 
                    useCORS: true, 
                    letterRendering: true,
                    windowWidth: 1200 // Fuerza el diseño de escritorio para que no se amontone
                },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'landscape' }
            };
            
            html2pdf().set(opciones).from(elemento).save();
        }

        document.addEventListener("DOMContentLoaded", function() {
            
            // INICIALIZACIÓN DE SELECT2
            $('.select2-buscador').select2({
                width: '100%',
                language: {
                    noResults: function() { return "No se encontraron coincidencias"; }
                }
            }).on('change', function(e) {
                // Al seleccionar un item de la lista, envía el formulario automáticamente
                $(this).closest('form').submit();
            });

            // Gráfico de Comportamiento de Manifiestos
            var ctxComportamiento = document.getElementById("chartComportamiento").getContext("2d");
            new Chart(ctxComportamiento, {
                type: 'bar',
                data: {
                    labels: ["Realizados", "Activos", "Cumplidos", "Liquidados", "Facturados"],
                    datasets: [{
                        label: "Viajes",
                        backgroundColor: ["#4e73df", "#1cc88a", "#f6c23e", "#e74a3b", "#858796"],
                        hoverBackgroundColor: ["#2e59d9", "#17a673", "#f4b619", "#e02d1b", "#60616f"],
                        borderColor: "#4e73df",
                        data: {!! json_encode($dashboardData['chart_estados']) !!},
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    legend: { display: false },
                    scales: {
                        xAxes: [{ gridLines: { display: false }, ticks: { autoSkip: false } }],
                        yAxes: [{ ticks: { beginAtZero: true, precision: 0 } }]
                    }
                }
            });

            // Gráfico: Viajes por Día
            var ctxDiario = document.getElementById("chartDiario").getContext("2d");
            new Chart(ctxDiario, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($dashboardData['chart_diario']['fechas']) !!},
                    datasets: [{
                        label: "Viajes Realizados",
                        backgroundColor: "#36b9cc",
                        hoverBackgroundColor: "#2c9fae",
                        borderColor: "#36b9cc",
                        data: {!! json_encode($dashboardData['chart_diario']['totales']) !!},
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    legend: { display: false },
                    scales: {
                        xAxes: [{ 
                            gridLines: { display: false },
                            ticks: { maxTicksLimit: 15 }
                        }],
                        yAxes: [{ ticks: { beginAtZero: true, precision: 0 } }]
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, chart) {
                                return tooltipItem.yLabel + ' Viajes';
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>