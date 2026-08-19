<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/favicon.png') }}">
    <title>Gestión de Manifiestos</title>

    <!-- Fuentes y estilos SB Admin 2 -->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,900" rel="stylesheet">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

    <!-- Estilos DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap4.min.css">

    <style>
        .table td, .table th {
            vertical-align: middle !important;
            font-size: 0.83rem;
        }
        .dataTables_filter {
            display: none;
        }
    </style>
</head>

<body id="page-top">

    <div id="wrapper">

        <!-- MENÚ LATERAL -->
        @include('layouts.menu')

        <div id="content-wrapper" class="d-flex flex-column">
            
            <div id="content">
                <!-- NAVBAR / CABECERA -->
                @include('layouts.cabecera')

                <!-- CONTENIDO PRINCIPAL -->
                <div class="container-fluid py-3">
                    
                    <div class="d-sm-flex align-items-center justify-content-between mb-3">
                        <h2 class="h4 mb-0 text-gray-800 font-weight-bold">Gestión de Manifiestos</h2>
                        <div id="export-buttons-container" class="d-flex align-items-center"></div>
                    </div>

                    <!-- PANEL DE FILTROS HORIZONTALES -->
                    <div class="card shadow-sm mb-4 border-left-success">
                        <div class="card-header py-2 bg-white d-flex align-items-center justify-content-between">
                            <span class="font-weight-bold text-success small">
                                <i class="fas fa-filter mr-1"></i> Filtros de Búsqueda Horizontal
                            </span>
                            <button class="btn btn-sm btn-link text-muted p-0" type="button" data-toggle="collapse" data-target="#collapseFiltros">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </div>
                        <div class="collapse show" id="collapseFiltros">
                            <div class="card-body bg-light p-3">
                                <form id="formFiltros">
                                    <div class="d-flex flex-wrap align-items-end" style="gap: 10px;">
                                        
                                        <!-- Col 0: N° Manifiesto -->
                                        <div style="flex: 1 1 130px;">
                                            <label for="filtroNumManifiesto" class="small font-weight-bold text-muted mb-1 d-block">N° Manifiesto</label>
                                            <input type="text" class="form-control form-control-sm" id="filtroNumManifiesto" placeholder="Buscar N°...">
                                        </div>

                                        <!-- Col 1: Nombre -->
                                        <div style="flex: 1 1 150px;">
                                            <label for="filtroNombre" class="small font-weight-bold text-muted mb-1 d-block">Nombre</label>
                                            <input type="text" class="form-control form-control-sm" id="filtroNombre" placeholder="Nombre...">
                                        </div>

                                        <!-- Col 2: Cliente -->
                                        <div style="flex: 1 1 150px;">
                                            <label for="filtroCliente" class="small font-weight-bold text-muted mb-1 d-block">Cliente</label>
                                            <input type="text" class="form-control form-control-sm" id="filtroCliente" placeholder="Nombre cliente...">
                                        </div>

                                        <!-- Col 3: Tipo Operación -->
                                        <div style="flex: 1 1 130px;">
                                            <label for="filtroOperacion" class="small font-weight-bold text-muted mb-1 d-block">Tipo Operación</label>
                                            <select class="form-control form-control-sm" id="filtroOperacion">
                                                <option value="">Todas</option>
                                                <option value="Carga">Carga</option>
                                                <option value="Descarga">Descarga</option>
                                                <option value="Tránsito">Tránsito</option>
                                            </select>
                                        </div>

                                        <!-- Col 4: Producto -->
                                        <div style="flex: 1 1 120px;">
                                            <label for="filtroProducto" class="small font-weight-bold text-muted mb-1 d-block">Producto</label>
                                            <input type="text" class="form-control form-control-sm" id="filtroProducto" placeholder="Producto...">
                                        </div>

                                        <!-- Col 5: Fecha -->
                                        <div style="flex: 1 1 130px;">
                                            <label for="filtroFecha" class="small font-weight-bold text-muted mb-1 d-block">Fecha</label>
                                            <input type="date" class="form-control form-control-sm" id="filtroFecha">
                                        </div>

                                        <!-- Col 6: Hora -->
                                        <div style="flex: 1 1 100px;">
                                            <label for="filtroHora" class="small font-weight-bold text-muted mb-1 d-block">Hora</label>
                                            <input type="text" class="form-control form-control-sm" id="filtroHora" placeholder="HH:MM">
                                        </div>

                                        <!-- Col 7: Cantidad -->
                                        <div style="flex: 1 1 100px;">
                                            <label for="filtroCantidad" class="small font-weight-bold text-muted mb-1 d-block">Cantidad</label>
                                            <input type="text" class="form-control form-control-sm" id="filtroCantidad" placeholder="Cantidad...">
                                        </div>

                                        <!-- Col 8: Peso -->
                                        <div style="flex: 1 1 100px;">
                                            <label for="filtroPeso" class="small font-weight-bold text-muted mb-1 d-block">Peso</label>
                                            <input type="text" class="form-control form-control-sm" id="filtroPeso" placeholder="Peso...">
                                        </div>

                                        <!-- Col 9: Flete Neto -->
                                        <div style="flex: 1 1 110px;">
                                            <label for="filtroFlete" class="small font-weight-bold text-muted mb-1 d-block">Flete Neto</label>
                                            <input type="text" class="form-control form-control-sm" id="filtroFlete" placeholder="Flete...">
                                        </div>

                                        <!-- Col 10: Anticipo -->
                                        <div style="flex: 1 1 110px;">
                                            <label for="filtroAnticipo" class="small font-weight-bold text-muted mb-1 d-block">Anticipo</label>
                                            <input type="text" class="form-control form-control-sm" id="filtroAnticipo" placeholder="Anticipo...">
                                        </div>

                                        <!-- Col 11: Saldo a Pagar -->
                                        <div style="flex: 1 1 110px;">
                                            <label for="filtroSaldo" class="small font-weight-bold text-muted mb-1 d-block">Saldo a Pagar</label>
                                            <input type="text" class="form-control form-control-sm" id="filtroSaldo" placeholder="Saldo...">
                                        </div>

                                        <div class="ml-auto mt-2" style="flex: 0 0 auto;">
                                            <button type="button" id="btnLimpiarFiltros" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-undo mr-1"></i> Limpiar Filtros
                                            </button>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- TABLA DE MANIFIESTOS -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover text-nowrap w-100" id="tablaManifiestos">
                                    <thead class="bg-dark text-white text-center">
                                        <tr>
                                            <th>Número de Manifiesto</th> <!-- 0 -->
                                            <th>Nombre</th>               <!-- 1 -->
                                            <th>Cliente</th>              <!-- 2 -->
                                            <th>Tipo Operación</th>       <!-- 3 -->
                                            <th>Producto</th>             <!-- 4 -->
                                            <th>Fecha</th>                <!-- 5 -->
                                            <th>Hora</th>                 <!-- 6 -->
                                            <th>Cantidad</th>             <!-- 7 -->
                                            <th>Peso</th>                 <!-- 8 -->
                                            <th>Flete Neto</th>           <!-- 9 -->
                                            <th>Anticipo</th>             <!-- 10 -->
                                            <th>Saldo a Pagar</th>        <!-- 11 -->
                                            <th>ReteIca</th>              <!-- 12 -->
                                            <th>Fopat</th>                <!-- 13 -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($manifiestos as $manifiesto)
                                        <tr>
                                            <!-- Código del Manifiesto -->
                                            <td class="font-weight-bold text-success text-center">
                                                {{ $manifiesto->manifiesto_codigo ?? 'Sin N°' }}
                                            </td>
                                            
                                            <!-- Nombre del Conductor/Poseedor -->
                                            <td>{{ $manifiesto->nombre ?? '' }}</td>
                                            
                                            <!-- Cliente -->
                                            <td>{{ $manifiesto->cliente ?? '' }}</td>
                                            
                                            <!-- Tipo de Operación -->
                                            <td class="text-center">
                                                <span class="badge badge-success px-2 py-1">
                                                    {{ $manifiesto->tipoOperacion ?? 'N/A' }}
                                                </span>
                                            </td>
                                            
                                            <!-- Producto -->
                                            <td>{{ $manifiesto->producto ?? '' }}</td>
                                            
                                            <!-- Fecha (formateada sin hora) -->
                                            <td class="text-center">
                                                {{ !empty($manifiesto->fecha) ? \Carbon\Carbon::parse($manifiesto->fecha)->format('Y-m-d') : '' }}
                                            </td>
                                            
                                            <!-- Hora -->
                                            <td class="text-center">{{ $manifiesto->hora ?? '' }}</td>
                                            
                                            <!-- Cantidad -->
                                            <td class="text-right">{{ $manifiesto->cantidad ?? 0 }}</td>
                                            
                                            <!-- Peso -->
                                            <td class="text-right">{{ $manifiesto->peso ?? 0 }}</td>
                                            
                                            <!-- Flete Neto -->
                                            <td class="text-right font-weight-bold text-dark">
                                                $ {{ number_format($manifiesto->fleteNeto ?? 0, 0, ',', '.') }}
                                            </td>
                                            
                                            <!-- Anticipo -->
                                            <td class="text-right text-info">
                                                $ {{ number_format($manifiesto->anticipo ?? 0, 0, ',', '.') }}
                                            </td>
                                            
                                            <!-- Saldo a Pagar -->
                                            <td class="text-right font-weight-bold text-danger">
                                                $ {{ number_format($manifiesto->saldoPagar ?? 0, 0, ',', '.') }}
                                            </td>
                                            
                                            <!-- ReteICA -->
                                            <td class="text-right">
                                                $ {{ number_format($manifiesto->reteIca ?? 0, 0, ',', '.') }}
                                            </td>
                                            
                                            <!-- ReteFuente -->
                                            <td class="text-right">
                                                $ {{ number_format($manifiesto->reteFuente ?? 0, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="14" class="text-center text-muted py-4">
                                                No hay registros de manifiestos disponibles.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- FOOTER -->
            @include('layouts.pie')

        </div>
    </div>

    <!-- Scripts de la plantilla -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

    <script>
    $(document).ready(function() {
        var table = $('#tablaManifiestos').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            },
            responsive: true,
            pageLength: 10,
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel mr-1"></i> Excel',
                    className: 'btn btn-success btn-sm'
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="fas fa-file-csv mr-1"></i> CSV',
                    className: 'btn btn-info btn-sm'
                }
            ]
        });

        table.buttons().container().appendTo('#export-buttons-container');

        // Mapeo exacto de los filtros por índice de columna (0 a 11)
        $('#filtroNumManifiesto').on('keyup change', function() { table.column(0).search(this.value).draw(); });
        $('#filtroNombre').on('keyup change',        function() { table.column(1).search(this.value).draw(); });
        $('#filtroCliente').on('keyup change',       function() { table.column(2).search(this.value).draw(); });
        $('#filtroOperacion').on('change',           function() { table.column(3).search(this.value).draw(); });
        $('#filtroProducto').on('keyup change',      function() { table.column(4).search(this.value).draw(); });
        $('#filtroFecha').on('change',               function() { table.column(5).search(this.value).draw(); });
        $('#filtroHora').on('keyup change',          function() { table.column(6).search(this.value).draw(); });
        $('#filtroCantidad').on('keyup change',      function() { table.column(7).search(this.value).draw(); });
        $('#filtroPeso').on('keyup change',          function() { table.column(8).search(this.value).draw(); });
        $('#filtroFlete').on('keyup change',         function() { table.column(9).search(this.value).draw(); });
        $('#filtroAnticipo').on('keyup change',      function() { table.column(10).search(this.value).draw(); });
        $('#filtroSaldo').on('keyup change',         function() { table.column(11).search(this.value).draw(); });

        $('#btnLimpiarFiltros').on('click', function() {
            $('#formFiltros')[0].reset();
            table.columns().search('').draw();
        });
    });
    </script>

</body>
</html>