<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Gestión de Viajes | Admin</title>
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/favicon.png') }}">
    <style>
        .fondo-plexa{background:#fff url("{{ asset('img/Logo_plexa.svg') }}") center/80% no-repeat; min-height:100vh; padding:2rem;}
        .bg-gradient-filter { background: linear-gradient(180deg, #f8f9fc 0%, #e3e6f0 100%); border-left: 5px solid #4e73df; }
        .table-hover tbody tr:hover { background-color: rgba(78, 115, 223, 0.05); }
    </style>
</head>
<body id="page-top">
<div id="wrapper">
    
    @include('layouts.menu')
    
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            
            @include('layouts.cabecera')
            
            <div class="container-fluid fondo-plexa">
                
                <div class="d-sm-flex align-items-center justify-content-between mb-4 px-3 py-2 bg-white shadow-sm rounded border-left-danger">
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-plane-departure mr-2 text-danger"></i> Gestión de Solicitudes
                    </h1>
                    <span class="badge badge-danger">Administrador</span>
                </div>

                @if(session('success')) 
                    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                        <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div> 
                @endif
                
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                        <strong><i class="fas fa-exclamation-triangle"></i> No se pudo procesar:</strong>
                        <ul class="mb-0 mt-1 pl-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                @endif

                <div class="card shadow mb-4 border-bottom-primary">
                    <div class="card-header py-3 bg-gradient-filter">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-filter mr-1"></i> Filtros de Búsqueda</h6>
                    </div>
                    <div class="card-body bg-light">
                        <form action="{{ route('tickets.gestion') }}" method="GET">
                            <div class="row align-items-end">
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <label class="font-weight-bold small text-uppercase text-gray-600">Empleado o Ruta</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text bg-white border-right-0"><i class="fas fa-search text-gray-400"></i></span></div>
                                        <input type="text" name="busqueda" class="form-control border-left-0" placeholder="Nombre, Apellido, Destino..." value="{{ request('busqueda') }}">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 mb-3">
                                    <label class="font-weight-bold small text-uppercase text-gray-600">Estado</label>
                                    <select name="estado" class="form-control">
                                        <option value="">Todos</option>
                                        <option value="2" {{ request('estado') == '2' ? 'selected' : '' }}>🟡 Pendiente</option>
                                        <option value="1" {{ request('estado') == '1' ? 'selected' : '' }}>🟢 Aprobado</option>
                                        <option value="0" {{ request('estado') == '0' ? 'selected' : '' }}>🔴 Rechazado</option>
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-6 mb-3">
                                    <label class="font-weight-bold small text-uppercase text-gray-600">Desde</label>
                                    <input type="date" name="fecha_inicio" class="form-control" value="{{ request('fecha_inicio') }}">
                                </div>
                                <div class="col-lg-2 col-md-6 mb-3">
                                    <label class="font-weight-bold small text-uppercase text-gray-600">Hasta</label>
                                    <input type="date" name="fecha_fin" class="form-control" value="{{ request('fecha_fin') }}">
                                </div>
                                <div class="col-lg-2 col-md-12 mb-3 d-flex">
                                    <button type="submit" class="btn btn-primary btn-block mr-2 shadow-sm"><i class="fas fa-filter"></i> Filtrar</button>
                                    <a href="{{ route('tickets.gestion') }}" class="btn btn-secondary btn-block mt-0 shadow-sm"><i class="fas fa-eraser"></i></a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Listado de Solicitudes</h6>
                        <span class="badge badge-info">{{ $tickets->total() }} Registros</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Empleado</th>
                                        <th>Ruta</th>
                                        <th>Fecha Viaje</th>
                                        <th class="text-center">Estado</th>
                                        <th class="text-center">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tickets as $t)
                                    <tr>
                                        <td class="align-middle">
                                            <div class="font-weight-bold text-gray-800">{{ $t->Nombre }} {{ $t->Apellido }}</div>
                                            <small class="text-muted">{{ $t->email }}</small>
                                        </td>
                                        <td class="align-middle">
                                            <span>{{ $t->origen }}</span> <i class="fas fa-long-arrow-alt-right text-primary mx-1"></i> <span class="font-weight-bold">{{ $t->destino }}</span>
                                            <div class="text-xs text-info font-weight-bold">{{ $t->tipo_viaje }}</div>
                                        </td>
                                        <td class="align-middle">
                                            <div><i class="far fa-calendar-alt mr-1"></i> {{ $t->fecha_viaje }}</div>
                                            @if($t->fecha_regreso) <small class="text-danger">Regreso: {{ $t->fecha_regreso }}</small> @endif
                                        </td>
                                        <td class="align-middle text-center">
                                            @if($t->estado == 2) <span class="badge badge-warning px-2 py-1">Pendiente</span>
                                            @elseif($t->estado == 1) <span class="badge badge-success px-2 py-1">Aprobado</span>
                                            @else <span class="badge badge-danger px-2 py-1">Rechazado</span> @endif
                                        </td>
                                        <td class="align-middle text-center">
                                            @if($t->estado == 2)
                                                <button class="btn btn-primary btn-sm font-weight-bold shadow-sm" data-toggle="modal" data-target="#m{{$t->id}}">
                                                    <i class="fas fa-cog"></i> Gestionar
                                                </button>
                                            @elseif($t->estado == 1)
                                                <a href="{{ asset('archivos_tickets/'.$t->archivo_tikete) }}" target="_blank" class="btn btn-info btn-sm shadow-sm"><i class="fas fa-file-pdf"></i> Ver Tiquete</a>
                                            @else -- @endif
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="m{{$t->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content border-0 shadow-lg">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title font-weight-bold">Gestionar Solicitud #{{$t->id}}</h5>
                                                    <button class="close text-white" type="button" data-dismiss="modal"><span>×</span></button>
                                                </div>
                                                
                                                <form action="{{ route('tickets.gestionar', $t->id) }}" method="POST" enctype="multipart/form-data" onsubmit="return validarFormulario(this)">
                                                    @csrf
                                                    <div class="modal-body bg-light">
                                                        <div class="alert alert-primary mb-3">
                                                            <small class="text-uppercase font-weight-bold">Solicitante:</small><br>
                                                            <span class="h6 font-weight-bold">{{ $t->Nombre }} {{ $t->Apellido }}</span>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="font-weight-bold text-gray-800">Acción:</label>
                                                            <select name="accion" class="form-control select-accion" onchange="toggle(this, {{$t->id}})">
                                                                <option value="aprobar">✅ Aprobar (Requiere Tiquete)</option>
                                                                <option value="rechazar">❌ Rechazar</option>
                                                            </select>
                                                        </div>

                                                        <div id="file{{$t->id}}" class="form-group p-3 bg-white rounded border shadow-sm">
                                                            <label class="text-danger font-weight-bold small text-uppercase">
                                                                <i class="fas fa-file-upload mr-1"></i> Subir Tiquete/Comprobante *
                                                            </label>
                                                            <input type="file" name="archivo_tikete" class="form-control-file input-archivo" accept=".pdf, .jpg, .jpeg, .png">
                                                            <small class="form-text text-muted mt-2"><i class="fas fa-info-circle"></i> Obligatorio. Solo PDF, JPG o PNG.</small>
                                                        </div>

                                                        <div class="form-group mt-3">
                                                            <label class="small font-weight-bold text-gray-600">Mensaje (Opcional)</label>
                                                            <textarea name="mensaje_admin" class="form-control" rows="2" placeholder="Ej: Buen viaje..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-light">
                                                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                                        <button type="submit" class="btn btn-primary font-weight-bold">
                                                            <i class="fas fa-save mr-1"></i> Guardar Cambios
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <tr><td colspan="5" class="text-center py-5">No se encontraron solicitudes.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-4">{{ $tickets->appends(request()->query())->links() }}</div>
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
    // 1. Mostrar/Ocultar el campo de archivo
    function toggle(selectObj, id) {
        var fileDiv = document.getElementById('file' + id);
        if (selectObj.value == 'rechazar') {
            fileDiv.style.display = 'none';
        } else {
            fileDiv.style.display = 'block';
        }
    }

    // 2. VALIDACIÓN DEL BOTÓN "GUARDAR CAMBIOS"
    function validarFormulario(form) {
        // Buscamos el select de acción y el input de archivo DENTRO de este formulario específico
        var accion = form.querySelector('.select-accion').value;
        var archivo = form.querySelector('.input-archivo');

        // Si la acción es APROBAR y el archivo está VACÍO
        if (accion === 'aprobar' && archivo.files.length === 0) {
            // Lanzamos la alerta
            alert("⚠️ ¡ATENCIÓN!\n\nPara aprobar la solicitud es OBLIGATORIO adjuntar el tiquete o comprobante (PDF o Imagen).");
            
            // Ponemos el foco en el input para que el usuario vea dónde falta
            archivo.focus();
            
            // Detenemos el envío del formulario
            return false;
        }

        // Si todo está bien, dejamos pasar
        return true;
    }
</script>

</body>
</html>