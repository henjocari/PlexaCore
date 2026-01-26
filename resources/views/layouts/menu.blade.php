<!-- Sidebar -->
<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color: #378E77 ">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/index') }}">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('img/Logo_plexa.svg') }}" alt="Logo"  width="100%">
            </div>
        </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item active">
    @if(in_array('Dashboard', session('modulos_permitidos', [])))
        <a class="nav-link" href="{{ url('/dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    @endif
    </li>
    <li class="nav-item active">
    @if(in_array('Usuarios', session('modulos_permitidos', [])))
        <a class="nav-link" href="{{ url('/usuarios') }}">
            <i class="fas fa-users"></i>
            <span>Usuarios</span></a>
    @endif
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Seccion 
    </div>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseHotel"
            aria-expanded="true" aria-controls="collapseHotel">
            <i class="fas fa-fw fa-hotel"></i>
            <span>Gestión de Hotel</span>
        </a>
        <div id="collapseHotel" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Navegación:</h6>
                @if(in_array('Hotel', session('modulos_permitidos', [])))
                    <a class="collapse-item" href="{{ url('/hotel') }}">
                        <i class="fas fa-bed"></i> Hotel</a>
                @endif
                @if(in_array('Historial Habitacion', session('modulos_permitidos', [])))
                    <a class="collapse-item" href="{{ route('historial.habitaciones') }}">
                        <i class="fas fa-history"></i> Historial Habitacion
                    </a>
                @endif
            </div>
        </div>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Precios y Multimedia
    </div>

    <li class="nav-item">
    @if(in_array('Precio GLP', session('modulos_permitidos', [])))
        <a class="nav-link" href="{{ url('/precio-glp') }}">
            <i class="fas fa-fw fa-gas-pump"></i>
            <span>Precio GLP</span>
        </a>
    @endif

        <a class="nav-link" href="{{ url('/carrusel') }}">
            <i class="fas fa-fw fa-images"></i>
            <span>Carrusel</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    @if(in_array('Tabla Conductores', session('modulos_permitidos', [])))
    <li class="nav-item">
        <a class="nav-link" href="{{ url('/tablas') }}">
            <i class="fas fa-fw fa-table"></i>
            <span>Tabla de Conductores</span>
        </a>
    </li>
    <hr class="sidebar-divider">
    @endif

    <div class="sidebar-heading">
        Soporte
    </div>

    <li class="nav-item {{ Request::routeIs('tickets.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('tickets.index') }}">
            <i class="fas fa-fw fa-ticket-alt"></i>
            <span>Tickets</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->