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
                                    
                                    <h6 class="font-weight-bold text-primary mb-3 border-bottom pb-2 mt-2">
                                        <i class="fas fa-user-check mr-1"></i> Información del Pasajero
                                    </h6>

                                    <div class="row mb-2">
                                        <div class="col-md-12 mb-3">
                                            <label class="text-secondary small font-weight-bold">Nombre Completo del Viajero *</label>
                                            <input type="text" name="beneficiario_nombre" class="form-control" placeholder="Ej: Pepito Pérez" required value="{{ old('beneficiario_nombre') }}">
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label class="text-secondary small font-weight-bold">Cédula / ID *</label>
                                            <input type="text" name="beneficiario_cedula" class="form-control" placeholder="Ej: 1045..." required value="{{ old('beneficiario_cedula') }}" inputmode="numeric" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')" title="Solo se permiten números">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="text-secondary small font-weight-bold">Fecha de Nacimiento *</label>
                                            <input type="date" name="beneficiario_fecha_nac" class="form-control" required value="{{ old('beneficiario_fecha_nac') }}">
                                        </div>
                                    </div>

                                    <h6 class="font-weight-bold text-primary mb-3 border-bottom pb-2 mt-4">
                                        <i class="fas fa-map-marked-alt mr-1"></i> Itinerario del Viaje
                                    </h6>

                                    <div class="form-group">
                                        <label class="text-secondary small font-weight-bold">Origen *</label>
                                        <input type="text" name="origen" class="form-control" required placeholder="Ej: Cartagena">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="text-secondary small font-weight-bold">Destino *</label>
                                        <input type="text" name="destino" class="form-control" required placeholder="Ej: Bogotá">
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12" id="col-fecha-ida">
                                            <div class="form-group">
                                                <label class="text-secondary small font-weight-bold">Fecha de Ida *</label>
                                                <input type="date" name="fecha_viaje" class="form-control" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6" id="col-fecha-regreso" style="display: none;">
                                            <div class="form-group">
                                                <label class="text-primary font-weight-bold small">Fecha de Regreso *</label>
                                                <input type="date" name="fecha_regreso" id="input-regreso" class="form-control border-left-primary">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="text-secondary small font-weight-bold">Motivo del Viaje *</label>
                                        <textarea name="descripcion" class="form-control" rows="3" placeholder="Describe el propósito del viaje..." required></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-block font-weight-bold mt-4">
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
                                                <th>Pasajero</th>
                                                <th>Ruta</th>
                                                <th>Fechas</th>
                                                <th>Tipo</th>
                                                <th>Estado</th>
                                                <th>Tiquete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($misTickets as $t)
                                            <tr>
                                                <td class="align-middle">
                                                    <span class="font-weight-bold">{{ $t->beneficiario_nombre ?? 'N/A' }}</span><br>
                                                    <small class="text-muted">CC: {{ $t->beneficiario_cedula ?? 'N/A' }}</small>
                                                </td>

                                                <td class="align-middle">
                                                    {{ $t->origen }} <i class="fas fa-arrow-right text-muted mx-1"></i> {{ $t->destino }}
                                                </td>
                                                
                                                <td class="align-middle text-nowrap">
                                                    <div class="small"><strong>Ida:</strong> {{ $t->fecha_viaje }}</div>
                                                    @if($t->fecha_regreso)
                                                        <div class="small text-info"><strong>Regreso:</strong> {{ $t->fecha_regreso }}</div>
                                                    @endif
                                                </td>

                                                <td class="align-middle text-center">
                                                    @if($t->tipo_viaje == 'Ida y Vuelta' || $t->tipo_viaje == 'redondo')
                                                        <span class="badge badge-info">Ida y Vuelta</span>
                                                    @else
                                                        <span class="badge badge-secondary">Solo Ida</span>
                                                    @endif
                                                </td>

                                                <td class="align-middle text-center">
                                                    @if($t->estado == 2) <span class="badge badge-warning">Pendiente</span>
                                                    @elseif($t->estado == 1) <span class="badge badge-success">Aprobado</span>
                                                    @else <span class="badge badge-danger">Rechazado</span> @endif
                                                </td>
                                                
                                                <td class="align-middle text-center">
                                                    @if($t->estado == 1 && $t->archivo_tikete)
                                                        <a href="{{ asset('archivos_tickets/'.$t->archivo_tikete) }}" target="_blank" class="btn btn-info btn-sm btn-circle"><i class="fas fa-download"></i></a>
                                                    @else
                                                        -
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