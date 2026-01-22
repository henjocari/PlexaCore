<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Plexa - Carrusel</title>
    <link rel="shortcut icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">

    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        /* Ajustes Visuales */
        .carousel-item { height: calc(100vh - 70px); min-height: 500px; position: relative; background: #000; }
        .carousel-item img { width: 100%; height: 100%; object-fit: cover; opacity: 0.7; }
        .carousel-caption { z-index: 10; display: flex; align-items: center; justify-content: center; bottom: 0; top: 0; }
        .display-1 { font-size: 4rem; font-weight: 800; text-shadow: 2px 2px 10px rgba(0,0,0,0.8); }
        .subtitulo-plexa { font-size: 1.5rem; letter-spacing: 3px; font-weight: 600; text-warning: #f6c23e; }

        /* Controles Internos (Esquina) */
        .admin-controls-corner { position: absolute; top: 20px; right: 20px; z-index: 999; display: flex; gap: 10px; }
        .btn-mini { width: 40px; height: 40px; border-radius: 50%; background: rgba(0,0,0,0.4); color: white; border: 1px solid rgba(255,255,255,0.3); display: flex; align-items: center; justify-content: center; transition: all 0.2s; }
        .btn-mini:hover { background: white; color: black; transform: scale(1.1); }
        .btn-mini-danger:hover { background: #e74a3b; border-color: #e74a3b; }

        /* Flechas */
        .carousel-control-prev, .carousel-control-next { width: 60px; opacity: 1; }
        .carousel-control-prev-icon, .carousel-control-next-icon { background-color: rgba(0,0,0,0.6); border-radius: 50%; width: 50px; height: 50px; background-size: 50%; border: 1px solid rgba(255,255,255,0.3); transition: all 0.3s ease; }
        .carousel-control-prev:hover .carousel-control-prev-icon, .carousel-control-next:hover .carousel-control-next-icon { background-color: #4e73df; transform: scale(1.1); border-color: #fff; box-shadow: 0 0 15px rgba(78,115,223,0.5); }

        /* BOTONES FLOTANTES - FORZADOS */
        .btn-floating-container {
            position: fixed; 
            bottom: 30px; 
            right: 30px; 
            z-index: 2147483647; /* EL NÚMERO MÁS ALTO POSIBLE EN CSS */
            display: flex; 
            flex-direction: column; 
            gap: 15px; 
        }
        .btn-floating { 
            width: 60px; height: 60px; 
            border-radius: 50%; 
            font-size: 24px; 
            display: flex; align-items: center; justify-content: center; 
            box-shadow: 0 5px 25px rgba(0,0,0,0.6); 
            color: white; 
            border: 2px solid white;
            cursor: pointer;
        }
        .btn-floating:hover { transform: scale(1.1); color: white; }
        .btn-trash { background-color: #858796; } 
        .btn-add { background-color: #4e73df; }   
    </style>
</head>

<body id="page-top">
    
    <div id="wrapper">
        @include('layouts.menu')
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                @include('layouts.cabecera')

                <div class="container-fluid p-0">
                    <div id="header-carousel" class="carousel slide" data-ride="carousel" data-interval="5000">
                        <div class="carousel-inner">
                            @forelse($slides as $slide)
                            <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                @php $imagenLimpia = trim($slide->imagen); @endphp
                                @if(Str::startsWith($imagenLimpia, ['http://', 'https://']))
                                    <img src="{{ $imagenLimpia }}" alt="Slide">
                                @else
                                    <img src="{{ asset('storage/carrusel/' . $imagenLimpia) }}" alt="Slide Local">
                                @endif

                                @if($esAdmin)
                                    <div class="admin-controls-corner">
                                        <form action="{{ route('carrusel.update_imagen', $slide->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="file" name="imagen" id="file-{{ $slide->id }}" style="display: none;" accept="image/*" onchange="this.form.submit()">
                                            <button type="button" class="btn-mini" onclick="document.getElementById('file-{{ $slide->id }}').click()"><i class="fas fa-camera"></i></button>
                                        </form>
                                        <button type="button" class="btn-mini" onclick="editarSlide({{ json_encode($slide) }})"><i class="fas fa-pen"></i></button>
                                        <form action="{{ route('carrusel.inactivar', $slide->id) }}" method="POST" class="formInactivar">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn-mini btn-mini-danger"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                @endif
                                
                                <div class="carousel-caption">
                                    <div class="container"><div class="row justify-content-center"><div class="col-lg-10 text-left">
                                        <p class="subtitulo-plexa text-warning text-uppercase animate__animated animate__fadeInDown">{{ $slide->subtitulo }}</p>
                                        <h1 class="display-1 text-white mb-4 animate__animated animate__zoomIn">{{ $slide->titulo }}</h1>
                                        <div class="animate__animated animate__fadeInUp">
                                            <a href="{{ $slide->url }}" class="btn btn-primary btn-lg px-5 py-3 font-weight-bold shadow">{{ $slide->boton }}</a>
                                        </div>
                                    </div></div></div>
                                </div>
                            </div>
                            @empty
                                <div class="carousel-item active">
                                    <div style="width:100%; height:100%; background: linear-gradient(45deg, #1a1a1a, #4e73df);"></div>
                                    <div class="carousel-caption"><h1 class="display-1 text-white">Bienvenido</h1></div>
                                </div>
                            @endforelse
                        </div>
                        <a class="carousel-control-prev" href="#header-carousel" role="button" data-slide="prev"><span class="carousel-control-prev-icon"></span></a>
                        <a class="carousel-control-next" href="#header-carousel" role="button" data-slide="next"><span class="carousel-control-next-icon"></span></a>
                    </div>
                </div>
            </div>
            @include('layouts.pie')
        </div>
    </div> 
    {{-- Quitamos el IF momentáneamente para probar. Si salen, el problema era tu rol de usuario. --}}
    {{-- @if($esAdmin) --}} 
        <div class="btn-floating-container">
            <button class="btn btn-trash btn-floating" data-toggle="modal" data-target="#modalPapelera" title="Ver Papelera">
                <i class="fas fa-recycle"></i>
            </button>
            <button class="btn btn-add btn-floating" data-toggle="modal" data-target="#modalNuevoSlide" title="Nuevo Slide">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    {{-- @endif --}}

    {{-- MODALES --}}
    <div class="modal fade" id="modalNuevoSlide" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white"><h5 class="modal-title">Gestión de Slides</h5><button class="close text-white" data-dismiss="modal">&times;</button></div>
                <form action="{{ route('carrusel.store') }}" method="POST" enctype="multipart/form-data" id="formSlide">
                    @csrf <input type="hidden" name="accion" id="accion" value="nuevo"><input type="hidden" name="id_editar" id="id_editar"><input type="hidden" name="orden" value="10">
                    <div class="modal-body">
                        <div class="form-group" id="divInputImagen"><label>Imagen</label><input type="file" name="imagen" class="form-control" accept="image/*"></div>
                        <div class="form-group"><input type="text" name="titulo" id="titulo" class="form-control" placeholder="Título"></div>
                        <div class="form-group"><input type="text" name="subtitulo" id="subtitulo" class="form-control" placeholder="Subtítulo"></div>
                        <div class="form-row">
                            <div class="col-6"><input type="text" name="boton" id="boton" class="form-control" value="Leer Más"></div>
                            <div class="col-6"><input type="text" name="url" id="url" class="form-control" value="#"></div>
                        </div>
                    </div>
                    <div class="modal-footer"><button type="submit" class="btn btn-primary">Guardar</button></div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalPapelera" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-secondary text-white"><h5 class="modal-title">Papelera</h5><button class="close text-white" data-dismiss="modal">&times;</button></div>
                <div class="modal-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($slidesInactivos as $s)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div><strong>{{ $s->titulo }}</strong></div>
                                <form action="{{ route('carrusel.activar', $s->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-success btn-sm">Restaurar</button>
                                </form>
                            </li>
                        @empty
                            <div class="p-3 text-center text-muted">Vacía.</div>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $('.formInactivar').on('submit', function(e) { e.preventDefault(); Swal.fire({ title: '¿Borrar?', icon: 'warning', showCancelButton: true, confirmButtonText: 'Sí' }).then((r) => { if (r.isConfirmed) this.submit(); }); });
        window.editarSlide = function(data) { $('#accion').val('editar'); $('#id_editar').val(data.id); $('#titulo').val(data.titulo); $('#subtitulo').val(data.subtitulo); $('#boton').val(data.boton); $('#url').val(data.url); $('#divInputImagen').hide(); $('#modalNuevoSlide').modal('show'); };
        $('#modalNuevoSlide').on('hidden.bs.modal', function () { $('#formSlide')[0].reset(); $('#accion').val('nuevo'); $('#divInputImagen').show(); });
    </script>
</body>
</html>