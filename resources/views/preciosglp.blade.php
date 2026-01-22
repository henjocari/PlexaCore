<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/favicon.png') }}">
    <title>Plexa Core - Precio GLP</title>
    
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .fade-in { animation: fadeIn ease 1.2s; }
        @keyframes fadeIn { 0% { opacity:0; } 100% { opacity:1; } }
        
        /* ESTILO TIPO "HOJA DE PAPEL" */
        .pdf-paper {
            width: 100%;
            height: 1150px; /* Altura de una hoja A4 larga para que se vea completa */
            border: none;
            display: block;
        }
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
                        
                        {{-- 1. VISOR TIPO DOCUMENTO (CENTRADO Y ACOTADO) --}}
                        @if($ultimoPrecio)
                            {{-- AQUÍ ESTÁ EL TRUCO: max-width: 850px hace que no se vea gigante --}}
                            <div class="card shadow mb-4 mx-auto" style="max-width: 850px; border: 1px solid #d1d3e2;">
                                <div class="card-header py-2 bg-white border-bottom-0">
                                    <small class="text-muted font-weight-bold text-uppercase">
                                        <i class="fas fa-file-pdf mr-1"></i> Visualización Oficial
                                    </small>
                                </div>
                                <div class="card-body p-0">
                                    {{-- 
                                        Parámetros PDF:
                                        #view=FitH  -> Ajustar al ancho del contenedor (850px) = Zoom perfecto.
                                        #toolbar=0  -> Sin barras negras.
                                    --}}
                                    <iframe 
                                        src="{{ asset('archivos_glp/' . $ultimoPrecio->archivo_pdf) }}?v={{ time() }}#toolbar=0&navpanes=0&scrollbar=0&view=FitH" 
                                        class="pdf-paper"
                                        title="Visor PDF">
                                    </iframe>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning mx-auto mb-5" style="max-width: 800px;">
                                <i class="fas fa-exclamation-triangle mr-2"></i> No hay documento vigente cargado.
                            </div>
                        @endif

                        {{-- 2. FORMULARIO DE CARGA --}}
                        @if($puedeEditar)
                            <div class="card shadow-sm border-left-primary mx-auto mb-5 text-left" style="max-width: 800px;">
                                <div class="card-body">
                                    <h5 class="font-weight-bold text-primary mb-3"><i class="fas fa-cloud-upload-alt mr-2"></i>Cargar Nuevo Documento</h5>
                                    
                                    <form action="{{ route('precio.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="input-group">
                                            <input type="file" name="archivo_pdf" class="form-control" accept=".pdf" required>
                                            <button type="submit" class="btn btn-primary font-weight-bold">
                                                SUBIR
                                            </button>
                                        </div>
                                    </form>
                                    <small class="text-muted mt-2 d-block">* El archivo será visible inmediatamente.</small>
                                </div>
                            </div>
                        @endif

                        {{-- 3. TABLA HISTÓRICA --}}
                        <div class="row justify-content-center">
                            <div class="col-lg-10"> {{-- Ajustado a col-lg-10 para que no sea tan ancha --}}
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3 bg-light">
                                        <h6 class="m-0 font-weight-bold text-primary">Historial de Cargas</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover text-center mb-0">
                                                <thead class="bg-gray-100">
                                                    <tr>
                                                        <th>ID</th>
                                                        <th class="text-left pl-3">Archivo</th>
                                                        <th>Responsable</th> 
                                                        <th>Fecha</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($historico as $archivo)
                                                        <tr>
                                                            <td>{{ $archivo->id }}</td>
                                                            <td class="text-left pl-3 small">{{ $archivo->archivo_pdf }}</td>
                                                            <td class="text-info font-weight-bold">
                                                                {{ $archivo->nombre_user ?? 'Sistema' }} {{ $archivo->apellido_user ?? '' }}
                                                            </td>
                                                            <td>{{ \Carbon\Carbon::parse($archivo->fecha_inicio)->format('d/m/Y') }}</td>
                                                            <td>
                                                                <a href="{{ asset('archivos_glp/' . $archivo->archivo_pdf) }}" target="_blank" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                                                
                                                                @if(in_array((int)auth()->user()->rol, [1, 2]))
                                                                    <form action="{{ route('precio.inactivar', $archivo->id) }}" method="POST" class="formInactivar d-inline ml-1">
                                                                        @csrf @method('PATCH')
                                                                        <button type="submit" class="btn btn-sm btn-secondary"><i class="fas fa-trash"></i></button>
                                                                    </form>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr><td colspan="5" class="py-3 text-muted">No hay historial disponible.</td></tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="p-3 d-flex justify-content-center">
                                            {{ $historico->links() }}
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

    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
    <script>
        $('.formInactivar').on('submit', function(e) {
            e.preventDefault();
            Swal.fire({ 
                title: '¿Eliminar?', 
                text: "El documento dejará de ser visible.",
                icon: 'warning', 
                showCancelButton: true, 
                confirmButtonColor: '#e74a3b',
                confirmButtonText: 'Sí, eliminar' 
            }).then((r) => { if (r.isConfirmed) this.submit(); });
        });
    </script>
</body>
</html>