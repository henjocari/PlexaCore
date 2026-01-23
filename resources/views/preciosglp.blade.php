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
        
        /* ESTILO LIMPIO "HOJA DE PAPEL" */
        .pdf-paper {
            width: 100%;
            height: 1150px; /* Altura perfecta para A4 */
            border: none;
            display: block;
            background-color: #fff; /* Fondo blanco detrás */
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
                        
                        {{-- 1. VISOR TIPO DOCUMENTO (MODO LIMPIO) --}}
                        @if($ultimoPrecio)
                            <div class="card shadow-lg mb-4 mx-auto" style="max-width: 900px; border: none;">
                                <div class="card-body p-0">
                                    {{-- 
                                       CAMBIO CLAVE: Usamos <embed> en lugar de <iframe>.
                                       Esto, junto con view=FitH, elimina las franjas negras laterales.
                                    --}}
                                    <embed 
                                        src="{{ asset('archivos_glp/' . $ultimoPrecio->archivo_pdf) }}?v={{ time() }}#toolbar=0&navpanes=0&scrollbar=0&view=FitH" 
                                        type="application/pdf"
                                        class="pdf-paper">
                                </div>
                                <div class="card-footer bg-white border-top-0 text-muted small">
                                    Visualización oficial del documento cargado.
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
                            <div class="col-lg-10">
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
                                                            {{-- TRUCO VISUAL: Quitamos guiones bajos SOLO en la tabla para que se vea bonito --}}
                                                            <td class="text-left pl-3 small">{{ str_replace('_', ' ', $archivo->archivo_pdf) }}</td>
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
                title: '¿Inactivar?', 
                text: "El documento dejará de ser visible.",
                icon: 'warning', 
                showCancelButton: true, 
                confirmButtonColor: '#1dc920',
                confirmButtonText: 'Sí, inactivar' 
            }).then((r) => { if (r.isConfirmed) this.submit(); });
        });
    </script>
</body>
</html>