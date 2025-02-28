<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Incidencias</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .header img { max-width: 150px; margin-bottom: 10px; }
        h1 { font-size: 24px; margin-bottom: 20px; }
        .resumen { margin-bottom: 20px; }
        .resumen h2 { font-size: 18px; margin-bottom: 10px; }
        .resumen p { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/upemor.png') }}" alt="Logo">
        <h1>Reporte de Incidencias</h1>
    </div>

    <div class="resumen">
        <h2>Resumen General</h2>
        <p><strong>Total de Incidencias:</strong> {{ $data->resumen->total_incidencias }}</p>
        <p><strong>Incidencias Resueltas:</strong> {{ $data->resumen->incidencias_resueltas }}</p>
        <p><strong>Porcentaje Resueltas:</strong> {{ $data->resumen->porcentaje_resueltas }}%</p>
        <p><strong>Tiempo Promedio de Resolución:</strong> {{ $data->resumen->tiempo_promedio_resolucion }} horas</p>
    </div>

    <div class="resumen">
        <h2>Incidencias Más Frecuentes</h2>
        <ul>
            @foreach ($data->incidencias_frecuentes as $tipo => $total)
                <li><strong>{{ $tipo }}:</strong> {{ $total }} incidencias</li>
            @endforeach
        </ul>
    </div>

    <div class="resumen">
        <h2>Tiempo Promedio por Tipo de Incidencia</h2>
        <ul>
            @foreach ($data->tiempo_promedio_por_tipo as $tipo => $tiempo)
                <li><strong>{{ $tipo }}:</strong> {{ round($tiempo, 2) }} horas</li>
            @endforeach
        </ul>
    </div>

    <h2>Detalle de Incidencias</h2>
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
                    <td>{{ round($incidencia->tiempo_promedio, 2) }} horas</td>
                    <td>{{ $incidencia->estado_resolucion }}</td>
                    <td>{{ $incidencia->reportado_por }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>