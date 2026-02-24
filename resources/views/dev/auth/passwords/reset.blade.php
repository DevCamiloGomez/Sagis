<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restablecer Contraseña - SAGIS</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="card card-outline card-danger">
        <div class="card-header text-center">
            <a href="{{ route('login') }}" class="h1"><b>SAGIS</b></a>
        </div>
        <div class="card-body">
            <p class="login-box-msg">Estás a un paso de recuperar tu acceso. Ingresa tu nueva contraseña.</p>

            <form action="{{ route('graduate.password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="input-group mb-3">
                    <input type="email"
                           class="form-control @error('email') is-invalid @enderror"
                           placeholder="Correo Electrónico"
                           name="email"
                           value="{{ $email ?? old('email') }}"
                           required autocomplete="email" autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                    </div>
                    @error('email')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="input-group mb-3">
                    <input type="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Nueva contraseña"
                           name="password"
                           required autocomplete="new-password">
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-lock"></span></div>
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="input-group mb-3">
                    <input type="password"
                           class="form-control"
                           placeholder="Confirmar nueva contraseña"
                           name="password_confirmation"
                           required autocomplete="new-password">
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-lock"></span></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-danger btn-block">Cambiar contraseña</button>
                    </div>
                </div>
            </form>

            <p class="mt-3 mb-1">
                <a href="{{ route('login') }}" class="text-danger">Volver al inicio de sesión</a>
            </p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
<script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js"></script>
</body>
</html>
