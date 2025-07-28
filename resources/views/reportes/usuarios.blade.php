<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Actividad de Usuarios</title>
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
        <h1>Reporte de Actividad de Usuarios</h1>
        <div class="fechas">
            <strong>Período:</strong> {{ $resumen['rango_fechas'] ?? 'Todos los registros' }}
        </div>
    </div>

    <div class="resumen">
        <h2>Resumen General</h2>
        <p><strong>Total de Actividades:</strong> {{ $resumen['total_actividades'] ?? 0 }}</p>
        <p><strong>Usuarios Activos:</strong> {{ $resumen['usuarios_activos'] ?? 0 }}</p>
        <p><strong>Tiempo Promedio General:</strong> 
            {{ isset($resumen['tiempo_promedio_general']) ? round($resumen['tiempo_promedio_general'], 2).' horas' : 'N/A' }}
        </p>
    </div>

    <div class="resumen">
        <h2>Usuarios Más Activos</h2>
        <ul>
            @forelse ($usuarios_mas_activos as $nombre => $actividades)
                <li>
                    <strong>{{ $nombre }}:</strong> {{ $actividades }} actividades
                </li>
            @empty
                <li>No hay datos de actividad para mostrar.</li>
            @endforelse
        </ul>
    </div>

    <div class="resumen">
        <h2>Tiempo Promedio por Rol</h2>
        <ul>
            @forelse ($tiempo_promedio_por_rol as $rol => $tiempo)
                <li>
                    <strong>{{ $rol }}:</strong> 
                    {{ $tiempo !== null ? round($tiempo, 2).' horas' : 'N/A' }}
                </li>
            @empty
                <li>No hay datos de tiempo para mostrar.</li>
            @endforelse
        </ul>
    </div>

    <h2>Detalle Completo de Actividades</h2>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Rol</th>
                <th>Actividades</th>
                <th>Tiempo Promedio</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($detalle_usuarios as $usuario)
                <tr>
                    <td>{{ $usuario['nombre_completo'] ?? 'Usuario Desconocido' }}</td>
                    <td>{{ $usuario['rol'] ?? 'Sin rol' }}</td>
                    <td>{{ $usuario['total_actividades'] ?? 0 }}</td>
                    <td>{{ isset($usuario['tiempo_promedio']) ? round($usuario['tiempo_promedio'], 2).' horas' : 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">No se encontraron actividades en el período seleccionado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Generado el: {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>