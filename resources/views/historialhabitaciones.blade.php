<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/favicon.png') }}">
    <title>Historial de Habitaciones</title>

    <!-- Fonts y estilos -->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,900" rel="stylesheet">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

    <style>
        .table-hover tbody tr:hover {
            background-color: #f8f9fc;
            cursor: pointer;
        }

        .badge-ocupada {
            background-color: #e74a3b;
        }

        .badge-disponible {
            background-color: #1cc88a;
        }

        .btn-export {
            background-color: #1cc88a;
            color: white;
        }

        .btn-export:hover {
            background-color: #17a673;
            color: white;
        }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        @include('layouts.menu')

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                @include('layouts.cabecera')

                <div class="container-fluid">
                    <!-- Encabezado -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">
                            <i class="fas fa-history"></i> Historial de Habitaciones
                        </h1>
                        <div class="btn-group" role="group">
                            <a href="{{ route('historial.export.csv') }}" class="btn btn-success shadow-sm">
                                <i class="fas fa-file-csv fa-sm"></i> Exportar CSV
                            </a>
                            <a href="{{ route('historial.export.excel') }}" class="btn btn-export shadow-sm">
                                <i class="fas fa-file-excel fa-sm"></i> Exportar Excel
                            </a>
                        </div>
                    </div>
                    <!-- Card principal -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h6 class="m-0 font-weight-bold text-primary">Registro de Asignaciones</h6>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text" id="busqueda" class="form-control" 
                                               placeholder="Buscar por habitación, conductor, estado...">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            @if($historial->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="tablaHistorial" width="100%" cellspacing="0">
                                        <thead class="thead-dark">
                                            <tr class="text-center">
                                                <th>ID</th>
                                                <th>Habitación</th>
                                                <th>Estado</th>
                                                <th>Conductor</th>
                                                <th>Usuario</th>
                                                <th>Fecha y Hora</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($historial as $registro)
                                                <tr class="text-center">
                                                    <td>{{ $registro->id }}</td>
                                                    <td>
                                                        <span class="badge badge-info badge-pill" style="font-size: 1rem;">
                                                            {{ $registro->habitacion }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge {{ $registro->estado == 'Ocupada' ? 'badge-ocupada' : 'badge-disponible' }} text-white">
                                                            {{ $registro->estado }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @php
                                                            $conductor = \App\Models\Conductor::where('cedula', $registro->conductor)->first();
                                                        @endphp
                                                        @if($conductor)
                                                        <strong>{{ $conductor->nombre }} {{ $conductor->apellido }}</strong>
                                                    @else
                                                        <span class="text-muted">{{ $registro->conductor ?? 'N/A' }}</span>
                                                    @endif
                                                    </td>
                                                    <td>{{ $registro->usuario }}</td>
                                                    <td>
                                                        <i class="far fa-calendar-alt text-primary"></i>
                                                        {{ \Carbon\Carbon::parse($registro->fecha)->format('d/m/Y') }}
                                                        <br>
                                                        <i class="far fa-clock text-success"></i>
                                                        {{ \Carbon\Carbon::parse($registro->fecha)->format('h:i A') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Paginación -->
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div>
                                        Mostrando {{ $historial->firstItem() }} a {{ $historial->lastItem() }} de {{ $historial->total() }} registros
                                    </div>
                                    <div>
                                        {{ $historial->links() }}
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No se encontraron registros</h5>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Estadísticas rápidas -->
                    <div class="row">
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Registros
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ \App\Models\HistorialHabitacion::count() }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Asignaciones Hoy
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ \App\Models\HistorialHabitacion::whereDate('fecha', today())->count() }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Habitaciones Únicas
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ \App\Models\HistorialHabitacion::distinct('habitacion')->count('habitacion') }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-bed fa-2x text-gray-300"></i>
                                        </div>
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

    <a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>

    <!-- JS -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const input = document.getElementById("busqueda");
            input.addEventListener("keyup", function () {
                const value = this.value.toLowerCase();
                document.querySelectorAll("#tablaHistorial tbody tr").forEach(row => {
                    row.style.display = row.textContent.toLowerCase().includes(value) ? "" : "none";
                });
            });
        });
    </script>
</body>

</html>