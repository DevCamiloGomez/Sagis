<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inicio de Sesión</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"
        integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">


</head>

<body class="hold-transition login-page">
    <div class="login-box">

        <!-- /.login-logo -->
        <div class="card">
            <div class="login-logo">
                <img src="{{ asset('img/logo_sistemas.jpg') }}" alt="">
                <h1>Administrador
                    <hr>
                    <b>SAGIS</b>
                </h1>


            </div>
            <div class="card-body login-card-body">
                <p class="login-box-msg">Ingresa tus datos para iniciar sesión</p>

                <form action="{{ route('admin.loggin') }}" method="post">
                    @csrf

                    <!-- Email -->
                    <div class="input-group mb-3">
                        <input type="email" class="form-control {{ isInvalidInput('email') }}"
                            placeholder="Correo Electrónico" name="email" value="{{ old('email') }}">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                    <!-- ./Email -->

                    <!-- Password -->
                    <div class="input-group mb-3">
                        <input type="password" class="form-control {{ isInvalidInput('password') }}"
                            placeholder="Contraseña" name="password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                    <!-- ./Password -->

                    <button type="submit" class="btn btn-danger btn-block btn-flat">Iniciar Sesión</button>

                    <hr style="border-color: red;">
                  

                    <div class="d-flex  justify-content-center">
                       
                               
                            <a style="text-decoration: none; color: #000000;"
                                href="{{ route('login') }}">

                                <button type="button" class="btn btn-outline-danger border border-danger border-4 fas fa-user-graduate"
                                    style="width: 45px; height: 45px"></button>
                            </a>
                    

    

                    </div>


                  
                    
                </form>



            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>

    <!-- Bootstrap 4 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous">
    </script>

    <!-- AdminLTE App -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>

    <!-- Font Awesome -->
    <script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js"
        integrity="sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc" crossorigin="anonymous">
    </script>

</body>

</html>
