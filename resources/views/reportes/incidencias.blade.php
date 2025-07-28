<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Incidencias</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .header img { max-width: 150px; }
        h1 { font-size: 24px; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        h2 { font-size: 18px; margin-top: 30px; margin-bottom: 10px; }
        .resumen { margin-bottom: 20px; }
        .resumen p, .resumen li { margin: 5px 0; font-size: 14px; }
        .resumen ul { margin: 5px 0; padding-left: 20px; list-style-type: disc; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; font-size: 12px; word-wrap: break-word; }
        th { background-color: #f2f2f2; }
        .fechas { margin-bottom: 15px; font-style: italic; color: #555; }
        .footer { margin-top: 30px; font-size: 10px; text-align: right; color: #777; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/upemor.png') }}" alt="Logo">
        <h1>Reporte de Incidencias</h1>
        <div class="fechas">
            <strong>Período:</strong> {{ $resumen['rango_fechas'] ?? 'Todos los registros' }}
        </div>
    </div>

    <div class="resumen">
        <h2>Resumen General</h2>
        <p><strong>Total de Incidencias:</strong> {{ $resumen['total_incidencias'] ?? 0 }}</p>
        <p><strong>Incidencias Resueltas:</strong> {{ $resumen['incidencias_resueltas'] ?? 0 }}</p>
        <p><strong>Porcentaje Resueltas:</strong> {{ isset($resumen['porcentaje_resueltas']) ? round($resumen['porcentaje_resueltas'], 2) : 0 }}%</p>
        <p><strong>Tiempo Promedio de Resolución:</strong> 
            {{ isset($resumen['tiempo_promedio_resolucion']) ? round($resumen['tiempo_promedio_resolucion'], 2) . ' horas' : 'N/A' }}
        </p>
    </div>

    <div class="resumen">
        <h2>Incidencias Más Frecuentes</h2>
        <ul>
            @forelse ($incidenciasFrecuentes as $tipo => $total)
                <li><strong>{{ $tipo }}:</strong> {{ $total }} incidencias</li>
            @empty
                <li>No hay datos disponibles.</li>
            @endforelse
        </ul>
    </div>

    <div class="resumen">
        <h2>Tiempo Promedio por Tipo de Incidencia</h2>
        <ul>
            @forelse ($tiemposPorTipo as $tipo => $tiempo)
                <li><strong>{{ $tipo }}:</strong> {{ $tiempo !== null ? round(abs($tiempo), 2) . ' horas' : 'N/A' }}</li>
            @empty
                <li>No hay datos disponibles.</li>
            @endforelse
        </ul>
    </div>

    <h2>Detalle Completo de Incidencias por Tipo</h2>
    <table>
        <thead>
            <tr>
                <th>Tipo</th>
                <th>Total</th>
                <th>Tiempo Promedio</th>
                <th>Estado General</th>
                <th>Principal Reportador</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($detalle as $incidencia)
                <tr>
                    <td>{{ $incidencia['tipo_experiencia'] ?? 'N/A' }}</td>
                    <td>{{ $incidencia['total'] ?? 0 }}</td>
                    <td>{{ isset($incidencia['tiempo_promedio']) ? round(abs($incidencia['tiempo_promedio']), 2).' horas' : 'N/A' }}</td>
                    <td>{{ $incidencia['estado'] ?? 'Pendiente' }}</td>
                    <td>{{ $incidencia['coordinador_nombre'] ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">No se encontraron incidencias en el período seleccionado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Generado el: {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>