<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Plexa Core | Gestión de Usuarios</title>

    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body id="page-top">
    <div id="wrapper">
        @include('layouts.menu')

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                @include('layouts.cabecera')

                <div class="container-fluid mt-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="text-primary font-weight-bold mb-0">Gestión de Usuarios</h4>
                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalCrearUsuario">
                            <i class="fas fa-user-plus"></i> Nuevo Usuario
                        </button>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-body table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="thead-dark text-center">
                                    <tr>
                                        <th>Cédula</th>
                                        <th>Nombre</th>
                                        <th>Apellido</th>
                                        <th>Email</th>
                                        <th>Celular</th>
                                        <th>Rol</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($usuarios as $u)
                                        <tr class="text-center">
                                            <td>{{ $u->cedula }}</td>
                                            <td>{{ $u->Nombre }}</td>
                                            <td>{{ $u->Apellido }}</td>
                                            <td>{{ $u->email }}</td>
                                            <td>{{ $u->cel }}</td>
                                            <td><span class="badge badge-info">{{ $u->rolInfo->nombre ?? 'Sin rol' }}</span></td>
                                            <td>
                                                @if($u->estado)
                                                    <span class="badge badge-success">Activo</span>
                                                @else
                                                    <span class="badge badge-danger">Inactivo</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $esAdmin = auth()->user()->rol == 1;
                                                    $esMismoUsuario = auth()->user()->cedula == $u->cedula;
                                                    $uEsAdmin = $u->rol == 1;
                                                @endphp

                                                {{-- Botón editar --}}
                                                @if($esAdmin || (!$esAdmin && !$uEsAdmin))
                                                    <button class="btn btn-warning btn-sm btnEditar"
                                                        data-id="{{ $u->cedula }}"
                                                        data-nombre="{{ $u->Nombre }}"
                                                        data-apellido="{{ $u->Apellido }}"
                                                        data-email="{{ $u->email }}"
                                                        data-cel="{{ $u->cel }}"
                                                        data-rol="{{ $u->rol }}"
                                                        data-estado="{{ $u->estado }}"
                                                        data-toggle="modal"
                                                        data-target="#modalEditarUsuario">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                @endif

                                                {{-- Botón activar/inactivar --}}
                                                @if(($esAdmin || (!$esAdmin && !$uEsAdmin)) && !$esMismoUsuario)
                                                    <form action="{{ route('usuarios.toggle', $u->cedula) }}" method="POST" class="d-inline formToggle">
                                                        @csrf
                                                        <button type="submit"
                                                            class="btn btn-sm {{ $u->estado ? 'btn-danger' : 'btn-success' }}">
                                                            <i class="fas {{ $u->estado ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="8" class="text-center text-muted">No hay usuarios registrados</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- MODAL CREAR -->
                <div class="modal fade" id="modalCrearUsuario" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <form id="formCrearUsuario" action="{{ route('usuarios.store') }}" method="POST" class="modal-content">
                            @csrf
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title">Nuevo Usuario</h5>
                                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body row g-2">
                                <div class="col-md-6">
                                    <label>Cédula</label>
                                    <input type="number" name="cedula" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label>Celular</label>
                                    <input type="text" name="cel" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label>Nombre</label>
                                    <input type="text" name="Nombre" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label>Apellido</label>
                                    <input type="text" name="Apellido" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label>Correo</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label>Contraseña</label>
                                    <input type="password" name="contraseña" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label>Rol</label>
                                    <select name="rol" class="form-control" required>
                                        @foreach($roles as $r)
                                            @if(auth()->user()->rol != 1 && $r->id == 1)
                                                @continue
                                            @endif
                                            <option value="{{ $r->id }}">{{ $r->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label>Estado</label>
                                    <select name="estado" class="form-control" required>
                                        <option value="1">Activo</option>
                                        <option value="0">Inactivo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- MODAL EDITAR -->
                <div class="modal fade" id="modalEditarUsuario" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <form id="formEditarUsuario" method="POST" class="modal-content">
                            @csrf
                            @method('PUT')
                            <div class="modal-header bg-warning text-dark">
                                <h5 class="modal-title">Editar Usuario</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body row g-2">
                                <div class="col-md-6">
                                    <label>Nombre</label>
                                    <input type="text" name="Nombre" id="editNombre" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label>Apellido</label>
                                    <input type="text" name="Apellido" id="editApellido" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label>Correo</label>
                                    <input type="email" name="email" id="editEmail" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label>Celular</label>
                                    <input type="text" name="cel" id="editCel" class="form-control">
                                </div>

                                <div class="col-md-6">
                                    <label>Rol</label>
                                    <select name="rol" id="editRol" class="form-control" required>
                                        @foreach($roles as $r)
                                            @if(auth()->user()->rol != 1 && $r->id == 1)
                                                @continue
                                            @endif
                                            <option value="{{ $r->id }}">{{ $r->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 campo-estado">
                                    <label>Estado</label>
                                    <select name="estado" id="editEstado" class="form-control" required>
                                        <option value="1">Activo</option>
                                        <option value="0">Inactivo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-warning">Actualizar</button>
                            </div>
                        </form>
                    </div>
                </div>

                @include('layouts.pie')
            </div>
        </div>
    </div>

    <a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>

    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    <script>
        // Confirmar crear usuario
        $('#formCrearUsuario').on('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Guardar nuevo usuario?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'Cancelar'
            }).then(result => {
                if (result.isConfirmed) this.submit();
            });
        });

        // Confirmar editar usuario
        $('#formEditarUsuario').on('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Actualizar datos del usuario?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, actualizar',
                cancelButtonText: 'Cancelar'
            }).then(result => {
                if (result.isConfirmed) this.submit();
            });
        });

        // Confirmar activar/inactivar usuario
        $('.formToggle').on('submit', function(e) {
            e.preventDefault();
            let form = this;
            Swal.fire({
                title: '¿Cambiar estado del usuario?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, continuar',
                cancelButtonText: 'Cancelar'
            }).then(result => {
                if (result.isConfirmed) form.submit();
            });
        });

        // Configurar modal editar
        $('#modalEditarUsuario').on('show.bs.modal', function(event) {
            let button = $(event.relatedTarget);
            let id = button.data('id');
            $('#formEditarUsuario').attr('action', '/usuarios/' + id);

            $('#editNombre').val(button.data('nombre'));
            $('#editApellido').val(button.data('apellido'));
            $('#editEmail').val(button.data('email'));
            $('#editCel').val(button.data('cel'));
            $('#editRol').val(button.data('rol'));
            $('#editEstado').val(button.data('estado'));

            const cedulaSesion = "{{ auth()->user()->cedula }}";
            const campoEstado = $('.campo-estado');

            if (id == cedulaSesion) {
                campoEstado.hide();
            } else {
                campoEstado.show();
            }
        });
    </script>
</body>
</html>
