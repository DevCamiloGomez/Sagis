<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $subject }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        }
        .header {
            background-color: #aa1916;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 26px;
            font-weight: 500;
            letter-spacing: 0.5px;
        }
        .header p {
            color: rgba(255, 255, 255, 0.9);
            margin: 10px 0 0;
            font-size: 16px;
        }
        .content {
            padding: 40px 30px;
            background-color: #ffffff;
        }
        .greeting {
            font-size: 18px;
            color: #333;
            margin-bottom: 25px;
        }
        .name {
            font-size: 20px;
            color: #aa1916;
            font-weight: 600;
            margin-bottom: 30px;
        }
        .message {
            font-size: 16px;
            line-height: 1.8;
            color: #555;
            margin-bottom: 30px;
        }
        .credentials {
            background-color: #f8f9fa;
            padding: 25px;
            border-left: 4px solid #aa1916;
            margin: 30px 0;
        }
        .credentials p {
            margin: 20px 0;
            color: #444;
            font-size: 15px;
        }
        .credentials strong {
            display: block;
            color: #333;
            font-size: 16px;
            margin-bottom: 8px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #eee;
        }
        .logo {
            max-width: 160px;
            margin-bottom: 20px;
        }
        .footer-text {
            color: #666;
            font-size: 13px;
            line-height: 1.8;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>SAGIS</h1>
            <p>Sistema de Apoyo a Graduados de Ingeniería de Sistemas</p>
        </div>
        
        <div class="content">
            <p class="greeting">Estimado(a) Ingeniero(a),</p>
            <p class="name">{{$person->name}} {{$person->lastname}}</p>
            
            <div class="message">
                {!! $content !!}
            </div>

            @if($includeCredentials && $userParams)
            <div class="credentials">
                <p>
                    <strong>🔗 Portal de Acceso</strong>
                    <a href="https://sagisufps.onrender.com">https://sagisufps.onrender.com</a>
                </p>
                
                <p>
                    <strong>📧 Correo electrónico</strong>
                    {{ $userParams['email'] }}
                </p>
                
                <p>
                    <strong>🔑 Contraseña</strong>
                    {{ $userParams['password'] }}
                </p>
            </div>
            @endif
        </div>
        
        <div class="footer">
            <img src="https://ingsistemas.cloud.ufps.edu.co/wp-content/uploads/2023/10/logo-ing-sistemas.png" alt="Logo UFPS" class="logo">
            <p class="footer-text">
                Programa de Ingeniería de Sistemas - UFPS<br>
                Acreditación de alta calidad<br>
                Cúcuta, Colombia
            </p>
        </div>
    </div>
</body>
</html> 