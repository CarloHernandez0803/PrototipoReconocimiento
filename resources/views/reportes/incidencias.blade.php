<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Incidencias</title>
    <style>
        body { font-family: Arial, sans-serif; }
        h1 { text-align: center; font-size: 24px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Reporte de Incidencias</h1>
    <table>
        <thead>
            <tr>
                <th>Tipo</th>
                <th>Total</th>
                <th>Tiempo Promedio</th>
                <th>Estado</th>
                <th>Reportado por</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data->detalle_incidencias as $incidencia)
                <tr>
                    <td>{{ $incidencia->tipo_experiencia }}</td>
                    <td>{{ $incidencia->total }}</td>
                    <td>{{ $incidencia->tiempo_promedio }}</td>
                    <td>{{ $incidencia->estado_resolucion }}</td>
                    <td>{{ $incidencia->reportado_por }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>