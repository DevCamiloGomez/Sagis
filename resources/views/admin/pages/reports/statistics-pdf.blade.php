<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte Estadístico - SAGIS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #aa1916;
            padding-bottom: 10px;
        }
        .header img {
            max-width: 100%;
            height: auto;
            max-height: 100px;
        }
        .header h1 {
            color: #aa1916;
            margin: 10px 0 0 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 12px;
        }
        .section-title {
            background-color: #fdf2f2;
            padding: 8px;
            margin-top: 20px;
            border-left: 4px solid #aa1916;
            font-weight: bold;
            font-size: 14px;
            color: #aa1916;
        }
        .stats-grid {
            display: table;
            width: 100%;
            margin-top: 10px;
        }
        .stat-box {
            display: table-cell;
            width: 33%;
            padding: 10px;
            text-align: center;
            border: 1px solid #e0e0e0;
            background-color: #fff;
        }
        .stat-value {
            font-size: 18px;
            font-weight: bold;
            color: #aa1916;
        }
        .stat-label {
            font-size: 11px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #e0e0e0;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #fdf2f2;
            font-weight: bold;
            color: #aa1916;
        }
        .page-break {
            page-break-after: always;
        }
        .chart-container {
            text-align: center;
            margin-top: 15px;
            margin-bottom: 20px;
        }
        .chart-img {
            max-width: 100%;
            height: auto;
            border: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="https://ingsistemas.cloud.ufps.edu.co/wp-content/uploads/PRGRAMAINGENIERIADESISTEMAS.png" alt="Ingeniería de Sistemas UFPS">
        <h1>Reporte de Estadísticas y Gráficas</h1>
        <p>Sistema de Apoyo a la Gestión de Información de Seguimiento (SAGIS)</p>
        <p>Fecha: {{ date('d/m/Y') }}</p>
    </div>

    <div class="section-title">Resumen General</div>
    <div class="stats-grid">
        <div class="stat-box">
            <div class="stat-value">{{ $graduates }}</div>
            <div class="stat-label">Graduados Registrados</div>
        </div>
        <div class="stat-box">
            <div class="stat-value">{{ $graduadosConTrabajo }}</div>
            <div class="stat-label">Con Empleo</div>
        </div>
        <div class="stat-box">
            <div class="stat-value">{{ $graduadoSinTrabajo }}</div>
            <div class="stat-label">Desempleados</div>
        </div>
    </div>
    <div class="stats-grid">
        <div class="stat-box">
            <div class="stat-value">{{ $extraGraduates }}</div>
            <div class="stat-label">En el Extranjero</div>
        </div>
        <div class="stat-box">
            <div class="stat-value">${{ number_format($salaryMin, 0, ',', '.') }}</div>
            <div class="stat-label">Salario Mínimo</div>
        </div>
        <div class="stat-box">
            <div class="stat-value">${{ number_format($salaryMax, 0, ',', '.') }}</div>
            <div class="stat-label">Salario Máximo</div>
        </div>
    </div>

    <div class="section-title">Distribución Salarial</div>
    
    @if(!empty($charts['salary']))
    <div class="chart-container">
        <img src="{{ $charts['salary'] }}" class="chart-img">
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Rango Salarial</th>
                <th>Cantidad de Graduados</th>
            </tr>
        </thead>
        <tbody>
            @foreach($salaryDistribution as $range => $count)
            <tr>
                <td>{{ $range }}</td>
                <td>{{ $count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Top Empresas Empleadoras</div>

    @if(!empty($charts['companies']))
    <div class="chart-container">
        <img src="{{ $charts['companies'] }}" class="chart-img">
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Empresa</th>
                <th>Graduados Contratados</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topCompanies as $company)
            <tr>
                <td>{{ $company->name }}</td>
                <td>{{ $company->total }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="page-break"></div>

    <div class="section-title">Graduados por Año</div>
    
    @if(!empty($charts['bar']))
    <div class="chart-container">
        <img src="{{ $charts['bar'] }}" class="chart-img">
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Año</th>
                <th>Cantidad de Graduados</th>
            </tr>
        </thead>
        <tbody>
            @foreach($graduatesByYear as $stat)
            <tr>
                <td>{{ $stat->year }}</td>
                <td>{{ $stat->total }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="page-break"></div>

    <div class="section-title">Graduados por País</div>
    @if(!empty($charts['pie']))
    <div class="chart-container">
        <img src="{{ $charts['pie'] }}" class="chart-img" style="max-height: 400px;">
    </div>
    @endif

</body>
</html>
