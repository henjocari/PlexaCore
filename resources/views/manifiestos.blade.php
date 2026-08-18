<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/favicon.png') }}">
    <title>Gestión de Manifiestos</title>

    <!-- Fuentes y estilos SB Admin -->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,900" rel="stylesheet">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

    <!-- Estilos de DataTables -->
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

    <!-- Wrapper Principal -->
    <div id="wrapper">

        <!-- MENÚ LATERAL -->
        @include('layouts.menu')

        <!-- Contenedor de Contenido -->
        <div id="content-wrapper" class="d-flex flex-column">
            
            <div id="content">
                <!-- NAVBAR / CABECERA -->
                @include('layouts.cabecera')

                <!-- CONTENIDO DE LA PÁGINA -->
                <div class="container-fluid py-3">
                    
                    <div class="d-sm-flex align-items-center justify-content-between mb-3">
                        <h2 class="h4 mb-0 text-gray-800 font-weight-bold">Gestión de Manifiestos</h2>
                        <div id="export-buttons-container" class="d-flex align-items-center"></div>
                    </div>

                    <!-- PANEL DE FILTROS HORIZONTALES -->
                    <div class="card shadow-sm mb-4 border-left-success">
                        <div class="card-header py-2 bg-white d-flex align-items-center justify-content-between">
                            <span class="font-weight-bold text-success small">
                                <i class="fas fa-filter mr-1"></i> Filtros de Búsqueda
                            </span>
                            <button class="btn btn-sm btn-link text-muted p-0" type="button" data-toggle="collapse" data-target="#collapseFiltros">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </div>
                        <div class="collapse show" id="collapseFiltros">
                            <div class="card-body bg-light p-3">
                                <form id="formFiltros">
                                    <!-- Flexbox horizontal fluido -->
                                    <div class="d-flex flex-wrap align-items-end" style="gap: 10px;">
                                        
                                        <div style="flex: 1 1 130px;">
                                            <label for="filtroNumManifiesto" class="small font-weight-bold text-muted mb-1 d-block">N° Manifiesto</label>
                                            <input type="text" class="form-control form-control-sm" id="filtroNumManifiesto" placeholder="">
                                        </div>

                                        <div style="flex: 1 1 130px;">
                                            <label for="filtroFecha" class="small font-weight-bold text-muted mb-1 d-block">Fecha</label>
                                            <input type="date" class="form-control form-control-sm" id="filtroFecha">
                                        </div>

                                        <div style="flex: 1 1 100px;">
                                            <label for="filtroHora" class="small font-weight-bold text-muted mb-1 d-block">Hora</label>
                                            <input type="text" class="form-control form-control-sm" id="filtroHora" placeholder="">
                                        </div>

                                        <div style="flex: 1 1 150px;">
                                            <label for="filtroCliente" class="small font-weight-bold text-muted mb-1 d-block">Cliente</label>
                                            <input type="text" class="form-control form-control-sm" id="filtroCliente" placeholder="">
                                        </div>

                                        <div style="flex: 1 1 130px;">
                                            <label for="filtroOperacion" class="small font-weight-bold text-muted mb-1 d-block">Tipo Operación</label>
                                            <select class="form-control form-control-sm" id="filtroOperacion">
                                                <option value="">Todas</option>
                                                <option value="Carga">Carga</option>
                                                <option value="Descarga">Descarga</option>
                                                <option value="Tránsito">Tránsito</option>
                                            </select>
                                        </div>

                                        <div style="flex: 1 1 120px;">
                                            <label for="filtroProducto" class="small font-weight-bold text-muted mb-1 d-block">Producto</label>
                                            <input type="text" class="form-control form-control-sm" id="filtroProducto" placeholder="Producto...">
                                        </div>

                                        <div style="flex: 1 1 100px;">
                                            <label for="filtroCantidad" class="small font-weight-bold text-muted mb-1 d-block">Cantidad</label>
                                            <input type="text" class="form-control form-control-sm" id="filtroCantidad" placeholder="Cantidad...">
                                        </div>

                                        <div style="flex: 1 1 100px;">
                                            <label for="filtroPeso" class="small font-weight-bold text-muted mb-1 d-block">Peso</label>
                                            <input type="text" class="form-control form-control-sm" id="filtroPeso" placeholder="Peso...">
                                        </div>

                                        <div style="flex: 1 1 110px;">
                                            <label for="filtroFlete" class="small font-weight-bold text-muted mb-1 d-block">Flete Neto</label>
                                            <input type="text" class="form-control form-control-sm" id="filtroFlete" placeholder="Flete...">
                                        </div>

                                        <div style="flex: 1 1 110px;">
                                            <label for="filtroAnticipo" class="small font-weight-bold text-muted mb-1 d-block">Anticipo</label>
                                            <input type="text" class="form-control form-control-sm" id="filtroAnticipo" placeholder="Anticipo...">
                                        </div>

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

                    <!-- TABLA DINÁMICA DE MANIFIESTOS -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover text-nowrap w-100" id="tablaManifiestos">
                                    <thead class="bg-dark text-white text-center">
                                        <tr>
                                            <th>Número de Manifiesto</th>
                                            <th>Fecha</th>
                                            <th>Hora</th>
                                            <th>Cliente</th>
                                            <th>Tipo Operación</th>
                                            <th>Producto</th>
                                            <th>Cantidad</th>
                                            <th>Peso</th>
                                            <th>Flete Neto</th>
                                            <th>Anticipo</th>
                                            <th>Saldo a Pagar</th>
                                            <th>ReteIca</th>
                                            <th>Fopat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="font-weight-bold text-success text-center">MNF-2026-001</td>
                                            <td class="text-center">2026-08-18</td>
                                            <td class="text-center">08:30 AM</td>
                                            <td>PLEXA SAS ESP</td>
                                            <td class="text-center"><span class="badge badge-success px-2 py-1">Carga</span></td>
                                            <td>GLP Propano</td>
                                            <td class="text-right">500 Gal</td>
                                            <td class="text-right">12,500 kg</td>
                                            <td class="text-right font-weight-bold text-dark">$ 2,500,000</td>
                                            <td class="text-right text-info">$ 1,000,000</td>
                                            <td class="text-right font-weight-bold text-danger">$ 1,500,000</td>
                                            <td class="text-right">$ 24,000</td>
                                            <td class="text-right">$ 15,000</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- FOOTER / PIE DE PÁGINA -->
            @include('layouts.pie')

        </div>
    </div>

    <!-- Scripts requeridos -->
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

        // Mapeo de búsqueda por columnas
        $('#filtroNumManifiesto').on('keyup change', function() { table.column(0).search(this.value).draw(); });
        $('#filtroFecha').on('change', function() { table.column(1).search(this.value).draw(); });
        $('#filtroHora').on('keyup change', function() { table.column(2).search(this.value).draw(); });
        $('#filtroCliente').on('keyup change', function() { table.column(3).search(this.value).draw(); });
        $('#filtroOperacion').on('change', function() { table.column(4).search(this.value).draw(); });
        $('#filtroProducto').on('keyup change', function() { table.column(5).search(this.value).draw(); });
        $('#filtroCantidad').on('keyup change', function() { table.column(6).search(this.value).draw(); });
        $('#filtroPeso').on('keyup change', function() { table.column(7).search(this.value).draw(); });
        $('#filtroFlete').on('keyup change', function() { table.column(8).search(this.value).draw(); });
        $('#filtroAnticipo').on('keyup change', function() { table.column(9).search(this.value).draw(); });
        $('#filtroSaldo').on('keyup change', function() { table.column(10).search(this.value).draw(); });

        $('#btnLimpiarFiltros').on('click', function() {
            $('#formFiltros')[0].reset();
            table.columns().search('').draw();
        });
    });
    </script>

</body>
</html>