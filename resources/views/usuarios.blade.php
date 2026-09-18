<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Plexa Core | Usuarios y Roles</title>

    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { background: #f4f6f9; }
        .ux-wrap { max-width: 1500px; margin: 0 auto; padding: 24px 20px 60px; }
        .ux-breadcrumb { font-size: .78rem; color: #8a94a6; margin-bottom: 4px; }
        .ux-breadcrumb b { color: #378E77; }
        .ux-h1 { font-size: 1.5rem; font-weight: 800; color: #16283c; margin-bottom: 22px; }

        .ux-card { background: #fff; border: 1px solid #e6eaf0; border-radius: 14px; box-shadow: 0 4px 16px rgba(20,40,70,.05); animation: uxFadeUp .5s ease both; }
        .ux-card-head { padding: 16px 22px; border-bottom: 1px solid #eef1f5; display: flex; align-items: center; justify-content: space-between; }
        .ux-card-title { font-weight: 800; color: #16283c; font-size: 1rem; margin: 0; }
        .ux-card-sub { font-size: .78rem; color: #8a94a6; margin: 2px 0 0; }
        .ux-card-body { padding: 20px 22px; }

        .ux-stat { padding: 18px 20px; display: flex; align-items: center; justify-content: space-between; }
        .ux-stat .lbl { font-size: .75rem; color: #8a94a6; font-weight: 700; }
        .ux-stat .val { font-size: 1.6rem; font-weight: 800; color: #16283c; }
        .ux-stat .ico { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
        .ico-green { background: #e8f7f1; color: #378E77; }
        .ico-blue  { background: #eaf2fd; color: #2f6fdb; }
        .ico-amber { background: #fdf6e3; color: #c99a2e; }
        .ico-gray  { background: #f1f4f8; color: #6b7686; }

        .ux-table { width: 100%; border-collapse: collapse; }
        .ux-table th { font-size: .7rem; letter-spacing: .8px; text-transform: uppercase; color: #8a94a6; text-align: left; padding: 10px 14px; border-bottom: 1px solid #eef1f5; }
        .ux-table td { padding: 12px 14px; border-bottom: 1px solid #f2f4f7; font-size: .85rem; color: #334155; vertical-align: middle; }
        .ux-table tr:last-child td { border-bottom: none; }
        .ux-avatar { width: 34px; height: 34px; border-radius: 50%; background: #e8f7f1; color: #248b6f; font-weight: 800; font-size: .75rem; display: inline-flex; align-items: center; justify-content: center; margin-right: 10px; }
        .ux-chip { background: #eef2f7; color: #64748b; border-radius: 6px; font-size: .68rem; font-weight: 700; padding: 2px 6px; margin-left: 6px; }

        .badge-rol { background: #eaf2fd; color: #2f6fdb; border-radius: 20px; padding: 5px 12px; font-size: .72rem; font-weight: 700; }
        .badge-op  { background: #fdf6e3; color: #c99a2e; border-radius: 20px; padding: 5px 12px; font-size: .72rem; font-weight: 700; margin-left: 4px; }
        .badge-on  { background: #eaf7e6; color: #4c9a3f; border-radius: 20px; padding: 5px 12px; font-size: .72rem; font-weight: 700; }
        .badge-off { background: #eef1f5; color: #8a94a6; border-radius: 20px; padding: 5px 12px; font-size: .72rem; font-weight: 700; }

        .ux-btn { border: none; border-radius: 9px; padding: 8px 14px; font-size: .78rem; font-weight: 700; cursor: pointer; }
        .ux-btn-primary { background: #1d4ed8; color: #fff; }
        .ux-btn-plexa { background: linear-gradient(135deg, #378E77, #248b6f); color: #fff; }
        .ux-btn-ghost { background: #fff; border: 1px solid #dfe4ea; color: #475569; }
        .ux-btn-ghost:hover { background: #f6f8fa; }
        .ux-btn-danger { background: #fdecec; color: #c0392b; }
        .ux-btn-sm { padding: 6px 10px; font-size: .72rem; }

        .ux-search { border: 1px solid #dfe4ea; border-radius: 9px; padding: 8px 12px 8px 34px; font-size: .82rem; width: 260px; background: #fff url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='14' height='14' fill='%238a94a6' viewBox='0 0 16 16'><path d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85zm-5.242.156a5 5 0 1 1 0-10 5 5 0 0 1 0 10z'/></svg>") no-repeat 12px center; }
        .ux-search:focus { outline: none; border-color: #378E77; }

        .role-card { border: 1px solid #e6eaf0; border-radius: 12px; padding: 16px; height: 100%; display: flex; flex-direction: column; background: #fff; transition: all .25s ease; }
        .role-card:hover { border-color: #378E77; box-shadow: 0 8px 22px rgba(55,142,119,.14); transform: translateY(-3px); }
        .role-head { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 6px; }
        .role-name { font-weight: 800; color: #16283c; font-size: .95rem; }
        .role-desc { font-size: .76rem; color: #8a94a6; flex: 1; margin-bottom: 12px; }
        .role-foot { display: flex; align-items: center; justify-content: space-between; margin-top: auto; }
        .role-count { font-size: .74rem; color: #64748b; }
        .role-count b { color: #16283c; }

        .ux-matrix { width: 100%; border-collapse: collapse; }
        .ux-matrix th { font-size: .66rem; letter-spacing: .6px; text-transform: uppercase; color: #8a94a6; padding: 10px 12px; border-bottom: 1px solid #eef1f5; text-align: center; white-space: nowrap; }
        .ux-matrix th:first-child { text-align: left; }
        .ux-matrix td { padding: 10px 12px; border-bottom: 1px solid #f2f4f7; font-size: .8rem; color: #334155; text-align: center; }
        .ux-matrix td:first-child { text-align: left; font-weight: 600; }
        .mx-yes { color: #378E77; font-weight: 800; }
        .mx-no  { color: #cbd5e1; font-weight: 700; }
        .mx-tag { font-size: .66rem; color: #a5afbd; font-weight: 600; }

        .ux-modal-card { border: none; border-radius: 16px; overflow: hidden; }
        .ux-modal-head { padding: 18px 22px; border-bottom: 1px solid #eef1f5; display: flex; gap: 12px; align-items: center; }
        .ux-modal-ico { width: 40px; height: 40px; border-radius: 10px; background: #eaf7e6; color: #4c9a3f; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .ux-modal-title { font-weight: 800; color: #16283c; font-size: 1rem; margin: 0; }
        .ux-modal-sub { font-size: .76rem; color: #8a94a6; margin: 0; }
        .ux-modal-body { padding: 22px; }
        .ux-modal-foot { padding: 14px 22px; background: #f8fafc; border-radius: 0 0 16px 16px; display: flex; align-items: center; gap: 10px; }
        .ux-label { font-size: .68rem; font-weight: 800; letter-spacing: .6px; color: #8a94a6; text-transform: uppercase; margin-bottom: 6px; display: block; }
        .ux-input { width: 100%; border: 1px solid #dfe4ea; border-radius: 9px; padding: 9px 12px; font-size: .84rem; transition: all .2s; }
        .ux-input:focus { outline: none; border-color: #378E77; box-shadow: 0 0 0 3px rgba(55,142,119,.12); }

        .perm-group { border: 1px solid #e6eaf0; border-radius: 12px; padding: 10px 14px; transition: all .25s ease; opacity: 0; }
        .perm-group:hover { border-color: #378E77; box-shadow: 0 5px 16px rgba(55,142,119,.14); transform: translateY(-2px); }
        .perm-check { display: flex; align-items: center; gap: 8px; font-size: .82rem; color: #334155; padding: 4px 0; cursor: pointer; }
        .perm-check input { accent-color: #378E77; width: 15px; height: 15px; }
        .perm-check input:checked + span { color: #248b6f; font-weight: 700; }

        .ux-modal-scroll { max-height: 62vh; overflow-y: auto; padding-right: 8px; }
        .ux-modal-scroll::-webkit-scrollbar { width: 8px; }
        .ux-modal-scroll::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 8px; }
        .ux-modal-scroll::-webkit-scrollbar-thumb { background: #378E77; border-radius: 8px; }
        .ux-modal-scroll::-webkit-scrollbar-thumb:hover { background: #248b6f; }

        @keyframes uxModalIn { from { opacity: 0; transform: scale(.92) translateY(18px); } to { opacity: 1; transform: scale(1) translateY(0); } }
        @keyframes uxGroupIn { from { opacity: 0; transform: translateX(-16px); } to { opacity: 1; transform: translateX(0); } }
        @keyframes uxPop { 0% { transform: scale(1); } 40% { transform: scale(1.35); } 100% { transform: scale(1); } }
        @keyframes uxFadeUp { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }

        .modal.show .ux-modal-card { animation: uxModalIn .35s cubic-bezier(.22, 1, .36, 1); }
        .modal.show .perm-group { animation: uxGroupIn .35s ease forwards; }
        .perm-check input:checked { animation: uxPop .25s ease; }

        .ux-close { margin-left: auto; background: #f1f5f9; border: none; width: 34px; height: 34px; border-radius: 50%; color: #64748b; font-size: 1.3rem; line-height: 1; cursor: pointer; transition: all .25s ease; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .ux-close:hover { background: #fdecec; color: #c0392b; transform: rotate(90deg); }

        .ux-counter { font-size: .75rem; background: #e8f7f1; color: #248b6f; border-radius: 20px; padding: 6px 16px; font-weight: 700; margin-right: auto; }
        .ux-group-toggle { font-size: .7rem; font-weight: 800; color: #378E77; text-decoration: none; transition: all .2s; }
        .ux-group-toggle:hover { color: #115c48; text-decoration: underline; }
    </style>
</head>

<body id="page-top">
<div id="wrapper">
    @include('layouts.menu')

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            @include('layouts.cabecera')

            <div class="ux-wrap">
                <div class="ux-breadcrumb">Administración / <b>Usuarios y roles</b></div>
                <div class="ux-h1">Usuarios y roles</div>

                @if(session('success'))
                    <div class="alert alert-success shadow-sm" style="border:none; border-radius:12px; background:#eaf7e6; color:#2f7a25;">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger shadow-sm" style="border:none; border-radius:12px; background:#fdecec; color:#c0392b;">
                        <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}
                    </div>
                @endif

                <!-- ===== STATS ===== -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="ux-card ux-stat">
                            <div><div class="lbl">Roles</div><div class="val">{{ $stats['roles'] }}</div></div>
                            <div class="ico ico-blue"><i class="fas fa-shield-alt"></i></div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="ux-card ux-stat">
                            <div><div class="lbl">Módulos</div><div class="val">{{ $stats['permisos'] }}</div></div>
                            <div class="ico ico-green"><i class="fas fa-lock"></i></div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="ux-card ux-stat">
                            <div><div class="lbl">Usuarios</div><div class="val">{{ $stats['usuarios'] }}</div></div>
                            <div class="ico ico-amber"><i class="fas fa-users"></i></div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="ux-card ux-stat">
                            <div>
                                <div class="lbl">Tu rol</div>
                                <div style="font-size:1.05rem; font-weight:800; color:#16283c;">{{ $tuRol->nombre ?? '—' }}</div>
                                <div style="font-size:.72rem; color:#8a94a6;">{{ auth()->user()->Nombre }} {{ auth()->user()->Apellido }}</div>
                            </div>
                            <div class="ico ico-gray"><i class="fas fa-user"></i></div>
                        </div>
                    </div>
                </div>

                <!-- ===== USUARIOS ===== -->
                <div class="ux-card mb-4">
                    <div class="ux-card-head">
                        <div>
                            <h6 class="ux-card-title">Usuarios</h6>
                            <p class="ux-card-sub">Personas con acceso al sistema, su rol y su tipo de operación.</p>
                        </div>
                        <button class="ux-btn ux-btn-primary" data-toggle="modal" data-target="#modalUsuario" onclick="abrirUsuarioModal(null)">
                            <i class="fas fa-plus mr-1"></i> Nuevo usuario
                        </button>
                    </div>
                    <div class="ux-card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <input type="text" id="buscarUsuario" class="ux-search" placeholder="Buscar por nombre o correo...">
                            <span class="small text-muted">{{ $usuarios->count() }} usuarios</span>
                        </div>
                        <div class="table-responsive">
                            <table class="ux-table" id="tablaUsuarios">
                                <thead>
                                    <tr>
                                        <th>Usuario</th>
                                        <th>Correo</th>
                                        <th>Celular</th>
                                        <th>Rol / Operación</th>
                                        <th>Estado</th>
                                        <th class="text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse($usuarios as $u)
                                    @php
                                        $iniciales = strtoupper(substr($u->Nombre ?? 'U', 0, 1) . substr($u->Apellido ?? 'P', 0, 1));
                                        $esAdmin        = auth()->user()->rol == 1;
                                        $esMismoUsuario = auth()->user()->cedula == $u->cedula;
                                        $uEsAdmin       = $u->rol == 1;
                                    @endphp
                                    <tr>
                                        <td>
                                            <span class="ux-avatar">{{ $iniciales }}</span>
                                            <b>{{ trim($u->Nombre . ' ' . $u->Apellido) }}</b>
                                            <span class="ux-chip">{{ $u->cedula }}</span>
                                        </td>
                                        <td>{{ $u->email ?? '—' }}</td>
                                        <td class="text-muted">{{ $u->cel ?? '—' }}</td>
                                        <td>
                                            <span class="badge-rol">{{ $u->rolInfo->nombre ?? 'Sin rol' }}</span>
                                            @if(!empty($u->tipo_operacion))
                                                <span class="badge-op">{{ $u->tipo_operacion }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($u->estado) <span class="badge-on">Activo</span>
                                            @else <span class="badge-off">Inactivo</span>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            @if($esAdmin || (!$esAdmin && !$uEsAdmin))
                                                <button type="button" class="ux-btn ux-btn-ghost ux-btn-sm mr-1"
                                                    data-toggle="modal" data-target="#modalUsuario"
                                                    onclick="abrirUsuarioModal('{{ $u->cedula }}')">
                                                    <i class="fas fa-pen mr-1"></i>Editar
                                                </button>
                                            @endif
                                            @if(($esAdmin || (!$esAdmin && !$uEsAdmin)) && !$esMismoUsuario)
                                                <form action="{{ route('usuarios.toggle', $u->cedula) }}" method="POST" class="d-inline formToggle">
                                                    @csrf
                                                    <button type="submit" class="ux-btn ux-btn-sm {{ $u->estado ? 'ux-btn-danger' : 'ux-btn-plexa' }}">
                                                        <i class="fas {{ $u->estado ? 'fa-user-slash' : 'fa-user-check' }} mr-1"></i>
                                                        {{ $u->estado ? 'Desactivar' : 'Activar' }}
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center text-muted py-4">No hay usuarios registrados</td></tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- ===== ROLES Y PERMISOS ===== -->
                <div class="ux-card mb-4">
                    <div class="ux-card-head">
                        <div>
                            <h6 class="ux-card-title">Roles y permisos</h6>
                            <p class="ux-card-sub">Crea roles a la medida y define qué pantallas puede ver cada uno.</p>
                        </div>
                        <button class="ux-btn ux-btn-primary" data-toggle="modal" data-target="#modalRol" onclick="abrirRolModal(null)">
                            <i class="fas fa-plus mr-1"></i> Nuevo rol
                        </button>
                    </div>
                    <div class="ux-card-body">
                        <div class="row">
                            @foreach($roles as $rol)
                                <div class="col-xl-3 col-md-6 mb-3">
                                    <div class="role-card">
                                        <div class="role-head">
                                            <span class="role-name"><i class="fas fa-shield-alt mr-1" style="color:#378E77;"></i> {{ $rol->nombre }}</span>
                                            @if($rol->id == 1) <span class="badge-off">Sistema</span>
                                            @else <span class="badge-on" style="background:#f4fbe4; color:#7ba23f;">Rol</span>
                                            @endif
                                        </div>
                                        <div class="role-desc">
                                            {{ implode(', ', array_slice($rolPaginas[$rol->id] ?? [], 0, 3)) }}@if(count($rolPaginas[$rol->id] ?? []) > 3) … @endif
                                        </div>
                                        <div class="role-foot">
                                            <span class="role-count"><b>{{ count($rolPaginas[$rol->id] ?? []) }}</b> pantallas</span>
                                            <span>
                                                <button type="button" class="ux-btn ux-btn-ghost ux-btn-sm"
                                                    data-toggle="modal" data-target="#modalRol"
                                                    onclick="abrirRolModal({{ $rol->id }})">
                                                    Editar
                                                </button>
                                                @if($rol->id != 1)
                                                    <form action="{{ route('usuarios.roles.destroy', $rol->id) }}" method="POST" class="d-inline formEliminarRol">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="ux-btn ux-btn-danger ux-btn-sm ml-1">Eliminar</button>
                                                    </form>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- ===== MATRIZ RESUMEN ===== -->
                <div class="ux-card">
                    <div class="ux-card-head">
                        <div>
                            <h6 class="ux-card-title">Resumen de permisos por rol</h6>
                            <p class="ux-card-sub">Qué pantalla ve cada rol de un vistazo.</p>
                        </div>
                    </div>
                    <div class="ux-card-body table-responsive">
                        <table class="ux-matrix">
                            <thead>
                                <tr>
                                    <th>Pantalla / Módulo</th>
                                    @foreach($roles as $rol) <th>{{ $rol->nombre }}</th> @endforeach
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($paginas as $pagina)
                                <tr>
                                    <td>{{ $pagina }} <span class="mx-tag">· Pantallas</span></td>
                                    @foreach($roles as $rol)
                                        <td>
                                            @if(!empty($matriz[$pagina][$rol->id]))
                                                <span class="mx-yes">✓</span>
                                            @else
                                                <span class="mx-no">✕</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr><td colspan="{{ $roles->count() + 1 }}" class="text-center text-muted py-4">No hay módulos registrados.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            @include('layouts.pie')
        </div>
    </div>
</div>

<!-- ===== MODAL USUARIO ===== -->
<div class="modal fade" id="modalUsuario" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content ux-modal-card">
            <form id="formUsuario" method="POST" action="{{ route('usuarios.store') }}">
                @csrf
                <input type="hidden" name="_method" id="usuarioMethod" value="POST">
                <div class="ux-modal-head">
                    <div class="ux-modal-ico"><i class="fas fa-user-plus"></i></div>
                    <div>
                        <h5 class="ux-modal-title" id="usuarioModalTitle">Nuevo usuario</h5>
                        <p class="ux-modal-sub">Crea un usuario, asígnale rol y tipo de operación</p>
                    </div>
                    <button type="button" class="ux-close" data-dismiss="modal" aria-label="Cerrar">&times;</button>
                </div>
                <div class="ux-modal-body ux-modal-scroll">
                    <div class="row">
                        <div class="col-6 form-group"><label class="ux-label">Cédula *</label><input type="number" name="cedula" id="u_cedula" class="ux-input" required></div>
                        <div class="col-6 form-group"><label class="ux-label">Celular</label><input type="text" name="cel" id="u_cel" class="ux-input"></div>
                        <div class="col-7 form-group"><label class="ux-label">Nombre *</label><input type="text" name="Nombre" id="u_nombre" class="ux-input" required></div>
                        <div class="col-5 form-group"><label class="ux-label">Apellido *</label><input type="text" name="Apellido" id="u_apellido" class="ux-input" required></div>
                        <div class="col-12 form-group"><label class="ux-label">Correo</label><input type="email" name="email" id="u_email" class="ux-input"></div>
                        <div class="col-6 form-group"><label class="ux-label">Rol *</label>
                            <select name="rol" id="u_rol" class="ux-input" required>
                                @foreach($roles as $r)
                                    @if(auth()->user()->rol != 1 && $r->id == 1) @continue @endif
                                    <option value="{{ $r->id }}">{{ $r->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6 form-group"><label class="ux-label">Estado *</label>
                            <select name="estado" id="u_estado" class="ux-input" required>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                        <div class="col-12 form-group"><label class="ux-label">Tipo Operación (filtro de manifiestos)</label>
                            <select name="tipo_operacion" id="u_tipo_operacion" class="ux-input">
                                <option value="">Todas (sin restricción)</option>
                                <option value="PGR">PGR</option>
                                <option value="GLP">GLP</option>
                                <option value="Transporte Liquido">Transporte Liquido</option>
                            </select>
                        </div>
                        <div class="col-12 form-group mb-0"><label class="ux-label">Contraseña <span id="u_pass_hint">*</span></label><input type="password" name="contraseña" id="u_password" class="ux-input"></div>
                    </div>
                </div>
                <div class="ux-modal-foot">
                    <button type="button" class="ux-btn ux-btn-ghost" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="ux-btn ux-btn-primary" id="usuarioSubmit">Crear usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ===== MODAL ROL (solo pantallas) ===== -->
<div class="modal fade" id="modalRol" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content ux-modal-card">
            <form id="formRol" method="POST" action="{{ route('usuarios.roles.store') }}">
                @csrf
                <input type="hidden" name="_method" id="rolMethod" value="POST">
                <div class="ux-modal-head">
                    <div class="ux-modal-ico"><i class="fas fa-shield-alt"></i></div>
                    <div>
                        <h5 class="ux-modal-title" id="rolModalTitle">Nuevo rol</h5>
                        <p class="ux-modal-sub">Selecciona las pantallas que puede ver este rol.</p>
                    </div>
                    <button type="button" class="ux-close" data-dismiss="modal" aria-label="Cerrar">&times;</button>
                </div>

                <div class="ux-modal-body ux-modal-scroll">
                    <div class="form-group">
                        <label class="ux-label">Nombre del rol *</label>
                        <input type="text" name="nombre" id="r_nombre" class="ux-input" placeholder="Ej: Auditor" required>
                    </div>

                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <label class="ux-label mb-0">Pantallas / Módulos</label>
                        <a href="javascript:void(0)" class="ux-group-toggle" onclick="toggleTodos()">Marcar todos</a>
                    </div>

                    <div class="row">
                        @foreach($paginas as $pagina)
                            <div class="col-md-6">
                                <div class="perm-group" style="animation-delay: {{ $loop->index * 0.04 }}s; margin-bottom:10px;">
                                    <label class="perm-check">
                                        <input type="checkbox" name="paginas[]" value="{{ $pagina }}" class="rol-pagina-check">
                                        <span><i class="fas fa-desktop mr-1 text-muted"></i> {{ $pagina }}</span>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="ux-modal-foot">
                    <span class="ux-counter" id="rolCounter">0 pantallas</span>
                    <button type="button" class="ux-btn ux-btn-ghost" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="ux-btn ux-btn-primary" id="rolSubmit">Crear rol</button>
                </div>
            </form>
        </div>
    </div>
</div>

<a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>

<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

<script>
    const BASE_USUARIOS = "{{ url('/usuarios') }}";
    const BASE_ROLES    = "{{ url('/usuarios/roles') }}";

    window.usuariosData = JSON.parse(atob("{{ $usuariosJsonB64 }}"));
    window.rolesData    = JSON.parse(atob("{{ $rolesDataB64 }}"));
    window.rolesInfo    = JSON.parse(atob("{{ $rolesInfoB64 }}"));

    // ===== Modal usuario =====
    function abrirUsuarioModal(cedula) {
        const form = document.getElementById('formUsuario');
        ['u_cedula','u_cel','u_nombre','u_apellido','u_email','u_password','u_tipo_operacion'].forEach(function (id) {
            document.getElementById(id).value = '';
        });

        const u = cedula ? (window.usuariosData[cedula] || null) : null;

        if (u) {
            document.getElementById('usuarioModalTitle').innerText = 'Editar usuario';
            document.getElementById('usuarioSubmit').innerText = 'Guardar cambios';
            document.getElementById('usuarioMethod').value = 'PUT';
            form.action = BASE_USUARIOS + '/' + u.cedula;
            document.getElementById('u_cedula').value = u.cedula ?? '';
            document.getElementById('u_cedula').disabled = true;
            document.getElementById('u_nombre').value   = u.Nombre ?? '';
            document.getElementById('u_apellido').value = u.Apellido ?? '';
            document.getElementById('u_email').value    = u.email ?? '';
            document.getElementById('u_cel').value      = u.cel ?? '';
            document.getElementById('u_rol').value      = u.rol ?? '';
            document.getElementById('u_estado').value   = u.estado ? '1' : '0';
            document.getElementById('u_tipo_operacion').value = u.tipo_operacion ?? '';
            document.getElementById('u_pass_hint').innerText = '(opcional)';
        } else {
            document.getElementById('usuarioModalTitle').innerText = 'Nuevo usuario';
            document.getElementById('usuarioSubmit').innerText = 'Crear usuario';
            document.getElementById('usuarioMethod').value = 'POST';
            form.action = BASE_USUARIOS;
            document.getElementById('u_cedula').disabled = false;
            document.getElementById('u_estado').value = '1';
            document.getElementById('u_tipo_operacion').value = '';
            document.getElementById('u_pass_hint').innerText = '*';
        }
    }

    // ===== Modal rol (solo pantallas) =====
    function abrirRolModal(rolId) {
        const form = document.getElementById('formRol');
        document.querySelectorAll('.rol-pagina-check').forEach(c => c.checked = false);

        if (rolId) {
            const info = window.rolesInfo[rolId] || {};
            const pags = window.rolesData[rolId] || [];
            document.getElementById('rolModalTitle').innerText = 'Editar rol';
            document.getElementById('rolSubmit').innerText = 'Guardar cambios';
            document.getElementById('rolMethod').value = 'PUT';
            form.action = BASE_ROLES + '/' + rolId;
            document.getElementById('r_nombre').value = info.nombre ?? '';

            pags.forEach(function (pag) {
                const chk = document.querySelector('.rol-pagina-check[value="' + CSS.escape(pag) + '"]');
                if (chk) chk.checked = true;
            });
        } else {
            document.getElementById('rolModalTitle').innerText = 'Nuevo rol';
            document.getElementById('rolSubmit').innerText = 'Crear rol';
            document.getElementById('rolMethod').value = 'POST';
            form.action = BASE_ROLES;
            document.getElementById('r_nombre').value = '';
        }
        actualizarContador();
    }

    function toggleTodos() {
        const checks = document.querySelectorAll('.rol-pagina-check');
        const todos = Array.from(checks).every(c => c.checked);
        checks.forEach(c => { c.checked = !todos; });
        actualizarContador();
    }

    function actualizarContador() {
        const p = document.querySelectorAll('.rol-pagina-check:checked').length;
        const el = document.getElementById('rolCounter');
        if (el) el.innerText = p + ' pantallas';
    }
    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('rol-pagina-check')) actualizarContador();
    });
    $('#modalRol').on('shown.bs.modal', actualizarContador);

    // ===== Búsqueda de usuarios =====
    document.getElementById('buscarUsuario').addEventListener('keyup', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('#tablaUsuarios tbody tr').forEach(function (tr) {
            tr.style.display = tr.innerText.toLowerCase().includes(q) ? '' : 'none';
        });
    });

    // ===== Confirmaciones SweetAlert2 =====
    $('#formUsuario').on('submit', function (e) {
        e.preventDefault();
        const form = this;
        Swal.fire({ title: '¿Guardar usuario?', icon: 'question', showCancelButton: true, confirmButtonText: 'Sí, guardar', cancelButtonText: 'Cancelar', confirmButtonColor: '#378E77' }).then(function (r) { if (r.isConfirmed) form.submit(); });
    });

    $('#formRol').on('submit', function (e) {
        e.preventDefault();
        const form = this;
        Swal.fire({ title: '¿Guardar rol y permisos?', icon: 'question', showCancelButton: true, confirmButtonText: 'Sí, guardar', cancelButtonText: 'Cancelar', confirmButtonColor: '#378E77' }).then(function (r) { if (r.isConfirmed) form.submit(); });
    });

    $(document).on('submit', '.formToggle', function (e) {
        e.preventDefault();
        const form = this;
        Swal.fire({ title: '¿Cambiar estado?', icon: 'question', showCancelButton: true, confirmButtonText: 'Sí', cancelButtonText: 'Cancelar', confirmButtonColor: '#378E77' }).then(function (r) { if (r.isConfirmed) form.submit(); });
    });

    $(document).on('submit', '.formEliminarRol', function (e) {
        e.preventDefault();
        const form = this;
        Swal.fire({ title: '¿Eliminar rol?', icon: 'warning', showCancelButton: true, confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar', confirmButtonColor: '#c0392b' }).then(function (r) { if (r.isConfirmed) form.submit(); });
    });
</script>
</body>
</html>