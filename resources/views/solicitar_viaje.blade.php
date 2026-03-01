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
                                            <input type="text" name="beneficiario_nombre" class="form-control" placeholder="Nombre" required value="{{ old('beneficiario_nombre') }}">
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label class="text-secondary small font-weight-bold">Cédula / ID *</label>
                                            <input type="text" name="beneficiario_cedula" class="form-control" placeholder="CC" required value="{{ old('beneficiario_cedula') }}" inputmode="numeric" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="text-secondary small font-weight-bold">Fecha de Nacimiento *</label>
                                            <input type="date" name="beneficiario_fecha_nac" class="form-control" required value="{{ old('beneficiario_fecha_nac') }}">
                                        </div>
                                    </div>

                                    <h6 class="font-weight-bold text-primary mb-3 border-bottom pb-2 mt-4">
                                        <i class="fas fa-map-marked-alt mr-1"></i> Itinerario del Viaje
                                    </h6>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="text-secondary small font-weight-bold">Origen *</label>
                                            <input type="text" name="origen" class="form-control" required placeholder="Ej: Cartagena">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="text-secondary small font-weight-bold">Destino *</label>
                                            <input type="text" name="destino" class="form-control" required placeholder="Ej: Bogotá">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="text-secondary small font-weight-bold">Fecha de Ida *</label>
                                            <input type="date" name="fecha_viaje" id="fecha_viaje_id" class="form-control" required onchange="calcularHoteles()">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="text-secondary small font-weight-bold">Jornada de Ida *</label>
                                            <select name="jornada_ida" class="form-control" required>
                                                <option value="">Seleccione...</option>
                                                <option value="Mañana">🌅 Mañana</option>
                                                <option value="Tarde">☀️ Tarde</option>
                                                <option value="Noche">🌙 Noche</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row border-left-primary bg-light pt-2 mx-1 mb-3 rounded" id="row-regreso" style="display: none;">
                                        <div class="col-md-6 mb-3">
                                            <label class="text-primary font-weight-bold small">Fecha de Regreso *</label>
                                            <input type="date" name="fecha_regreso" id="input-regreso" class="form-control" onchange="calcularHoteles()">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="text-primary font-weight-bold small">Jornada de Regreso *</label>
                                            <select name="jornada_regreso" id="input-jornada-regreso" class="form-control">
                                                <option value="">Seleccione...</option>
                                                <option value="Mañana">🌅 Mañana</option>
                                                <option value="Tarde">☀️ Tarde</option>
                                                <option value="Noche">🌙 Noche</option>
                                            </select>
                                        </div>
                                    </div>

                                    <h6 class="font-weight-bold text-primary mb-3 border-bottom pb-2 mt-4">
                                        <i class="fas fa-hotel mr-1"></i> Detalles de Hospedaje
                                    </h6>
                                    <div class="form-group mb-4">
                                        <input type="hidden" name="hospedaje" id="hospedaje_final">
                                        
                                        <div id="contenedor-hoteles" class="p-3 bg-light rounded border">
                                            <small class="text-muted"><i class="fas fa-info-circle"></i> Selecciona primero las fechas de ida y regreso para poder asignar las ciudades de hospedaje.</small>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="text-secondary small font-weight-bold">Motivo del Viaje *</label>
                                        <textarea name="descripcion" class="form-control" rows="3" placeholder="Describe el propósito del viaje..." required></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-block font-weight-bold mt-4 shadow-sm">
                                        ENVIAR SOLICITUD <i class="fas fa-paper-plane ml-1"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-7 col-lg-6">
                        
                        <div class="card shadow mb-4 border-left-secondary">
                            <div class="card-header py-3 bg-white"><h6 class="m-0 font-weight-bold text-secondary">Mis Solicitudes Recientes</h6></div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-sm">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Pasajero</th>
                                                <th>Ruta</th>
                                                <th>Fechas y Hotel</th>
                                                <th>Tipo</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($misTickets as $t)
                                            <tr>
                                                <td class="align-middle border-right">
                                                    <span class="font-weight-bold text-primary">{{ $t->beneficiario_nombre ?? 'N/A' }}</span><br>
                                                    <small class="text-muted">CC: {{ $t->beneficiario_cedula ?? 'N/A' }}</small>
                                                </td>

                                                <td class="align-middle">
                                                    {{ $t->origen }} <i class="fas fa-arrow-right text-muted mx-1"></i> {{ $t->destino }}
                                                </td>
                                                
                                                <td class="align-middle text-nowrap">
                                                    <div class="small"><strong>Ida:</strong> {{ $t->fecha_viaje }} <span class="text-muted">({{ $t->jornada_ida ?? 'N/A' }})</span></div>
                                                    @if($t->fecha_regreso)
                                                        <div class="small text-danger"><strong>Regreso:</strong> {{ $t->fecha_regreso }} <span class="text-muted">({{ $t->jornada_regreso ?? 'N/A' }})</span></div>
                                                    @endif
                                                    @if($t->hospedaje)
                                                        <div class="small mt-1 text-info font-weight-bold" title="{{ $t->hospedaje }}" style="cursor:help;">
                                                            <i class="fas fa-bed"></i> Ver Noches
                                                        </div>
                                                    @endif
                                                </td>

                                                <td class="align-middle text-center">
                                                    @if($t->tipo_viaje == 'Ida y Vuelta' || $t->tipo_viaje == 'redondo')
                                                        <span class="badge badge-info px-2 py-1">Ida y Vuelta</span>
                                                    @else
                                                        <span class="badge badge-secondary px-2 py-1">Solo Ida</span>
                                                    @endif
                                                </td>

                                                <td class="align-middle text-center">
                                                    @if($t->estado == 2) <span class="badge badge-warning px-2 py-1">Pendiente</span>
                                                    @elseif($t->estado == 1) <span class="badge badge-success px-2 py-1">Aprobado</span>
                                                    @else <span class="badge badge-danger px-2 py-1">Rechazado</span> @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-center mt-3">{{ $misTickets->links() }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow mb-4 border-left-info bg-white">
                            <div class="card-body">
                                <h6 class="font-weight-bold text-info mb-3 text-uppercase">
                                    <i class="fas fa-info-circle mr-2"></i>Anticipos y Legalización
                                </h6>
                                <p class="text-gray-700 text-justify mb-3 small">
                                    El colaborador debe solicitar un anticipo de dinero para cubrir los gastos de viaje asociados a la alimentación y transporte. Este trámite debe gestionarse en el momento en que se efectúa la solicitud de reserva de alojamiento y compra de tiquete. 
                                </p>
                                <div class="mb-3 text-center">
                                    <span class="badge badge-info px-3 py-2 shadow-sm" style="font-size: 0.85rem;">
                                        <i class="fas fa-clock mr-1"></i> Tiempo límite: 5 días hábiles antes del viaje
                                    </span>
                                </div>
                                <hr class="sidebar-divider">
                                <h6 class="font-weight-bold text-secondary mb-2 small text-uppercase">
                                    <i class="fas fa-file-invoice-dollar mr-1"></i> Legalización de Gastos
                                </h6>
                                <p class="text-gray-700 text-justify mb-3 small">
                                    Este reporte deberá realizarse a más tardar <strong>tres (3) días hábiles</strong> contados a partir del regreso del viaje.
                                </p>
                                <div class="alert alert-warning border-left-warning shadow-sm py-2 mb-3" role="alert">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-exclamation-triangle mr-2 text-warning"></i> 
                                        <strong class="text-dark small">Aviso Importante</strong>
                                    </div>
                                    <span class="text-dark" style="font-size: 0.8rem;">
                                        Todo anticipo no legalizado dentro de los tiempos previstos y que al cierre de mes tenga una antigüedad superior a 15 días, será reportado a nómina para su respectivo descuento.
                                    </span>
                                </div>
                                <p class="text-right text-muted mb-0 font-italic" style="font-size: 0.7rem;">
                                    <i class="fas fa-book mr-1"></i> <strong>Fuente: Política de control del gasto del sistema de gestión integral SGI-PS4-PO1.</strong>
                                </p>
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
        const rowRegreso = document.getElementById('row-regreso');
        const inputRegreso = document.getElementById('input-regreso');
        const inputJornadaRegreso = document.getElementById('input-jornada-regreso');
        const lblIda = document.getElementById('lbl-ida');
        const lblVuelta = document.getElementById('lbl-vuelta');
        const inputReal = document.getElementById('input_real_tipo');

        if (esRedondo) {
            rowRegreso.style.display = 'flex';
            inputRegreso.required = true; 
            inputJornadaRegreso.required = true;
            lblIda.classList.remove('active');
            lblVuelta.classList.add('active');
            inputReal.value = 'Ida y Vuelta'; 
        } else {
            rowRegreso.style.display = 'none';
            inputRegreso.required = false; 
            inputRegreso.value = ''; 
            inputJornadaRegreso.required = false;
            inputJornadaRegreso.value = '';
            lblIda.classList.add('active');
            lblVuelta.classList.remove('active');
            inputReal.value = 'Solo Ida';
        }
        
        calcularHoteles();
    }

    // ====================================================================
    // MAGIA DE LOS HOTELES: Dibuja las cajas según las fechas elegidas
    // ====================================================================
    function calcularHoteles() {
        const tipoViaje = document.getElementById('input_real_tipo').value;
        const fechaIda = document.getElementById('fecha_viaje_id').value;
        const fechaRegreso = document.getElementById('input-regreso').value;
        const contenedor = document.getElementById('contenedor-hoteles');
        const hiddenHospedaje = document.getElementById('hospedaje_final');

        contenedor.innerHTML = '<small class="text-muted"><i class="fas fa-info-circle"></i> Selecciona primero las fechas de ida y regreso para poder asignar las ciudades de hospedaje.</small>';
        hiddenHospedaje.value = '';

        if (!fechaIda) return;

        let fechas = [];
        
        if (tipoViaje === 'Ida y Vuelta') {
            if (!fechaRegreso) return; 
            
            let partesIda = fechaIda.split('-');
            let partesReg = fechaRegreso.split('-');
            
            let inicio = new Date(partesIda[0], partesIda[1] - 1, partesIda[2]);
            let fin = new Date(partesReg[0], partesReg[1] - 1, partesReg[2]);
            
            if (fin > inicio) {
                let actual = new Date(inicio);
                while (actual < fin) {
                    fechas.push(new Date(actual));
                    actual.setDate(actual.getDate() + 1);
                }
            } else {
                contenedor.innerHTML = '<small class="text-danger"><i class="fas fa-exclamation-triangle"></i> La fecha de regreso debe ser mayor a la de ida.</small>';
                return;
            }
        } else {
            let partesIda = fechaIda.split('-');
            fechas.push(new Date(partesIda[0], partesIda[1] - 1, partesIda[2]));
        }

        if(fechas.length > 0) {
            // TEXTO PRINCIPAL MÁS DIRECTO
            let html = '<label class="text-secondary small font-weight-bold mb-2">Especifique la ciudad de hospedaje por fecha:</label>';
            
            fechas.forEach((fecha) => {
                let dia = String(fecha.getDate()).padStart(2, '0');
                let mes = String(fecha.getMonth() + 1).padStart(2, '0');
                let anio = fecha.getFullYear();
                let dateStr = `${dia}/${mes}/${anio}`;
                
                html += `
                <div class="input-group input-group-sm mb-2 shadow-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-primary text-white font-weight-bold" style="width: 110px; font-size: 0.8rem;">
                            <i class="far fa-calendar-alt mr-1"></i> ${dateStr}
                        </span>
                    </div>
                    <input type="text" class="form-control hotel-input" data-fecha="${dateStr}" placeholder="Ciudad" onkeyup="actualizarHospedaje()">
                </div>`;
            });
            contenedor.innerHTML = html;
        }
    }

    function actualizarHospedaje() {
        let inputs = document.querySelectorAll('.hotel-input');
        let resultado = [];
        inputs.forEach(input => {
            if(input.value.trim() !== '') {
                // Guarda en la base de datos de manera limpia: "24/02/2026: Bogotá"
                resultado.push(input.dataset.fecha + ': ' + input.value.trim());
            }
        });
        
        document.getElementById('hospedaje_final').value = resultado.join(' | '); 
    }
</script>

</body>
</html>