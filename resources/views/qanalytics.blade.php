<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Plexa Core | QAnalytics</title>

    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <!-- SweetAlert2 para alertas de confirmación -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        body { background: #f4f6f9; }
        .ux-wrap { max-width: 1500px; margin: 0 auto; padding: 24px 20px 60px; }
        .ux-breadcrumb { font-size: .78rem; color: #8a94a6; margin-bottom: 4px; }
        .ux-breadcrumb b { color: #378E77; }
        .ux-h1 { font-size: 1.5rem; font-weight: 800; color: #16283c; margin-bottom: 22px; }

        .ux-card { background: #fff; border: 1px solid #e6eaf0; border-radius: 14px; box-shadow: 0 4px 16px rgba(20,40,70,.05); margin-bottom: 24px;}
        .ux-card-head { padding: 16px 22px; border-bottom: 1px solid #eef1f5; display: flex; align-items: center; justify-content: space-between; }
        .ux-card-title { font-weight: 800; color: #16283c; font-size: 1rem; margin: 0; }
        .ux-card-body { padding: 20px 22px; }

        .ux-table { width: 100%; border-collapse: collapse; }
        .ux-table th { font-size: .7rem; letter-spacing: .8px; text-transform: uppercase; color: #8a94a6; text-align: left; padding: 10px 14px; border-bottom: 1px solid #eef1f5; background-color: #f8fafc;}
        .ux-table td { padding: 12px 14px; border-bottom: 1px solid #f2f4f7; font-size: .85rem; color: #334155; vertical-align: middle; }
        .ux-table tr:nth-child(even) td { background-color: #f9f9f9; }
        .ux-table tr:last-child td { border-bottom: none; }

        .pagination { margin-top: 20px; display: flex; justify-content: center;}
        
        .action-link { color: #378E77; text-decoration: none; font-weight: bold; font-size: 0.8rem; cursor: pointer;}
        .action-link:hover { text-decoration: underline; color: #248b6f;}
        .action-separator { color: #ccc; margin: 0 5px; }

        /* Estilos para los botones y modal UX */
        .ux-btn { border: none; border-radius: 9px; padding: 8px 14px; font-size: .78rem; font-weight: 700; cursor: pointer; }
        .ux-btn-primary { background: #378E77; color: #fff; }
        .ux-btn-primary:hover { background: #248b6f; }
        .ux-btn-ghost { background: #fff; border: 1px solid #dfe4ea; color: #475569; }
        .ux-btn-ghost:hover { background: #f6f8fa; }
        
        .ux-modal-card { border: none; border-radius: 16px; overflow: hidden; }
        .ux-modal-head { padding: 18px 22px; border-bottom: 1px solid #eef1f5; display: flex; gap: 12px; align-items: center; }
        .ux-modal-ico { width: 40px; height: 40px; border-radius: 10px; background: #eaf7e6; color: #4c9a3f; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .ux-modal-title { font-weight: 800; color: #16283c; font-size: 1rem; margin: 0; }
        .ux-modal-sub { font-size: .76rem; color: #8a94a6; margin: 0; }
        .ux-modal-body { padding: 22px; }
        .ux-modal-foot { padding: 14px 22px; background: #f8fafc; border-radius: 0 0 16px 16px; display: flex; align-items: center; justify-content: flex-end; gap: 10px; }
        
        .ux-label { font-size: .68rem; font-weight: 800; letter-spacing: .6px; color: #8a94a6; text-transform: uppercase; margin-bottom: 6px; display: block; }
        .ux-input { width: 100%; border: 1px solid #dfe4ea; border-radius: 9px; padding: 9px 12px; font-size: .84rem; transition: all .2s; }
        .ux-input:focus { outline: none; border-color: #378E77; box-shadow: 0 0 0 3px rgba(55,142,119,.12); }
        .ux-close { margin-left: auto; background: #f1f5f9; border: none; width: 34px; height: 34px; border-radius: 50%; color: #64748b; font-size: 1.3rem; line-height: 1; cursor: pointer; transition: all .25s ease; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .ux-close:hover { background: #fdecec; color: #c0392b; transform: rotate(90deg); }
    </style>
</head>

<body id="page-top">
<div id="wrapper">
    @include('layouts.menu')

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            @include('layouts.cabecera')

            <div class="ux-wrap">
                <div class="ux-breadcrumb">Administración / <b>QAnalytics</b></div>
                <div class="ux-h1">Administración QAnalytics</div>

                <!-- Alertas del sistema -->
                @if(session('success'))
                    <div class="alert alert-success shadow-sm" style="border:none; border-radius:12px; background:#eaf7e6; color:#2f7a25;">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger shadow-sm" style="border:none; border-radius:12px; background:#fdecec; color:#c0392b;">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Sección: Filtros GPS -->
                <div class="ux-card">
                    <div class="ux-card-head">
                        <h6 class="ux-card-title">Filtros GPS</h6>
                        <button class="ux-btn ux-btn-primary" data-toggle="modal" data-target="#modalFiltro" onclick="abrirFiltroModal(null)">
                            <i class="fas fa-plus mr-1"></i> Nuevo filtro
                        </button>
                    </div>
                    <div class="ux-card-body">
                        <div class="table-responsive">
                            <table class="ux-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tipo</th>
                                        <th>Valor</th>
                                        <th>Activo</th>
                                        <th>Creado</th>
                                        <th>Actualizado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($filtros as $filtro)
                                        <tr>
                                            <td>{{ $filtro->id }}</td>
                                            <td>{{ $filtro->tipo }}</td>
                                            <td>{{ $filtro->valor }}</td>
                                            <td>
                                                @if($filtro->activo)
                                                    <span style="color:#2f7a25; font-weight:bold;">Sí</span>
                                                @else
                                                    <span style="color:#c0392b; font-weight:bold;">No</span>
                                                @endif
                                            </td>
                                            <td>{{ $filtro->created_at ?? '—' }}</td>
                                            <td>{{ $filtro->updated_at ?? '—' }}</td>
                                            <td>
                                                <a href="javascript:void(0)" class="action-link" 
                                                    data-toggle="modal" 
                                                    data-target="#modalFiltro" 
                                                    onclick="abrirFiltroModal({{ $filtro->id }}, '{{ $filtro->tipo }}', '{{ $filtro->valor }}', {{ $filtro->activo }})">
                                                    Editar
                                                </a>
                                                <span class="action-separator">|</span>
                                                <form action="{{ route('qanalytics.filtros.destroy', $filtro->id) }}" method="POST" class="d-inline formEliminarFiltro">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" style="background:none; border:none; padding:0;" class="action-link text-danger">Borrar</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">No hay filtros registrados.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Sección: Vehículos Temporales -->
                <div class="ux-card">
                    <div class="ux-card-head">
                        <h6 class="ux-card-title">Seguimiento Temporal (Sin Manifiesto Activo)</h6>
                    </div>
                    <div class="ux-card-body">
                        <!-- Formulario de ingreso -->
                        <form action="{{ route('qanalytics.temporales.store') }}" method="POST" style="display: flex; gap: 15px; align-items: center; margin-bottom: 20px;">
                            @csrf
                            <select name="placa" class="ux-input select2-busqueda" required style="min-width: 200px;">
                                <option value="">Buscar Placa...</option>
                                @foreach($placas as $placa)
                                    <option value="{{ $placa }}">{{ $placa }}</option>
                                @endforeach
                            </select>

                            <select name="cisterna" class="ux-input select2-busqueda" required style="min-width: 200px;">
                                <option value="">Buscar Cisterna...</option>
                                @foreach($cisternas as $cisterna)
                                    <option value="{{ $cisterna }}">{{ $cisterna }}</option>
                                @endforeach
                            </select>

                            <button type="submit" class="ux-btn ux-btn-primary"><i class="fas fa-search-location mr-1"></i> Agregar a seguimiento</button>
                        </form>

                        <div class="table-responsive">
                            <table class="ux-table">
                                <thead>
                                    <tr>
                                        <th>Placa</th>
                                        <th>Cisterna</th>
                                        <th>Fecha Agregado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($temporales as $temp)
                                        <tr>
                                            <td><strong>{{ $temp->placa }}</strong></td>
                                            <td>{{ $temp->cisterna ?? '—' }}</td>
                                            <td>{{ $temp->created_at }}</td>
                                            <td>
                                                <form action="{{ route('qanalytics.temporales.destroy', $temp->id) }}" method="POST" class="d-inline formEliminarFiltro">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="action-link text-danger" style="background:none; border:none; padding:0;">Quitar</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">No hay vehículos en seguimiento temporal.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Sección: Logs QAnalytics -->
                <div class="ux-card">
                    <div class="ux-card-head" style="display: flex; flex-wrap: wrap; gap: 10px;">
                        <h6 class="ux-card-title" style="margin-right: auto;">Logs QAnalytics (Recientes)</h6>
                        
                        <form action="{{ route('qanalytics') }}" method="GET" style="display: flex; gap: 10px;">
                            <input type="text" name="buscar_placa" class="ux-input" placeholder="Buscar Placa..." value="{{ request('buscar_placa') }}" style="max-width: 150px; padding: 6px 12px; font-size: .8rem; text-transform: uppercase;">
                            <button type="submit" class="ux-btn ux-btn-ghost" style="padding: 6px 12px; font-size: .8rem;">Buscar</button>
                            @if(request()->filled('buscar_placa'))
                                <a href="{{ route('qanalytics') }}" class="ux-btn ux-btn-ghost text-danger" style="padding: 6px 12px; font-size: .8rem; display: flex; align-items: center;"><i class="fas fa-times"></i></a>
                            @endif
                        </form>
                        
                        <a href="{{ route('qanalytics.logs.exportar', ['buscar_placa' => request('buscar_placa')]) }}" class="ux-btn ux-btn-primary" style="padding: 6px 12px; font-size: .8rem; background-color: #2e7d32; border-color: #2e7d32;">
                            <i class="fas fa-file-excel mr-1"></i> Exportar
                        </a>
                    </div>
                    <div class="ux-card-body">
                        <div class="table-responsive">
                            <table class="ux-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Placa</th>
                                        <th>Payload</th>
                                        <th>Status Code</th>
                                        <th>Response Body</th>
                                        <th>Fecha Creación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($logs as $log)
                                        <tr>
                                            <td>{{ $log->id }}</td>
                                            <td>{{ $log->placa }}</td>
                                            <td title="{{ $log->payload }}">{{ Str::limit($log->payload, 50) }}</td>
                                            <td>
                                                <span class="badge {{ $log->status_code == 200 ? 'badge-success' : 'badge-danger' }}">
                                                    {{ $log->status_code }}
                                                </span>
                                            </td>
                                            <td title="{{ $log->response_body }}">{{ Str::limit($log->response_body, 50) }}</td>
                                            <td>{{ $log->created_at }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">No hay logs registrados.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="pagination">
                            {{ $logs->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>

            </div>
            
            @include('layouts.pie')
        </div>
    </div>
</div>

<!-- Modal para Crear/Editar Filtro -->
<!-- Modal Filtro -->
<div class="modal fade" id="modalFiltro" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content ux-modal-card">
            <!-- El action se llena con JS -->
            <form id="formFiltro" method="POST" action="">
                @csrf
                <!-- Input clave para que Laravel sepa si es POST (Crear) o PUT (Editar) -->
                <input type="hidden" name="_method" id="filtroMethod" value="POST">
                
                <div class="ux-modal-head">
                    <div class="ux-modal-ico"><i class="fas fa-filter"></i></div>
                    <div>
                        <h5 class="ux-modal-title" id="filtroModalTitle">Nuevo filtro</h5>
                        <p class="ux-modal-sub">Configura un nuevo parámetro para el GPS</p>
                    </div>
                    <button type="button" class="ux-close" data-dismiss="modal">&times;</button>
                </div>
                
                <div class="ux-modal-body">
                    <div class="form-group">
                        <label class="ux-label">Tipo *</label>
                        <select name="tipo" id="f_tipo" class="ux-input" required>
                            <option value="operacion">operacion</option>
                            <option value="cliente">cliente</option>
                            <option value="placa">placa</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="ux-label">Valor</label>
                        <input type="text" name="valor" id="f_valor" class="ux-input">
                    </div>
                    <div class="form-group">
                        <label class="ux-label">Activo *</label>
                        <select name="activo" id="f_activo" class="ux-input" required>
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
                
                <div class="ux-modal-foot">
                    <button type="button" class="ux-btn ux-btn-ghost" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="ux-btn ux-btn-primary" id="filtroSubmit">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>

<!-- Scripts necesarios -->
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

<script>
    const BASE_FILTROS = "{{ url('/qanalytics/filtros') }}";
    // Cargamos los datos codificados desde el controlador
    window.filtrosData = JSON.parse(atob("{{ $filtrosJson ?? 'e30=' }}"));

    // Función que se ejecuta al presionar "Nuevo Filtro" o "Editar"
    function abrirFiltroModal(id, tipo, valor, activo) {
        const form = document.getElementById('formFiltro');
        const methodInput = document.getElementById('filtroMethod');
        const title = document.getElementById('filtroModalTitle');
        const submitBtn = document.getElementById('filtroSubmit');

        if (id) {
            // MODO EDICIÓN
            title.innerText = 'Editar filtro';
            submitBtn.innerText = 'Guardar cambios';
            
            // ¡Importante! Cambiar el método a PUT y poner el ID en la ruta
            methodInput.value = 'PUT';
            form.action = BASE_FILTROS + '/' + id;

            // Llenar los campos con los datos actuales
            document.getElementById('f_tipo').value = tipo || '';
            document.getElementById('f_valor').value = valor || '';
            document.getElementById('f_activo').value = activo;
        } else {
            // MODO CREACIÓN
            title.innerText = 'Nuevo filtro';
            submitBtn.innerText = 'Crear filtro';
            
            // Restaurar método POST y ruta base (sin ID)
            methodInput.value = 'POST';
            form.action = BASE_FILTROS;

            // Limpiar los campos
            document.getElementById('f_tipo').value = '';
            document.getElementById('f_valor').value = '';
            document.getElementById('f_activo').value = '1';
        }
    }

    // Interceptar el envío del formulario de eliminar para pedir confirmación con SweetAlert2
    $(document).on('submit', '.formEliminarFiltro', function (e) {
        e.preventDefault();
        const form = this;
        Swal.fire({
            title: '¿Eliminar filtro?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#c0392b',
            cancelButtonColor: '#475569',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then(function (result) {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
    
</script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2-busqueda').select2({
            width: 'resolve',
            tags: true, // Permite escribir texto que no esté en la lista
            createTag: function (params) {
                var term = $.trim(params.term);
                if (term === '') return null;
                return {
                    id: term.toUpperCase(),
                    text: term.toUpperCase(),
                    newTag: true
                }
            }
        });
    });
</script>
@if(session('error_swal'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Acción bloqueada',
        text: '{{ session('error_swal') }}',
        confirmButtonColor: '#c0392b'
    });
</script>
@endif
@if(session('warning_swal'))
<script>
    Swal.fire({
        icon: 'warning',
        title: 'Verifique la información',
        text: '{{ session('warning_swal') }}',
        confirmButtonColor: '#f6c23e'
    });
</script>
@endif
</body>
</html>