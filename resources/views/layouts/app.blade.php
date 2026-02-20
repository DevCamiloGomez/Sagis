<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>


    <!-- Fuente de google: Open Sans - Regular 400 -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!--Link del boostrap-->
    <link rel="stylesheet" href="{{ asset('css/lightbox.css') }}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    <!--Importar CSS y script del menú -->
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/index.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">

    <!-- SweetAlert -->
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">

    @yield('css')

    @yield('custom_css')

    <style>
        .separador {
            color: white;
        }

        #lista1 li {
            display: inline;
            padding-right: 3px;

        }
    </style>

    <!-- jQuery primero -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- lightbox-plus-jquery.js después de jQuery -->
    <script src="{{ asset('js/ligthboxjs/lightbox-plus-jquery.min.js') }}"></script>

    <!-- Select2 local y CDN después de lightbox -->
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2-bootstrap4.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</head>

<body class="d-flex flex-column min-vh-100">

    <!-- Nav -->
    @include('partials.navbar')
    <!-- ./Nav -->



    @auth
    @if ((graduate_user()->person->has_data_person() == false) || (graduate_user()->person->has_data_academic() ==
    false) || (graduate_user()->person->has_data_company() == false))
    <div class="text-center border border-success bg-success pt-2 pb-2">
        <h3 style="display:inline;">¡Por favor primero actualiza tus datos aquí:</h3>
        <ul id="lista1" style="display:inline;" class=" pl-1">
            @if(graduate_user()->person->has_data_person() == false)
            <li> <span class="separador">| </span>
                <h3 style="display:inline;"><a href="{{route('profile')  }}" style="color:#000000;">personales</a></h3>
            </li>

            @endif
            @if(graduate_user()->person->has_data_academic() == false)
            <li> <span class="separador">| </span>
                <h3 style="display:inline;"><a href="{{route('academics')  }}" style="color:#000000;">académicos</a>
                </h3>
            </li>
            @endif
            @if(graduate_user()->person->has_data_company() == false)
            <li> <span class="separador">| </span>
                <h3 style="display:inline;"><a href="{{route('jobs')  }}" style="color:#000000;">laborales</a></h3>
            </li>
            @endif

        </ul>
    </div>
    @endif
    @endauth



    <main class="flex-grow-1">

        <!-- Content Header -->
        @yield('content-header')
        <!-- ./Content Header -->


        @yield('content')

    </main>

    <!-- Footer -->
    @include('partials.footer')
    <!-- ./Footer -->

    <script src=" {{ asset('js/sesion.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
        </script>

    <!-- AdminLTE App -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>

    <!-- Font Awesome -->
    <script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js"
        integrity="sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc" crossorigin="anonymous">
        </script>

    <!-- SweetAlert2 -->
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>

    @yield('js')

    @if ($alert = Session::get('alert'))
    <script>
        Swal.fire({
            position: 'center',
            icon: "{{ $alert['icon'] }}",
            title: "{{ $alert['title'] }}",
            text: "{{ $alert['message'] }}",
            showConfirmButton: true,
            confirmButtonText: 'Ok',
            timer: 3500
        })
    </script>
    @endif


    @yield('custom_js')

</body>

</html>