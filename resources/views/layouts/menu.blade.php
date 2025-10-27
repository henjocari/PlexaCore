<!-- Sidebar -->
<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color: #378E77 ">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/index') }}">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('img/Logo_plexa.svg') }}" alt="Logo"  width="100%">
            <!-- <i class="fas fa-laugh-wink"></i> -->
        </div>
        <!-- <div class="sidebar-brand-text mx-3">INICIO</div> -->
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
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

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <!-- <div class="sidebar-heading">
        Interface
    </div>-->

    <!-- Nav Item - Pages Collapse Menu -->
    <!-- <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-cog"></i>
            <span>Components</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Custom Components:</h6>
                <a class="collapse-item" href="buttons.html">Buttons</a>
                <a class="collapse-item" href="cards.html">Cards</a>
            </div>
        </div>
    </li>-->

    <!-- Nav Item - Utilities Collapse Menu -->
    <!-- <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
            aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-wrench"></i>
            <span>Utilities</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Custom Utilities:</h6>
                <a class="collapse-item" href="utilities-color.html">Colors</a>
                <a class="collapse-item" href="utilities-border.html">Borders</a>
                <a class="collapse-item" href="utilities-animation.html">Animations</a>
                <a class="collapse-item" href="utilities-other.html">Other</a>
            </div>
        </div>
    </li>-->

    <!-- Divider -->
    <!--<hr class="sidebar-divider">-->

    <!-- Heading -->
    <div class="sidebar-heading">
        Seccion 
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
            aria-expanded="true" aria-controls="collapsePages">
            <i class="fas fa-fw fa-folder"></i>
            <span>Paginas</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Navegacion:</h6>
                <!--<a class="collapse-item" href="login.html">Inicia Sesion</a>-->
                <!--<a class="collapse-item" href="forgot-password.html">Forgot Password</a>
                <div class="collapse-divider"></div>
                <h6 class="collapse-header">Other Pages:</h6>
                <a class="collapse-item" href="404.html">404 Page</a>-->
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

    <!-- Nav Item - Charts -->
    <!--<li class="nav-item">
        <a class="nav-link" href="charts.html">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Graficos</span></a>
    </li>-->

    <!-- Nav Item - Tables -->
    @if(in_array('Tabla Conductores', session('modulos_permitidos', [])))
    <li class="nav-item">
        <a class="nav-link" href="{{ url('/tablas') }}">
            <i class="fas fa-fw fa-table"></i>
            <span>Tabla de Conductores</span></a>
    </li>
    @endif

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->