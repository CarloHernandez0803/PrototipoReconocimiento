<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Incidencias</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .header img { max-width: 150px; margin-bottom: 10px; }
        h1 { font-size: 24px; margin-bottom: 20px; }
        h2 { font-size: 18px; margin-bottom: 10px; }
        .resumen { margin-bottom: 20px; }
        .resumen p { margin: 5px 0; }
        .resumen ul { margin: 5px 0; padding-left: 20px; }
        .resumen li { margin-bottom: 3px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .fechas { margin-bottom: 15px; font-style: italic; }
        .estado-resuelto { background-color: #d4edda; color: #155724; padding: 2px 5px; border-radius: 3px; }
        .estado-pendiente { background-color: #fff3cd; color: #856404; padding: 2px 5px; border-radius: 3px; }
        .footer { margin-top: 20px; font-size: 12px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/upemor.png') }}" alt="Logo">
        <h1>Reporte de Incidencias</h1>
        <div class="fechas">
            <strong>Período:</strong> {{ $resumen['rango_fechas'] }}
        </div>
    </div>

    <div class="resumen">
        <h2>Resumen General</h2>
        <p><strong>Total de Incidencias:</strong> {{ $resumen['total_incidencias'] }}</p>
        <p><strong>Incidencias Resueltas:</strong> {{ $resumen['incidencias_resueltas'] }}</p>
        <p><strong>Porcentaje Resueltas:</strong> {{ round($resumen['porcentaje_resueltas'], 2) }}%</p>
        <p><strong>Tiempo Promedio de Resolución:</strong> 
            {{ $resumen['tiempo_promedio_resolucion'] ? round($resumen['tiempo_promedio_resolucion'], 2) . ' horas' : 'N/A' }}
        </p>
    </div>

    <div class="resumen">
        <h2>Incidencias Más Frecuentes</h2>
        <ul>
            @forelse ($incidenciasFrecuentes as $tipo => $total)
                <li><strong>{{ $tipo }}:</strong> {{ $total }} incidencias</li>
            @empty
                <li>No hay datos disponibles</li>
            @endforelse
        </ul>
    </div>

    <div class="resumen">
        <h2>Tiempo Promedio por Tipo de Incidencia</h2>
        <ul>
            @forelse ($tiemposPorTipo as $tipo => $tiempo)
                <li><strong>{{ $tipo }}:</strong> {{ $tiempo ? round(abs($tiempo), 2) . ' horas' : 'N/A' }}</li>
            @empty
                <li>No hay datos disponibles</li>
            @endforelse
        </ul>
    </div>

    <h2>Detalle Completo de Incidencias</h2>
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
            @forelse ($detalle as $incidencia)
                <tr>
                    <td>{{ $incidencia['tipo_experiencia'] ?? 'Error de Sistema' }}</td>
                    <td>{{ $incidencia['total'] ?? 0 }}</td>
                    <td>
                        @if(isset($incidencia['tiempo_promedio']) && $incidencia['tiempo_promedio'] !== null)
                            {{ round(abs($incidencia['tiempo_promedio']), 2) }}
                        @else
                            N/A
                        @endif horas
                    </td>
                    <td>
                        <span class="estado-{{ strtolower($incidencia['estado'] ?? 'pendiente') }}">
                            {{ $incidencia['estado'] ?? 'Pendiente' }}
                        </span>
                    </td>
                    <td>{{ $incidencia['coordinador_nombre'] ?? 'Usuario Desconocido' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">No se encontraron incidencias</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Generado el: {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>