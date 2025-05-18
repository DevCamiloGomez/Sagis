<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Credenciales de Acceso - SAGIS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 2px solid #aa1916;
        }
        .content {
            padding: 20px 0;
        }
        .credentials {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .credentials p {
            margin: 10px 0;
        }
        .footer {
            text-align: center;
            padding: 20px 0;
            border-top: 1px solid #eee;
        }
        .logo {
            max-width: 200px;
            margin: 20px auto;
        }
        .important {
            color: #aa1916;
            font-weight: bold;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #aa1916;
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="color: #aa1916; margin: 0;">SAGIS - Sistema de Apoyo a Graduados</h1>
            <p style="color: #666;">Universidad Francisco de Paula Santander</p>
        </div>
        
        <div class="content">
            <p>Estimado(a) Ingeniero(a),</p>
            <p><strong>{{$person->name}} {{$person->lastname}}</strong></p>
            
            <p>Le damos la bienvenida al Sistema de Apoyo a Graduados de Ingeniería de Sistemas. A continuación, encontrará sus credenciales de acceso:</p>
            
            <div class="credentials">
                <p><strong>🔗 Portal de Acceso:</strong><br>
                   <a href="https://sagisufps.onrender.com" style="color: #aa1916;">https://sagisufps.onrender.com</a></p>
                
                <p><strong>📧 Correo electrónico:</strong><br>
                   {{ $userParams['email'] }}</p>
                
                <p><strong>🔑 Contraseña temporal:</strong><br>
                   password</p>
            </div>
            
            <p class="important">Por seguridad, le recomendamos cambiar su contraseña después del primer inicio de sesión.</p>
            
            <div style="text-align: center;">
                <a href="https://sagisufps.onrender.com" class="button">Acceder al Sistema</a>
            </div>
        </div>
        
        <div class="footer">
            <img src="https://ingsistemas.cloud.ufps.edu.co/wp-content/uploads/2023/10/logo-ing-sistemas.png" alt="Logo UFPS" class="logo">
            <p style="color: #666; font-size: 12px;">
                Programa de Ingeniería de Sistemas - UFPS<br>
                Acreditación de alta calidad<br>
                Cúcuta, Colombia
            </p>
        </div>
    </div>
</body>
</html>