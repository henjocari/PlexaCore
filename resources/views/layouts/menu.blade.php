<!-- Sidebar -->
<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color: #378E77">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/index') }}">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('img/Logo_plexa.svg') }}" alt="Logo" width="100%">
        </div>
    </a>

    <hr class="sidebar-divider my-0">

    @php
        $misModulos = session('modulos_permitidos', []);
    @endphp

    {{-- DASHBOARD --}}
    @if(in_array('Dashboard', $misModulos))
        <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('/dashboard') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>
    @endif

    {{-- USUARIOS --}}
    @if(in_array('Usuarios', $misModulos) || in_array('Roles', $misModulos))
        <li class="nav-item">
            <a class="nav-link" href="{{ url('/usuarios') }}">
                <i class="fas fa-fw fa-users"></i>
                <span>Usuarios</span>
            </a>
        </li>
    @endif

    {{-- GESTIÓN DE HOTEL --}}
    @if(in_array('Hotel', $misModulos) || in_array('Historial Habitacion', $misModulos))
        <hr class="sidebar-divider">
        <div class="sidebar-heading">Sección</div>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseHotel"
                aria-expanded="true" aria-controls="collapseHotel">
                <i class="fas fa-fw fa-hotel"></i>
                <span>Gestión de Hotel</span>
            </a>
            <div id="collapseHotel" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Navegación:</h6>
                    
                    @if(in_array('Hotel', $misModulos))
                        <a class="collapse-item" href="{{ url('/hotel') }}">
                            <i class="fas fa-bed mr-2 text-gray-500"></i> Hotel
                        </a>
                    @endif

                    @if(in_array('Historial Habitacion', $misModulos))
                        <a class="collapse-item" href="{{ route('historial.habitaciones') }}">
                            <i class="fas fa-history mr-2 text-gray-500"></i> Historial
                        </a>
                    @endif
                </div>
            </div>
        </li>
    @endif

    {{-- ADMINISTRACIÓN --}}
    @if(in_array('Precio GLP', $misModulos) || in_array('Carrusel', $misModulos) || in_array('Tabla Conductores', $misModulos) || true)
        <hr class="sidebar-divider">
        <div class="sidebar-heading">Configuración</div>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAdmin"
                aria-expanded="true" aria-controls="collapseAdmin">
                <i class="fas fa-fw fa-cogs"></i>
                <span>Administración</span>
            </a>
            <div id="collapseAdmin" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Opciones Admin:</h6>
                    
                    @if(in_array('Precio GLP', $misModulos))
                        <a class="collapse-item" href="{{ url('/precio-glp') }}">
                            <i class="fas fa-gas-pump mr-2 text-gray-500"></i> Precio GLP
                        </a>
                    @endif

                    @if(in_array('Carrusel', $misModulos))
                        <a class="collapse-item" href="{{ url('/carrusel') }}">
                            <i class="fas fa-images mr-2 text-gray-500"></i> Carrusel
                        </a>
                    @endif

                    <a class="collapse-item" href="{{ url('/tratamientodedatos') }}">
                        <i class="fas fa-file-signature mr-2 text-gray-500"></i> Tratamiento de Datos
                    </a>

                    @if(in_array('Tabla Conductores', $misModulos))
                        <a class="collapse-item" href="{{ url('/tablas') }}">
                            <i class="fas fa-table mr-2 text-gray-500"></i> Conductores
                        </a>
                    @endif
                </div>
            </div>
        </li>
    @endif

    {{-- LOGÍSTICA --}}
    @if(in_array('Solicitar Viaje', $misModulos) || in_array('Gestion Viajes', $misModulos))
        <hr class="sidebar-divider">
        <div class="sidebar-heading">Logística</div>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseViajes"
                aria-expanded="true" aria-controls="collapseViajes">
                <i class="fas fa-fw fa-plane-departure"></i>
                <span>Gestión de Viajes</span>
            </a>
            <div id="collapseViajes" class="collapse" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Solicitudes:</h6>
                    
                    @if(in_array('Solicitar Viaje', $misModulos))
                        <a class="collapse-item {{ request()->routeIs('tickets.solicitar') ? 'active' : '' }}" 
                           href="{{ route('tickets.solicitar') }}">
                           <i class="fas fa-plus-circle mr-2 text-success"></i> Solicitar Viaje
                        </a>
                    @endif

                    @if(in_array('Gestion Viajes', $misModulos))
                        <a class="collapse-item {{ request()->routeIs('tickets.gestion') ? 'active' : '' }}" 
                           href="{{ route('tickets.gestion') }}">
                           <i class="fas fa-clipboard-list mr-2 text-primary"></i> Viajes Solicitados
                        </a>
                    @endif
                </div>
            </div>
        </li>
    @endif

    {{-- MEDIO AMBIENTE --}}
    @if(in_array('Hotel', $misModulos) || in_array('Historial Habitacion', $misModulos))
        <hr class="sidebar-divider">
        <div class="sidebar-heading">Medio Ambiente</div>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAmbiental"
                aria-expanded="true" aria-controls="collapseAmbiental">
                <i class="fas fa-fw fa-leaf"></i>
                <span>Gestión ambiental</span>
            </a>
            <div id="collapseAmbiental" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Formatos:</h6>
                    
                    @if(in_array('Hotel', $misModulos))
                        <a class="collapse-item" href="{{ route('inspeccion.quimica') }}">
                            <i class="fas fa-flask mr-2 text-gray-500"></i> Inspección Química
                        </a>
                    @endif

                    {{-- ✅ NUEVO: PLAN DE SEGUIMIENTO - SOSTENIBILIDAD --}}
                    @if(in_array('Hotel', $misModulos))
                        <a class="collapse-item {{ request()->routeIs('plandeprocedimiento.index') ? 'active' : '' }}" 
                           href="{{ route('plandeprocedimiento.index') }}">
                           <i class="fas fa-calendar-check mr-2 text-success"></i> Plan de Seguimiento
                        </a>
                    @endif
                </div>
            </div>
        </li>
    @endif

    {{-- MÓDULO MANIFIESTO --}}
    @if(in_array('Manifiesto', $misModulos) || in_array('Manifiestos', $misModulos) || in_array('Hotel', $misModulos) || in_array('Historial Habitacion', $misModulos))
        <hr class="sidebar-divider">
        <div class="sidebar-heading">Gestion Logistica</div>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseManifiesto"
                aria-expanded="true" aria-controls="collapseManifiesto">
                
                {{-- OPCIÓN A: Ícono FontAwesome alusivo a manifiestos y planillas de carga --}}
                <i class="fas fa-fw fa-file-invoice"></i>

                {{-- OPCIÓN B: Si deseas usar una imagen propia, descomenta la siguiente línea y ajusta la ruta --}}
                {{-- <img src="{{ asset('img/manifiesto_icon.svg') }}" alt="Manifiesto" width="18" class="mr-2"> --}}

                <span>Manifiesto</span>
            </a>

            <div id="collapseManifiesto" class="collapse" aria-labelledby="headingManifiesto" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Gestión de Manifiestos:</h6>
                    
                    {{-- Enlace principal a la vista de manifiesto --}}
                    <a class="collapse-item {{ request()->is('manifiestos') ? 'active' : '' }}" href="{{ url('/manifiestos') }}">
                        <i class="fas fa-clipboard-list mr-2 text-gray-500"></i> Validacion de Manifiestos
                    </a>

                    {{-- Enlace secundario (Opcional, para crear un nuevo manifiesto) --}}
                    <a class="collapse-item {{ request()->is('manifiestos/crear') ? 'active' : '' }}" href="{{ url('/manifiestos/crear') }}">
                        <i class="fas fa-plus-circle mr-2 text-success"></i> Silogtran
                    </a>
                </div>
            </div>
        </li>
    @endif

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->