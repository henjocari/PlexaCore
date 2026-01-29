<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Solicitar Viaje</title>
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <style>
        .fondo-plexa{background:#fff url("{{ asset('img/Logo_plexa.svg') }}") center/80% no-repeat; min-height:100vh; padding:2rem;}
        .btn-group-toggle .btn.active {
            background-color: #4e73df;
            color: white;
            border-color: #4e73df;
        }
    </style>
</head>
<body id="page-top">
<div id="wrapper">
    @include('layouts.menu')
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            @include('layouts.cabecera')
            <div class="container-fluid fondo-plexa">
                
                <h1 class="h3 mb-4 text-gray-800 px-3 py-2 rounded shadow-sm bg-white">
                    <i class="fas fa-plane-departure mr-2 text-primary"></i> Solicitar Nuevo Viaje
                </h1>

                @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>@foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                    </div>
                @endif

                <div class="row">
                    <div class="col-xl-5 col-lg-6">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Datos del Viaje</h6>
                                
                                <div class="btn-group btn-group-toggle btn-group-sm" data-toggle="buttons">
                                    <label class="btn btn-outline-primary active" id="lbl-ida">
                                        <input type="radio" name="modo_visual" checked onchange="toggleRegreso(false)"> 
                                        Solo Ida
                                    </label>
                                    <label class="btn btn-outline-primary" id="lbl-vuelta">
                                        <input type="radio" name="modo_visual" onchange="toggleRegreso(true)"> 
                                        Ida y Vuelta
                                    </label>
                                </div>
                            </div>

                            <div class="card-body">
                                <form action="{{ route('tickets.store') }}" method="POST">
                                    @csrf
                                    
                                    <input type="hidden" name="tipo_viaje" id="input_real_tipo" value="Solo Ida">
                                    
                                    <div class="form-group">
                                        <label>Origen</label>
                                        <input type="text" name="origen" class="form-control" required placeholder="Ej: Cartagena">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Destino</label>
                                        <input type="text" name="destino" class="form-control" required placeholder="Ej: Bogotá">
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12" id="col-fecha-ida">
                                            <div class="form-group">
                                                <label>Fecha de Ida</label>
                                                <input type="date" name="fecha_viaje" class="form-control" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6" id="col-fecha-regreso" style="display: none;">
                                            <div class="form-group">
                                                <label class="text-primary font-weight-bold">Fecha de Regreso</label>
                                                <input type="date" name="fecha_regreso" id="input-regreso" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Motivo</label>
                                        <textarea name="descripcion" class="form-control" rows="3" placeholder="Describe el propósito del viaje..."></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-block font-weight-bold">
                                        ENVIAR SOLICITUD <i class="fas fa-paper-plane ml-1"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-7 col-lg-6">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-secondary">Mis Solicitudes Recientes</h6></div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Ruta</th>
                                                <th>Fecha Ida</th>
                                                <th>Fecha Vuelta</th>
                                                <th>Tipo</th>
                                                <th>Motivo</th>
                                                <th>Estado</th>
                                                <th>Tiquete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($misTickets as $t)
                                            <tr>
                                                <td>{{ $t->origen }} > {{ $t->destino }}</td>
                                                <td>{{ $t->fecha_viaje }}</td>
                                                
                                                <td class="text-center">
                                                    @if($t->fecha_regreso)
                                                        {{ $t->fecha_regreso }}
                                                    @else
                                                        <span class="text-gray-400">-</span>
                                                    @endif
                                                </td>

                                                <td>
                                                    @if($t->tipo_viaje == 'Ida y Vuelta' || $t->tipo_viaje == 'redondo')
                                                        <span class="badge badge-info">Ida y Vuelta</span>
                                                    @else
                                                        <span class="badge badge-secondary">Solo Ida</span>
                                                    @endif
                                                </td>

                                                <td title="{{ $t->descripcion }}">
                                                    {{ \Illuminate\Support\Str::limit($t->descripcion, 15) }}
                                                </td>
                                                <td>
                                                    @if($t->estado == 2) <span class="badge badge-warning">Pendiente</span>
                                                    @elseif($t->estado == 1) <span class="badge badge-success">Aprobado</span>
                                                    @else <span class="badge badge-danger">Rechazado</span> @endif
                                                </td>
                                                <td>
                                                    @if($t->estado == 1 && $t->archivo_tikete)
                                                        <a href="{{ asset('archivos_tickets/'.$t->archivo_tikete) }}" target="_blank" class="btn btn-info btn-sm btn-circle"><i class="fas fa-download"></i></a>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    {{ $misTickets->links() }}
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
    function toggleRegreso(esRedondo) {
        const colIda = document.getElementById('col-fecha-ida');
        const colRegreso = document.getElementById('col-fecha-regreso');
        const inputRegreso = document.getElementById('input-regreso');
        const lblIda = document.getElementById('lbl-ida');
        const lblVuelta = document.getElementById('lbl-vuelta');
        const inputReal = document.getElementById('input_real_tipo');

        if (esRedondo) {
            // MOSTRAR
            colIda.classList.remove('col-md-12');
            colIda.classList.add('col-md-6');
            colRegreso.style.display = 'block';
            inputRegreso.required = true; 
            lblIda.classList.remove('active');
            lblVuelta.classList.add('active');

            // GUARDA EL TEXTO CORRECTO EN LA BD
            inputReal.value = 'Ida y Vuelta'; 
        } else {
            // OCULTAR
            colIda.classList.remove('col-md-6');
            colIda.classList.add('col-md-12');
            colRegreso.style.display = 'none';
            inputRegreso.required = false; 
            inputRegreso.value = ''; 
            lblIda.classList.add('active');
            lblVuelta.classList.remove('active');

            // GUARDA EL TEXTO CORRECTO EN LA BD
            inputReal.value = 'Solo Ida';
        }
    }
</script>

</body>
</html>