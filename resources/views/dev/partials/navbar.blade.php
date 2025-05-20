<!-- Navbar -->
<nav class="navbar navbar-expand-lg  navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('dev.home') }}">
            <img src="https://ingsistemas.cloud.ufps.edu.co/wp-content/uploads/2023/11/logo_ingsistemas_vertical_invertido.png"
                alt="" width="140px" height="120px" />
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ isActiveRoute('dev.home', 'active') }}" aria-current="page"
                        href="{{ route('dev.home') }}">INICIO</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ isActiveRoute('dev.notices', 'active') }}"
                        href="{{ route('dev.notices') }}">NOTICIAS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ isActiveRoute('dev.courses', 'active') }}"
                        href="{{ route('dev.courses') }}">CURSOS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ isActiveRoute('dev.events', 'active') }}"
                        href="{{ route('dev.events') }}">EVENTOS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ isActiveRoute('dev.gallery', 'active') }}"
                        href="{{ route('dev.gallery') }}">GALERIA</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ isActiveRoute('dev.videos', 'active') }}"
                        href="{{ route('dev.videos') }}">VIDEOS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://ww2.ufps.edu.co/universidad/egresados/2228">BOLSA
                        DE EMPLEO</a>
                </li>


                <li class="nav-item">
                    <a class="nav-link {{ isActiveRoute('a', 'active') }}" href="">ADMIN</a>
                </li>

            </ul>

            {{-- <template id="SiSesion"> --}}
            <ul class="navbar-nav ml-auto m-4">
                <li class="nav-item dropdown" style="list-style-type: none;">
                    <a class="nav-link dropdown-toggle link-dark" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Jarlin Fonseca
                    </a>
                    <ul class="dropdown-menu text-small " aria-labelledby="dropdownUser2">
                        <li><a class="dropdown-item" href="#">Mi perfil</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#">Salir</a></li>
                    </ul>
                </li>

                <div class="user">
                    <img src="{{ asset('img/imgUser.png') }}" width="70" height="70"
                        class="rounded-circle me-2">
                </div>

            </ul>
            {{-- </template> --}}
        </div>
    </div>
</nav>
<!-- ./Navbar -->
