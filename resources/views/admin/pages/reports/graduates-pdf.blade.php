<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Graduados</title>
    <style>
        @page {
            size: landscape;
            margin: 1cm;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            table-layout: fixed;
        }
        
        th, td {
            border: 0.5px solid #ddd;
            padding: 4px;
            text-align: left;
            word-wrap: break-word;
        }
        
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            font-size: 10px;
        }
        
        td {
            font-size: 9px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 10px;
            padding: 10px;
            background-color: #f8f9fa;
        }
        
        .header h1 {
            color: #333;
            font-size: 16px;
            margin: 0;
            margin-bottom: 5px;
        }
        
        .date {
            color: #666;
            font-size: 10px;
        }

        /* Ajustar anchos de columnas */
        th:nth-child(1), td:nth-child(1) { width: 15%; } /* Nombre */
        th:nth-child(2), td:nth-child(2) { width: 8%; }  /* Cédula */
        th:nth-child(3), td:nth-child(3) { width: 12%; } /* Correo */
        th:nth-child(4), td:nth-child(4) { width: 8%; }  /* Teléfono */
        th:nth-child(5), td:nth-child(5) { width: 7%; }  /* Año Grado */
        th:nth-child(6), td:nth-child(6) { width: 12%; } /* Programa */
        th:nth-child(7), td:nth-child(7) { width: 12%; } /* Facultad */
        th:nth-child(8), td:nth-child(8) { width: 10%; } /* Universidad */
        th:nth-child(9), td:nth-child(9) { width: 10%; } /* Empresa */
        th:nth-child(10), td:nth-child(10) { width: 6%; } /* Salario */
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Graduados</h1>
        <div class="date">Generado el: {{ date('d/m/Y H:i:s') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Cédula</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Año Grado</th>
                <th>Programa</th>
                <th>Facultad</th>
                <th>Universidad</th>
                <th>Empresa</th>
                <th>Salario</th>
            </tr>
        </thead>
        <tbody>
            @foreach($graduates as $graduate)
                @php
                    $person = $graduate->person;
                    $personAcademic = $person->personAcademic->first();
                    $personCompany = $person->personCompany->first();
                @endphp
                <tr>
                    <td>{{ $person->name }} {{ $person->lastname }}</td>
                    <td>{{ $person->document }}</td>
                    <td>{{ $person->email }}</td>
                    <td>{{ $person->phone }}</td>
                    <td>{{ $personAcademic ? $personAcademic->year : 'N/A' }}</td>
                    <td>{{ $personAcademic ? $personAcademic->program->name : 'N/A' }}</td>
                    <td>{{ $personAcademic ? $personAcademic->program->faculty->name : 'N/A' }}</td>
                    <td>{{ $personAcademic ? $personAcademic->program->faculty->university->name : 'N/A' }}</td>
                    <td>{{ $personCompany ? $personCompany->company->name : 'N/A' }}</td>
                    <td>{{ $personCompany ? '$'.number_format($personCompany->salary, 0, ',', '.') : 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 