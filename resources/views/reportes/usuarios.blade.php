<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Actividad de Usuarios</title>
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
        .footer { margin-top: 20px; font-size: 12px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/upemor.png') }}" alt="Logo">
        <h1>Reporte de Actividad de Usuarios</h1>
        <div class="fechas">
            <strong>Período:</strong> {{ data_get($resumen, 'rango_fechas', 'Todos los registros') }}
        </div>
    </div>

    <div class="resumen">
        <h2>Resumen General</h2>
        <p><strong>Total de Actividades:</strong> {{ data_get($resumen, 'total_actividades', 0) }}</p>
        <p><strong>Usuarios Activos:</strong> {{ data_get($resumen, 'usuarios_activos', 0) }}</p>
        <p><strong>Tiempo Promedio General:</strong> 
            {{ data_get($resumen, 'tiempo_promedio_general') ? round(data_get($resumen, 'tiempo_promedio_general'), 2).' horas' : 'N/A' }}
        </p>
    </div>

    <div class="resumen">
        <h2>Usuarios Más Activos</h2>
        <ul>
            @foreach ($usuarios_mas_activos as $usuario)
                @php
                    // Manejo seguro de propiedades tanto para objetos como arrays
                    $nombre = is_object($usuario) ? ($usuario->nombre_completo ?? '') : ($usuario['nombre_completo'] ?? '');
                    $rol = is_object($usuario) ? ($usuario->rol ?? '') : ($usuario['rol'] ?? '');
                    $actividades = is_object($usuario) ? ($usuario->total_actividades ?? 0) : ($usuario['total_actividades'] ?? 0);
                @endphp
                <li>
                    <strong>{{ $nombre }} ({{ $rol }}):</strong> 
                    {{ $actividades }} actividades
                </li>
            @endforeach
        </ul>
    </div>

    <div class="resumen">
        <h2>Tiempo Promedio por Rol</h2>
        <ul>
            @foreach ($tiempo_promedio_por_rol as $rol => $tiempo)
                @php
                    // Manejo seguro del rol y tiempo
                    $tiempoRedondeado = $tiempo ? round($tiempo, 2) : null;
                @endphp
                <li><strong>{{ $rol }}:</strong> {{ $tiempoRedondeado ? $tiempoRedondeado.' horas' : 'N/A' }}</li>
            @endforeach
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
            @forelse ($detalle as $usuario)
                @php
                    // Manejo seguro de propiedades tanto para objetos como arrays
                    $nombre = is_object($usuario) ? ($usuario->nombre_completo ?? 'Usuario Desconocido') : ($usuario['nombre_completo'] ?? 'Usuario Desconocido');
                    $rol = is_object($usuario) ? ($usuario->rol ?? 'Sin rol') : ($usuario['rol'] ?? 'Sin rol');
                    $actividades = is_object($usuario) ? ($usuario->total_actividades ?? 0) : ($usuario['total_actividades'] ?? 0);
                    $tiempo = is_object($usuario) ? ($usuario->tiempo_promedio ?? null) : ($usuario['tiempo_promedio'] ?? null);
                    $tiempoFormateado = $tiempo !== null ? round($tiempo, 2).' horas' : 'N/A';
                @endphp
                <tr>
                    <td>{{ $nombre }}</td>
                    <td>{{ $rol }}</td>
                    <td>{{ $actividades }}</td>
                    <td>{{ $tiempoFormateado }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">No se encontraron actividades</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Generado el: {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>