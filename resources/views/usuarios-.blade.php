@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-primary fw-bold mb-0">Gestión de Usuarios</h4>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCrearUsuario">
            <i class="fas fa-user-plus"></i> Nuevo Usuario
        </button>
    </div>

    {{-- Tabla de usuarios --}}
    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-primary text-center">
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
                        <td>
                            @if($u->rol == 1)
                                <span class="badge bg-success">Administrador</span>
                            @else
                                <span class="badge bg-secondary">Usuario</span>
                            @endif
                        </td>
                        <td>
                            @if($u->estado)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditarUsuario"
                                data-id="{{ $u->cedula }}"
                                data-nombre="{{ $u->Nombre }}"
                                data-apellido="{{ $u->Apellido }}"
                                data-email="{{ $u->email }}"
                                data-cel="{{ $u->cel }}"
                                data-rol="{{ $u->rol }}"
                                data-estado="{{ $u->estado }}">
                                <i class="fas fa-edit"></i>
                            </button>

                            <form action="{{ route('usuarios.toggle', $u->cedula) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm {{ $u->estado ? 'btn-danger' : 'btn-success' }}">
                                    <i class="fas {{ $u->estado ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">No hay usuarios registrados</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL CREAR --}}
<div class="modal fade" id="modalCrearUsuario" tabindex="-1" aria-labelledby="crearUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('usuarios.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Nuevo Usuario</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="form-label">Cédula</label>
                        <input type="number" name="cedula" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Celular</label>
                        <input type="text" name="cel" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="Nombre" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Apellido</label>
                        <input type="text" name="Apellido" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Correo</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="contraseña" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Rol</label>
                        <select name="rol" class="form-select">
                            <option value="1">Administrador</option>
                            <option value="2">Usuario</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-select">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDITAR --}}
<div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-labelledby="editarUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formEditarUsuario" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">Editar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-2">
                <div class="col-md-6">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="Nombre" id="editNombre" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Apellido</label>
                    <input type="text" name="Apellido" id="editApellido" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Correo</label>
                    <input type="email" name="email" id="editEmail" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Celular</label>
                    <input type="text" name="cel" id="editCel" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Rol</label>
                    <select name="rol" id="editRol" class="form-select">
                        <option value="1">Administrador</option>
                        <option value="2">Usuario</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Estado</label>
                    <select name="estado" id="editEstado" class="form-select">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-warning">Actualizar</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.getElementById('modalEditarUsuario').addEventListener('show.bs.modal', function (event) {
    let button = event.relatedTarget;
    let id = button.getAttribute('data-id');
    document.getElementById('formEditarUsuario').action = '/usuarios/' + id;

    document.getElementById('editNombre').value = button.getAttribute('data-nombre');
    document.getElementById('editApellido').value = button.getAttribute('data-apellido');
    document.getElementById('editEmail').value = button.getAttribute('data-email');
    document.getElementById('editCel').value = button.getAttribute('data-cel');
    document.getElementById('editRol').value = button.getAttribute('data-rol');
    document.getElementById('editEstado').value = button.getAttribute('data-estado');
});
</script>
@endpush
