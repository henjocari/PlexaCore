<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/favicon.png') }}">
    <title>Administración de Hotel</title>

    <!-- Fonts y estilos -->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,900" rel="stylesheet">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

    <style>
        .habitacion-card {
            flex: 1 1 calc(25% - 1rem);
            min-width: 250px;
            box-sizing: border-box;
        }

        .cajas {
            width: 100%;
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .card {
            position: relative;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            z-index: 1;
            overflow: visible !important;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            background-color: #f8f9fa;
            z-index: 1000;
        }
        
        .card:has(.lista-conductores[style*="display: block"]) {
            z-index: 1001 !important;
        }

        .card-Ocupada {
            cursor: not-allowed;
            opacity: 0.7;
        }

        .lista-conductores {
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 9999 !important;
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 6px;
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.25);
            display: none;
        }

        .conductor-item {
            padding: 8px 12px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .conductor-item:hover {
            background-color: #e9ecef;
        }

        .conductor-item strong {
            color: #1a202c;
        }

        .conductor-item .text-muted {
            font-size: 0.9em;
        }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        @include('layouts.menu')

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                @include('layouts.cabecera')

                <div class="container-fluid">
                    <h2 class="text-center mb-4">Gestión de Habitaciones</h2>
                    {{ now()->format('Y-m-d H:i:s') }}
 
                    

                    <!-- Mensajes -->
                    @if (session('success'))
                        <div class="alert alert-success text-center">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger text-center">{{ session('error') }}</div>
                    @endif

                    <div class="cajas">
                        @foreach ($habitaciones as $habitacion)
                            <div class="card card-{{ $habitacion['estado'] ?? '—' }} shadow p-3 habitacion-card">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Habitación {{ $habitacion['numero'] ?? '—' }}</h5>

                                    <p class="estado {{ ($habitacion['estado'] ?? '') === 'Disponible' ? 'text-success' : 'text-danger' }}">
                                        {{ $habitacion['estado'] ?? '—' }}
                                    </p>

                                    @if ($habitacion->estado === 'Disponible')
                                        <div class="mb-2 position-relative">
                                            <input type="text" 
                                                id="buscadorConductor{{ $habitacion->numero }}" 
                                                class="form-control"
                                                placeholder="Escriba cédula o nombre del conductor..." 
                                                autocomplete="off">

                                            <div id="listaConductores{{ $habitacion->numero }}" class="lista-conductores">
                                                @foreach ($conductores->whereNotIn('cedula', $habitaciones->pluck('conductor')->filter()) as $conductor)
                                                    <div class="conductor-item"
                                                        data-cedula="{{ $conductor->cedula }}"
                                                        data-nombre="{{ $conductor->nombre }} {{ $conductor->apellido }}">
                                                        <strong>{{ $conductor->nombre }} {{ $conductor->apellido }}</strong>
                                                        <div class="text-muted">{{ $conductor->cedula }}</div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <button type="button" 
                                            class="btn btn-primary w-100 mt-2 btn-asignar" 
                                            data-numero="{{ $habitacion->numero }}">
                                            Asignar Habitación
                                        </button>
                                    @else
                                        <div class="alert alert-info mt-2">
                                            Ocupada por: <strong>{{ $habitacion->hconductor->nombre ?? '' }} {{ $habitacion->hconductor->apellido ?? '' }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <hr class="my-5">

                    <h2 class="text-center mb-4">Inventario de Habitaciones</h2>
                    <div class="table-responsive">
                        <table class="table table-striped text-center" id="tablaInventario">
                            <thead class="table-dark">
                                <tr>
                                    <th># Habitación</th>
                                    <th>Conductor</th>
                                    <th>Estado</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($habitaciones as $habitacion)
                                    <tr>
                                        <td>{{ $habitacion['numero'] ?? '—' }}</td>
                                        <td>
                                            @if($habitacion->hconductor)
                                                {{ $habitacion->hconductor->nombre }} {{ $habitacion->hconductor->apellido }}
                                                {{--<small class="text-muted d-block">{{ $habitacion->hconductor->cedula }}</small>--}}
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                                {{ $habitacion['estado'] ?? '—' }}
                                            
                                        </td>
                                        <td>
                                            @if ($habitacion->estado === 'Ocupada')
                                                <button class="btn btn-danger btn-sm" 
                                                    onclick="actualizarEstado('{{ $habitacion->numero }}', 'Disponible')">
                                                    Desasignar
                                                </button>
                                            @else
                                                <button class="btn btn-secondary btn-sm" disabled>Disponible</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @include('layouts.pie')
        </div>
    </div>

    <a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>

    <!-- JS -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Función para desasignar habitación
        function actualizarEstado(numero, nuevoEstado) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: `¿Deseas cambiar el estado de la habitación ${numero} a ${nuevoEstado}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, desasignar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/habitaciones/${numero}`, {
                        method: "PUT",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            estado: nuevoEstado,
                            conductor: null
                        })
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire("Actualizado", data.message, "success").then(() => location.reload());
                        } else {
                            Swal.fire("Error", data.error || "No se pudo actualizar.", "error");
                        }
                    })
                    .catch(() => Swal.fire("Error", "Error de conexión con el servidor.", "error"));
                }
            });
        }

        document.addEventListener("DOMContentLoaded", () => {
            // Asignar habitación
            document.querySelectorAll(".btn-asignar").forEach(boton => {
                boton.addEventListener("click", () => {
                    const numero = boton.dataset.numero;
                    const input = document.getElementById("buscadorConductor" + numero);
                    const cedula = input.getAttribute("data-cedula");
                    const nombreConductor = input.value;

                    if (!cedula) {
                        Swal.fire("Atención", "Seleccione un conductor antes de asignar.", "warning");
                        return;
                    }

                    Swal.fire({
                        title: '¿Confirmar asignación?',
                        text: `¿Asignar habitación ${numero} a ${nombreConductor}?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Sí, asignar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(`/habitaciones/${numero}`, {
                                method: "PUT",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                },
                                body: JSON.stringify({
                                    estado: "Ocupada",
                                    conductor: cedula
                                })
                            })
                            .then(r => r.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire("Éxito", data.message, "success").then(() => location.reload());
                                } else {
                                    Swal.fire("Error", data.error || "No se pudo asignar la habitación.", "error");
                                }
                            })
                            .catch(() => Swal.fire("Error", "Error de conexión con el servidor.", "error"));
                        }
                    });
                });
            });

            // Sistema de búsqueda de conductores
            document.querySelectorAll('[id^="buscadorConductor"]').forEach(input => {
                const numero = input.id.replace("buscadorConductor", "");
                const lista = document.getElementById("listaConductores" + numero);
                const items = lista.querySelectorAll(".conductor-item");

                // Filtrar conductores mientras se escribe
                input.addEventListener("input", () => {
                    const texto = input.value.toLowerCase().trim();
                    let visible = false;

                    items.forEach(item => {
                        const nombre = item.dataset.nombre.toLowerCase();
                        const cedula = item.dataset.cedula.toLowerCase();
                        const coincide = nombre.includes(texto) || cedula.includes(texto);
                        
                        item.style.display = coincide ? "block" : "none";
                        if (coincide) visible = true;
                    });

                    lista.style.display = visible ? "block" : "none";
                });

                // Seleccionar conductor
                items.forEach(item => {
                    item.addEventListener("click", () => {
                        input.value = item.dataset.nombre;
                        input.setAttribute("data-cedula", item.dataset.cedula);
                        lista.style.display = "none";
                    });
                });

                // Cerrar lista al hacer clic fuera
                document.addEventListener("click", e => {
                    if (!lista.contains(e.target) && e.target !== input) {
                        lista.style.display = "none";
                    }
                });
            });
        });
    </script>
</body>
</html>