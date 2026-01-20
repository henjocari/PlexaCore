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
        /* ESTILO LIMPIO PARA EL VISOR */
        #pdf-viewer-container { 
            background-color: transparent; 
            width: 100%;
            max-width: 800px; /* Ancho controlado (tipo hoja A4) */
            margin: 0 auto;   /* Centrado */
            height: auto;     /* Altura automática */
            overflow: hidden; /* Sin barras de scroll */
            box-shadow: 0 4px 15px rgba(0,0,0,0.1); /* Sombra suave */
            border-radius: 4px;
            margin-bottom: 2rem;
        }

        #pdf-canvas { 
            width: 100%;
            height: auto;
            display: block; 
        }

        .fade-in { animation: fadeIn ease 1.2s; }
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
                        
                        {{-- 1. VISOR DE PDF (Sin botón de descarga) --}}
                        @if($ultimoPrecio)
                            <div id="pdf-viewer-container">
                                {{-- Usamos el nombre directo de la BD --}}
                                <canvas id="pdf-canvas" data-url="{{ asset('storage/precios/' . $ultimoPrecio->archivo_pdf) }}"></canvas>
                            </div>
                        @else
                            <div class="alert alert-info mx-auto mb-5" style="max-width: 800px;">
                                No hay documento vigente cargado.
                            </div>
                        @endif

                        {{-- 2. PANEL DE CARGA (Solo Admin o <24h) --}}
                        @if($puedeEditar)
                            <div class="card shadow-sm border-left-primary mx-auto mb-5 text-left" style="max-width: 800px;">
                                <div class="card-body">
                                    <h5 class="font-weight-bold text-primary mb-3"><i class="fas fa-file-upload mr-2"></i>Cargar Documento</h5>
                                    <form action="{{ route('precio.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="input-group">
                                            <input type="file" name="archivo_pdf" class="form-control" accept=".pdf" required>
                                            <button type="submit" name="accion" value="nuevo" class="btn btn-primary font-weight-bold">
                                                <i class="fas fa-upload mr-1"></i> SUBIR
                                            </button>
                                            @if($ultimoPrecio)
                                                <button type="submit" name="accion" value="editar" class="btn btn-warning font-weight-bold text-white ml-1">
                                                    <i class="fas fa-sync-alt mr-1"></i> REEMPLAZAR ACTUAL
                                                </button>
                                            @endif
                                        </div>
                                    </form>
                                    <small class="text-muted mt-2 d-block">* Se guardará con el nombre original del archivo.</small>
                                </div>
                            </div>
                        @endif

                        {{-- 3. TABLA HISTÓRICA --}}
                        <div class="row justify-content-center">
                            <div class="col-lg-10">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3 bg-light">
                                        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-history mr-2"></i>Historial de Cargas</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover text-center mb-0">
                                                <thead class="bg-gray-100">
                                                    <tr>
                                                        <th>ID</th>
                                                        <th class="text-left pl-3">Nombre del Archivo</th>
                                                        <th>Responsable</th> 
                                                        <th>Fecha</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($historico as $archivo)
                                                        <tr>
                                                            <td>{{ $archivo->id }}</td>
                                                            <td class="text-left pl-3">{{ $archivo->archivo_pdf }}</td>
                                                            <td class="text-info font-weight-bold">{{ $archivo->nombre_usuario ?? 'Sistema' }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($archivo->fecha_inicio)->format('d/m/Y') }}</td>
                                                            <td>
                                                                <div class="btn-group shadow-sm">
                                                                    {{-- Botón VER --}}
                                                                    <a href="{{ asset('storage/precios/' . $archivo->archivo_pdf) }}" target="_blank" class="btn btn-sm btn-info" title="Ver">
                                                                        <i class="fas fa-eye"></i>
                                                                    </a>
                                                                    {{-- Botón INACTIVAR (Admin) --}}
                                                                    @if(in_array((int)auth()->user()->rol, [1, 2]))
                                                                        <form action="{{ route('precio.inactivar', $archivo->id) }}" method="POST" class="formInactivar d-inline ml-1">
                                                                            @csrf @method('PATCH')
                                                                            <button type="submit" class="btn btn-sm btn-secondary" title="Ocultar">
                                                                                <i class="fas fa-eye-slash"></i>
                                                                            </button>
                                                                        </form>
                                                                    @endif
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr><td colspan="5" class="py-3 text-muted">No hay historial disponible.</td></tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        {{-- 4. PAGINACIÓN (Aquí aparecen los números) --}}
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
    <script src="{{ asset('js/pdfjs/pdf.min.js') }}"></script>

    <script>
        // Alerta Inactivar
        $('.formInactivar').on('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Ocultar documento?',
                text: "Desaparecerá de la lista pública.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#858796',
                cancelButtonColor: '#4e73df',
                confirmButtonText: 'Sí, ocultar'
            }).then((r) => { if (r.isConfirmed) this.submit(); });
        });

        // Visor PDF
        document.addEventListener("DOMContentLoaded", function() {
            const canvas = document.getElementById('pdf-canvas');
            if (!canvas) return;
            const url = canvas.getAttribute('data-url');
            if (url) {
                const pdfjsLib = window['pdfjs-dist/build/pdf'];
                pdfjsLib.GlobalWorkerOptions.workerSrc = "{{ asset('js/pdfjs/pdf.worker.min.js') }}";
                
                pdfjsLib.getDocument(url).promise.then(pdf => {
                    pdf.getPage(1).then(page => {
                        const context = canvas.getContext('2d');
                        // Escala 1.5 es buena calidad/tamaño para el ancho de 800px
                        const viewport = page.getViewport({ scale: 1.5 });
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;
                        page.render({ canvasContext: context, viewport: viewport });
                    });
                }).catch(e => { console.error(e); });
            }
        });
    </script>
</body>
</html>