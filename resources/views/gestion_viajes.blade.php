<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Gestión de Viajes</title>
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/favicon.png') }}">
    <style>.fondo-plexa{background:#fff url("{{ asset('img/Logo_plexa.svg') }}") center/80% no-repeat; min-height:100vh; padding:2rem;}</style>
</head>
<body id="page-top">
<div id="wrapper">
    @include('layouts.menu')
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            @include('layouts.cabecera')
            <div class="container-fluid fondo-plexa">
                
                <h1 class="h3 mb-4 text-gray-800 px-3 py-2 rounded shadow-sm bg-white">
                    <i class="fas fa-tasks mr-2 text-danger"></i> Gestión de Solicitudes (Administrador)
                </h1>

                @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

                <div class="card shadow mb-4">
                    <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Todas las Solicitudes</h6></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-dark"><tr><th>Empleado</th><th>Ruta</th><th>Fecha</th><th>Estado</th><th>Acción</th></tr></thead>
                                <tbody>
                                    @foreach($tickets as $t)
                                    <tr>
                                        {{-- CORRECCIÓN AQUÍ: Usamos Mayúsculas --}}
                                        <td>{{ $t->Nombre }} {{ $t->Apellido }}</td>
                                        
                                        <td>{{ $t->origen }} <i class="fas fa-arrow-right"></i> {{ $t->destino }}</td>
                                        <td>{{ $t->fecha_viaje }}</td>
                                        <td>
                                            @if($t->estado == 2) <span class="badge badge-warning">Pendiente</span>
                                            @elseif($t->estado == 1) <span class="badge badge-success">Aprobado</span>
                                            @else <span class="badge badge-danger">Rechazado</span> @endif
                                        </td>
                                        <td>
                                            @if($t->estado == 2)
                                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#m{{$t->id}}">Gestionar</button>
                                            @elseif($t->estado == 1)
                                                <a href="{{ asset('archivos_tickets/'.$t->archivo_tikete) }}" target="_blank" class="btn btn-secondary btn-sm">Ver Tiquete</a>
                                            @endif
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="m{{$t->id}}">
                                        <div class="modal-dialog"><div class="modal-content">
                                            <div class="modal-header bg-primary text-white"><h5 class="modal-title">Gestionar #{{$t->id}}</h5><button class="close text-white" data-dismiss="modal">&times;</button></div>
                                            <form action="{{ route('tickets.gestionar', $t->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body">
                                                    {{-- CORRECCIÓN AQUÍ TAMBIÉN --}}
                                                    <p><strong>Solicitante:</strong> {{ $t->Nombre }} {{ $t->Apellido }}</p>
                                                    
                                                    <label>Acción:</label>
                                                    <select name="accion" class="form-control mb-3" onchange="toggle(this, {{$t->id}})">
                                                        <option value="aprobar">✅ Aprobar</option><option value="rechazar">❌ Rechazar</option>
                                                    </select>
                                                    <div id="file{{$t->id}}"><label class="text-danger">Subir Tiquete *</label><input type="file" name="archivo_tikete" class="form-control"></div>
                                                    <textarea name="mensaje_admin" class="form-control mt-3" placeholder="Mensaje..."></textarea>
                                                </div>
                                                <div class="modal-footer"><button type="submit" class="btn btn-primary">Guardar</button></div>
                                            </form>
                                        </div></div>
                                    </div>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $tickets->links() }}
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
<script>function toggle(s,i){document.getElementById('file'+i).style.display=s.value=='rechazar'?'none':'block';}</script>
</body>
</html>