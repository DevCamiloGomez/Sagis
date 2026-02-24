<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Te echamos de menos</title>
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
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
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
            color: rgba(255,255,255,0.9);
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
            margin-bottom: 10px;
        }
        .name {
            font-size: 20px;
            color: #aa1916;
            font-weight: 600;
            margin-bottom: 25px;
        }
        .message {
            font-size: 16px;
            line-height: 1.8;
            color: #555;
            margin-bottom: 30px;
        }
        .highlights {
            background-color: #f8f9fa;
            border-left: 4px solid #aa1916;
            padding: 20px 25px;
            margin: 25px 0;
        }
        .highlights p {
            margin: 10px 0;
            color: #444;
            font-size: 15px;
        }
        .highlights p::before {
            content: "✓ ";
            color: #aa1916;
            font-weight: bold;
        }
        .btn-container {
            text-align: center;
            margin: 35px 0;
        }
        .btn {
            background-color: #aa1916;
            color: #ffffff;
            text-decoration: none;
            padding: 14px 35px;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 500;
            display: inline-block;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #eee;
        }
        .logo {
            max-width: 160px;
            margin-bottom: 15px;
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
            <p class="name">{{ $person->name }} {{ $person->lastname }}</p>

            <div class="message">
                <p>Han pasado más de 30 días desde tu última visita al portal SAGIS y <strong>te echamos de menos</strong>. 🎓</p>
                <p>Tu portal de graduados sigue activo con contenido pensado especialmente para ti:</p>
            </div>

            <div class="highlights">
                <p>Nuevas <strong>ofertas laborales</strong> publicadas en tu área</p>
                <p><strong>Contenido relevante</strong> del Programa de Ingeniería de Sistemas</p>
                <p>Actualiza tu <strong>perfil profesional</strong> y mantente visible</p>
                <p>Conecta con la <strong>comunidad de graduados</strong> UFPS</p>
            </div>

            <div class="btn-container">
                <a href="{{ config('app.url') }}" class="btn">Regresar al Portal →</a>
            </div>

            <p class="message" style="font-size:14px; color:#777;">
                Si ya no deseas recibir estos recordatorios, puedes ignorar este mensaje. El equipo SAGIS siempre estará aquí cuando lo necesites.
            </p>
        </div>

        <div class="footer">
            <img src="https://ingsistemas.cloud.ufps.edu.co/wp-content/uploads/ProgramadeIngenieriadeSistemas.png"
                 alt="Logo UFPS" class="logo">
            <p class="footer-text">
                Programa de Ingeniería de Sistemas - UFPS<br>
                Acreditación de alta calidad<br>
                Cúcuta, Colombia
            </p>
        </div>
    </div>
</body>
</html>
