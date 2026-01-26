<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Plexa Core - Tickets</title>
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/favicon.png') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .fondo-plexa {
            background-image: url("{{ asset('img/Logo_plexa.svg') }}");
            background-position: center center;
            background-repeat: no-repeat;
            background-size: contain; 
            background-color: #ffffff; 
            min-height: 100vh;
            padding: 2rem;
        }
        @media (max-width: 768px) {
            .fondo-plexa { background-size: 80%; padding: 1rem; }
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

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        @if($esPapelera)
                            <h1 class="h3 mb-0 text-danger px-3 py-2 rounded shadow-sm" style="background-color: rgba(255,255,255,0.95);">
                                <i class="fas fa-trash-alt mr-2"></i> Papelera de Reciclaje
                            </h1>
                            <a href="{{ route('tickets.index') }}" class="btn btn-secondary shadow-sm">
                                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Volver a Lista
                            </a>
                        @else
                            <h1 class="h3 mb-0 text-gray-800 px-3 py-2 rounded shadow-sm" style="background-color: rgba(255,255,255,0.95);">
                                <i class="fas fa-ticket-alt mr-2 text-primary"></i> Gestión de Tickets
                            </h1>
                            <a href="{{ route('tickets.papelera') }}" class="btn btn-danger shadow-sm">
                                <i class="fas fa-trash fa-sm text-white-50"></i> Ver Papelera
                            </a>
                        @endif
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success border-left-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger border-left-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                    @endif

                    <div class="row">
                        @if(!$esPapelera)
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4 border-bottom-primary">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Nuevo Envío</h6>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data" id="formTicket">
                                        @csrf
                                        <div class="form-group">
                                            <label class="font-weight-bold small text-primary">1. TIKETE</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" name="archivo_tikete" required accept=".pdf,.jpg,.jpeg,.png">
                                                <label class="custom-file-label">Elegir archivo...</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="font-weight-bold small text-primary">2. SOPORTE</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" name="archivo_soporte" required accept=".pdf,.jpg,.jpeg,.png">
                                                <label class="custom-file-label">Elegir archivo...</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="font-weight-bold small text-primary">DESCRIPCIÓN</label>
                                            <textarea name="descripcion" class="form-control" rows="3"></textarea>
                                        </div>
                                        <hr>
                                        <button type="submit" class="btn btn-primary btn-block font-weight-bold">
                                            <i class="fas fa-paper-plane mr-2"></i> ENVIAR
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="{{ $esPapelera ? 'col-12' : 'col-xl-8 col-lg-7' }}">
                            <div class="card shadow mb-4 {{ $esPapelera ? 'border-bottom-danger' : '' }}">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold {{ $esPapelera ? 'text-danger' : 'text-primary' }}">
                                        {{ $esPapelera ? 'Tickets Eliminados' : 'Historial de Mis Tickets' }}
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Empleado</th>
                                                    <th>Detalle</th>
                                                    <th class="text-center">Ver</th>
                                                    <th>Fecha</th>
                                                    <th class="text-center">Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($tickets as $t)
                                                    <tr style="{{ $esPapelera ? 'background-color: #fff5f5;' : '' }}">
                                                        <td class="font-weight-bold">#{{ $t->id }}</td>
                                                        <td><div class="small font-weight-bold">{{ $t->nombre }} {{ $t->apellido }}</div></td>
                                                        <td class="small">{{ Str::limit($t->descripcion, 30) ?? '---' }}</td>
                                                        
                                                        <td class="text-center">
                                                            <div class="btn-group">
                                                                <a href="{{ asset('archivos_tickets/'.$t->archivo_tikete) }}" target="_blank" class="btn btn-info btn-sm"><i class="fas fa-file-invoice"></i></a>
                                                                <a href="{{ asset('archivos_tickets/'.$t->archivo_soporte) }}" target="_blank" class="btn btn-warning btn-sm"><i class="fas fa-file-alt"></i></a>
                                                            </div>
                                                        </td>
                                                        
                                                        <td class="small">{{ \Carbon\Carbon::parse($t->created_at)->format('d/m/Y') }}</td>

                                                        <td class="text-center">
                                                            @if($esPapelera)
                                                                <form action="{{ route('tickets.activar', $t->id) }}" method="POST" class="form-activar" style="display:inline;">
                                                                    @csrf @method('PATCH')
                                                                    <button type="submit" class="btn btn-success btn-sm" title="Restaurar Ticket">
                                                                        <i class="fas fa-trash-restore mr-1"></i> Restaurar
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <form action="{{ route('tickets.inactivar', $t->id) }}" method="POST" class="form-inactivar" style="display:inline;">
                                                                    @csrf @method('PATCH')
                                                                    <button type="submit" class="btn btn-danger btn-circle btn-sm" title="Mover a Papelera">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center py-4 text-gray-500">
                                                            <i class="fas {{ $esPapelera ? 'fa-trash' : 'fa-folder-open' }} mb-2 fa-2x"></i><br>
                                                            {{ $esPapelera ? 'La papelera está vacía.' : 'No tienes tickets activos.' }}
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-3 d-flex justify-content-center">{{ $tickets->links() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('layouts.pie')
        </div>
    </div>
    
    <a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>

    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    <script>
        $('.custom-file-input').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });

        $('#formTicket').on('submit', function() {
            let btn = $(this).find('button[type="submit"]');
            btn.html('<i class="fas fa-spinner fa-spin mr-2"></i> Subiendo...');
            btn.addClass('disabled');
            setTimeout(() => { btn.prop('disabled', true); }, 100);
        });

        $('.form-inactivar').on('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Mover a Papelera?',
                text: "El ticket desaparecerá de esta lista.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74a3b',
                confirmButtonText: 'Sí, borrar'
            }).then((r) => { if (r.isConfirmed) this.submit(); });
        });

        $('.form-activar').on('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Restaurar Ticket?',
                text: "El ticket volverá a la lista principal.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1cc88a',
                confirmButtonText: 'Sí, restaurar'
            }).then((r) => { if (r.isConfirmed) this.submit(); });
        });
    </script>
</body>
</html>