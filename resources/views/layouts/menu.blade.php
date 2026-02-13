<!-- Sidebar -->
<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color: #378E77">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/index') }}">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('img/Logo_plexa.svg') }}" alt="Logo" width="100%">
        </div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item active">
        @if(session('modulos_permitidos') && in_array('Dashboard', session('modulos_permitidos', [])))
            <a class="nav-link" href="{{ url('/dashboard') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        @endif
    </li>

    <li class="nav-item active">
        @if(session('modulos_permitidos') && in_array('Usuarios', session('modulos_permitidos', [])))
            <a class="nav-link" href="{{ url('/usuarios') }}">
                <i class="fas fa-users"></i>
                <span>Usuarios</span>
            </a>
        @endif
    </li>

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
                
                @if(session('modulos_permitidos') && in_array('Hotel', session('modulos_permitidos', [])))
                    <a class="collapse-item" href="{{ url('/hotel') }}">
                        <i class="fas fa-bed mr-2 text-gray-500"></i> Hotel
                    </a>
                @endif

                @if(session('modulos_permitidos') && in_array('Historial Habitacion', session('modulos_permitidos', [])))
                    <a class="collapse-item" href="{{ route('historial.habitaciones') }}">
                        <i class="fas fa-history mr-2 text-gray-500"></i> Historial
                    </a>
                @endif
            </div>
        </div>
    </li>

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
                
                @if(session('modulos_permitidos') && in_array('Precio GLP', session('modulos_permitidos', [])))
                    <a class="collapse-item" href="{{ url('/precio-glp') }}">
                        <i class="fas fa-gas-pump mr-2 text-gray-500"></i> Precio GLP
                    </a>
                @endif

                <a class="collapse-item" href="{{ url('/carrusel') }}">
                    <i class="fas fa-images mr-2 text-gray-500"></i> Carrusel
                </a>

                @if(session('modulos_permitidos') && in_array('Tabla Conductores', session('modulos_permitidos', [])))
                    <a class="collapse-item" href="{{ url('/tablas') }}">
                        <i class="fas fa-table mr-2 text-gray-500"></i> Conductores
                    </a>
                @endif
            </div>
        </div>
    </li>

    <hr class="sidebar-divider">
    <div class="sidebar-heading">Logística</div>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseViajes"
            aria-expanded="true" aria-controls="collapseViajes">
            <i class="fas fa-fw fa-plane-departure"></i>
            <span>Gestión de Viajes</span>
        </a>
        <div id="collapseViajes" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Solicitudes:</h6>
                
                <a class="collapse-item {{ request()->routeIs('tickets.solicitar') ? 'active' : '' }}" 
                   href="{{ route('tickets.solicitar') }}">
                   <i class="fas fa-plus-circle mr-2 text-success"></i> Solicitar Viaje
                </a>

                @if(Auth::check() && (Auth::user()->rol == 1 || Auth::user()->rol == 2))
                    <a class="collapse-item {{ request()->routeIs('tickets.gestion') ? 'active' : '' }}" 
                       href="{{ route('tickets.gestion') }}">
                       <i class="fas fa-clipboard-list mr-2 text-primary"></i> Viajes Solicitados
                    </a>
                @endif
            </div>
        </div>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->