<!DOCTYPE html>
<html lang="es">
<head>
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        #pdf-viewer-container { background: #fff; border-radius: 8px; overflow: hidden; max-width: 850px; min-height: 400px; border: 1px solid #e3e6f0; }
        #pdf-canvas { width: 100%; height: auto; display: block; }
        .fade-in { animation: fadeIn ease 1.0s; }
        @keyframes fadeIn { 0% { opacity:0; } 100% { opacity:1; } }
    </style>
</head>
<body id="page-top">
    <div id="wrapper">
        @include('layouts.menu')
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                @include('layouts.cabecera')

                <div class="container-fluid mt-4">
                    <h1 class="h3 mb-4 text-gray-800 text-center font-weight-bold">Precio GLP Vigente</h1>

                    <div class="text-center fade-in">
                        @if($ultimoPrecio)
                            <div id="pdf-viewer-container" class="shadow-sm mx-auto mb-4">
                                <canvas id="pdf-canvas" data-url="{{ asset('storage/precios/' . $ultimoPrecio->archivo_pdf) }}"></canvas>
                            </div>
                            <a href="{{ asset('storage/precios/' . $ultimoPrecio->archivo_pdf) }}" download class="btn btn-success btn-icon-split shadow-sm mb-4">
                                <span class="icon text-white-50"><i class="fas fa-file-download"></i></span>
                                <span class="text">Descargar Documento Actual</span>
                            </a>
                        @endif

                        <div class="card shadow-sm border-left-primary mx-auto mb-5" style="max-width: 850px;">
                            <div class="card-body text-left">
                                @if($puedeEditar)
                                    <h5 class="font-weight-bold text-primary"><i class="fas fa-upload mr-2"></i>Actualizar Precio GLP</h5>
                                    <form action="{{ route('precio.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="input-group">
                                            <input type="file" name="archivo_pdf" class="form-control" accept=".pdf" required>
                                            <button type="submit" class="btn btn-primary">Subir PDF</button>
                                        </div>
                                    </form>
                                @else
                                    <div class="alert alert-warning mb-0"><i class="fas fa-lock mr-2"></i>Edición Bloqueada (24h).</div>
                                @endif
                            </div>
                        </div>

                        <div class="row justify-content-center mt-5">
                            <div class="col-lg-8"> <div class="card shadow mb-4">
                                    <div class="card-header py-3 bg-gray-100">
                                        <h6 class="m-0 font-weight-bold text-primary text-left"><i class="fas fa-history mr-2"></i>Historial de Precios</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered table-hover text-center">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th>Archivo</th>
                                                        <th>Publicación</th>
                                                        <th>Descargar</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($historico as $archivo)
                                                        <tr>
                                                            <td class="text-left">{{ $archivo->archivo_pdf }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($archivo->fecha_inicio)->format('d/m/Y') }}</td>
                                                            <td>
                                                                <a href="{{ asset('storage/precios/' . $archivo->archivo_pdf) }}" download class="btn btn-sm btn-outline-primary">
                                                                    <i class="fas fa-download"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr><td colspan="3" class="text-muted">No hay histórico disponible.</td></tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> </div>
            </div>
            @include('layouts.pie')
        </div>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
        // Tu lógica de PDF.js aquí...
    </script>
</body>
</html>