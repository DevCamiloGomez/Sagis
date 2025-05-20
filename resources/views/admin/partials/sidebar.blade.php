<!-- Main Sidebar -->
<aside class="main-sidebar sidebar-light-danger elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link navbar-danger text-light">
        <span class="logoMini ">
            SAGIS
        </span>
        <span class="brand-text font-weight-light ">{{ Session::get('role_admin')->name }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset(Auth::guard('admin')->user()->person->fullAsset()) }}" class="img-circle img-fluid"
                    alt="User Image">
            </div>
            <div class="info">
                <a href="{{ route('admin.settings') }}" class="d-block">{{ Auth::guard('admin')->user()->person->fullName() }}</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search" data-highlight-class='text-dark'
                data-not-found-text='No encontrado'>
                <input class="form-control form-control-sidebar " type="search" placeholder="Buscar"
                    aria-label="Search" highlightClass="text-dark">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->

                     <li class="nav-item">
                        <a href="{{ route('admin.home') }}" class="nav-link">
                            <em class="nav-icon fas fa-tachometer-alt"></em>
                            <p>
                                Dashboard
    
                            </p>
                        </a>
                    </li>
                <li class="nav-item">
                    <a href="{{ route('admin.graduates.index') }}" class="nav-link">
                        <em class="nav-icon fas fa-user"></em>
                        <p>
                            Graduados

                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.posts.index') }}" class="nav-link">
                        <em class="nav-icon fas fa-th"></em>
                        <p>
                            Contenidos informativos

                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.carousel.index') }}" class="nav-link">
                        <em class="nav-icon fas fa-images"></em>
                        <p>
                            Carrusel
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <em class="nav-icon fas fa-chart-pie"></em>
                        <p>
                            Reportes
                            <em class="right fas fa-angle-left"></em>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.reports.statistics') }}" class="nav-link">
                                <em class="far fa-circle nav-icon"></em>
                                <p>Estadísticas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.reports.graduates') }}" class="nav-link">
                                <em class="far fa-circle nav-icon"></em>
                                <p>Reportes</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.settings') }}" class="nav-link">
                        <em class="nav-icon fas fa-cog"></em>
                        <p>
                            Ajustes

                        </p>
                    </a>
                </li>


            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
<!-- ./Main Sidebar -->
