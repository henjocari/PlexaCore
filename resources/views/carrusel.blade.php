<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Plexa Core - Carrusel</title>
    <link rel="shortcut icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">

    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Ajuste para que las imágenes del carrusel siempre llenen el espacio sin deformarse */
        .carousel-item img {
            height: 500px; /* Altura fija para uniformidad */
            object-fit: cover; /* Recorta la imagen si sobra, no la estira */
            filter: brightness(0.7); /* Un poco oscura para que se lea el texto blanco */
        }
        /* Ajustes de texto del template original */
        .carousel-caption {
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.3); /* Fondo oscuro suave */
        }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        @include('layouts.menu')

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                @include('layouts.cabecera')

                <div class="container-fluid p-0">
                    
                    {{-- ======================================================= --}}
                    {{--  1. EL CARRUSEL (VISUALIZACIÓN PÚBLICA)                 --}}
                    {{-- ======================================================= --}}
                    
                    <div id="header-carousel" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            @forelse($slides as $slide)
                                {{-- La clase 'active' solo va en el primer elemento --}}
                                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                    <img class="w-100" src="{{ asset('storage/carrusel/' . $slide->imagen) }}" alt="Image">
                                    <div class="carousel-caption">
                                        <div class="container">
                                            <div class="row justify-content-center">
                                                <div class="col-lg-10 text-center">
                                                    {{-- Subtítulo --}}
                                                    @if($slide->subtitulo)
                                                        <p class="fs-5 fw-medium text-warning text-uppercase animated slideInRight mb-3 font-weight-bold">
                                                            {{ $slide->subtitulo }}
                                                        </p>
                                                    @endif
                                                    
                                                    {{-- Título --}}
                                                    @if($slide->titulo)
                                                        <h1 class="display-3 text-white mb-5 animated slideInRight font-weight-bold">
                                                            {{ $slide->titulo }}
                                                        </h1>
                                                    @endif
                                                    
                                                    {{-- Botón --}}
                                                    @if($slide->boton)
                                                        <a href="{{ $slide->url }}" class="btn btn-primary py-3 px-5 animated slideInRight shadow-lg">
                                                            {{ $slide->boton }}
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                {{-- Slide por defecto si no hay nada en la BD --}}
                                <div class="carousel-item active">
                                    <img class="w-100" src="https://via.placeholder.com/1920x600?text=Bienvenido+a+Plexa" alt="Default">
                                    <div class="carousel-caption">
                                        <h1 class="display-3 text-white">Bienvenido a Plexa</h1>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                        
                        <a class="carousel-control-prev" href="#header-carousel" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#header-carousel" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>


                    {{-- ======================================================= --}}
                    {{--  2. PANEL DE ADMINISTRACIÓN (SOLO ADMINS)               --}}
                    {{-- ======================================================= --}}
                    
                    @if($esAdmin)
                    <div class="container mt-5 mb-5">
                        <div class="row">
                            {{-- Formulario de Carga --}}
                            <div class="col-lg-4">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3 bg-primary text-white">
                                        <h6 class="m-0 font-weight-bold"><i class="fas fa-edit"></i> Gestión de Slides</h6>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('carrusel.store') }}" method="POST" enctype="multipart/form-data" id="formCarrusel">
                                            @csrf
                                            <input type="hidden" name="accion" id="accion" value="nuevo">
                                            <input type="hidden" name="id_editar" id="id_editar">

                                            <div class="form-group">
                                                <label>Imagen (Sugerido: 1920x600 px)</label>
                                                <input type="file" name="imagen" class="form-control-file border p-1" accept="image/*" id="inputImagen">
                                                <small class="text-muted" id="avisoImagen">* Obligatorio para nuevos</small>
                                            </div>

                                            <div class="form-group">
                                                <label>Título</label>
                                                <input type="text" name="titulo" id="titulo" class="form-control" placeholder="Ej: Energía que avanza">
                                            </div>

                                            <div class="form-group">
                                                <label>Subtítulo</label>
                                                <input type="text" name="subtitulo" id="subtitulo" class="form-control" placeholder="Ej: Soluciones GLP">
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label>Texto Botón</label>
                                                    <input type="text" name="boton" id="boton" class="form-control" value="Leer Más">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>Orden</label>
                                                    <input type="number" name="orden" id="orden" class="form-control" value="1">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label>URL (Enlace)</label>
                                                <input type="text" name="url" id="url" class="form-control" value="#">
                                            </div>

                                            <button type="submit" class="btn btn-success btn-block font-weight-bold" id="btnGuardar">
                                                <i class="fas fa-save mr-1"></i> GUARDAR SLIDE
                                            </button>
                                            
                                            <button type="button" class="btn btn-secondary btn-block" id="btnCancelar" style="display: none;" onclick="limpiarFormulario()">
                                                CANCELAR EDICIÓN
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- Tabla de Slides --}}
                            <div class="col-lg-8">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Slides Activos</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm text-center" width="100%" cellspacing="0">
                                                <thead>
                                                    <tr class="bg-gray-100">
                                                        <th>Img</th>
                                                        <th>Info</th>
                                                        <th>Orden</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($slides as $slide)
                                                        <tr>
                                                            <td style="width: 120px;">
                                                                <img src="{{ asset('storage/carrusel/' . $slide->imagen) }}" width="100" class="rounded shadow-sm">
                                                            </td>
                                                            <td class="text-left small">
                                                                <strong>T:</strong> {{ $slide->titulo }}<br>
                                                                <strong>S:</strong> {{ $slide->subtitulo }}<br>
                                                                <strong>URL:</strong> {{ $slide->url }}
                                                            </td>
                                                            <td class="align-middle">{{ $slide->orden }}</td>
                                                            <td class="align-middle">
                                                                {{-- Botón Editar (Carga datos al form) --}}
                                                                <button class="btn btn-warning btn-sm btn-circle" onclick="editarSlide({{ json_encode($slide) }})" title="Editar">
                                                                    <i class="fas fa-pen"></i>
                                                                </button>

                                                                {{-- Botón Inactivar --}}
                                                                <form action="{{ route('carrusel.inactivar', $slide->id) }}" method="POST" class="d-inline formInactivar">
                                                                    @csrf @method('PATCH')
                                                                    <button type="submit" class="btn btn-danger btn-sm btn-circle" title="Eliminar">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr><td colspan="4">No hay slides activos.</td></tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                </div> </div> @include('layouts.pie')
        </div>
    </div>

    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    <script>
        // Alerta de confirmación para eliminar
        $('.formInactivar').on('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Eliminar slide?',
                text: "Se ocultará del carrusel principal.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74a3b',
                cancelButtonColor: '#858796',
                confirmButtonText: 'Sí, eliminar'
            }).then((result) => { if (result.isConfirmed) this.submit(); });
        });

        // Función para cargar datos en el formulario al dar click en Editar
        function editarSlide(data) {
            // Llenar inputs
            $('#id_editar').val(data.id);
            $('#titulo').val(data.titulo);
            $('#subtitulo').val(data.subtitulo);
            $('#boton').val(data.boton);
            $('#url').val(data.url);
            $('#orden').val(data.orden);

            // Cambiar estado del formulario
            $('#accion').val('editar');
            $('#btnGuardar').removeClass('btn-success').addClass('btn-warning').html('<i class="fas fa-sync-alt mr-1"></i> ACTUALIZAR');
            $('#btnCancelar').show();
            $('#avisoImagen').text('* Opcional (subir solo si quieres cambiarla)');
            
            // Scroll suave hacia el formulario
            $('html, body').animate({ scrollTop: $("#formCarrusel").offset().top - 100 }, 500);
        }

        // Función para limpiar el formulario
        function limpiarFormulario() {
            $('#formCarrusel')[0].reset();
            $('#id_editar').val('');
            $('#accion').val('nuevo');
            $('#btnGuardar').removeClass('btn-warning').addClass('btn-success').html('<i class="fas fa-save mr-1"></i> GUARDAR SLIDE');
            $('#btnCancelar').hide();
            $('#avisoImagen').text('* Obligatorio para nuevos');
        }
    </script>
</body>
</html>