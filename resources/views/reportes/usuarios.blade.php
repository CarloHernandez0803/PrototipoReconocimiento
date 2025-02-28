<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Usuarios</title>
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
        <h1>Reporte de Usuarios</h1>
    </div>

    <div class="resumen">
        <h2>Resumen General</h2>
        <p><strong>Total de Actividades:</strong> {{ $data->resumen->total_actividades }}</p>
        <p><strong>Tiempo Promedio de Aprobación:</strong> {{ $data->resumen->tiempo_promedio_aprobacion }} horas</p>
    </div>

    <div class="resumen">
        <h2>Usuarios Más Activos</h2>
        <ul>
            @foreach ($data->usuarios_mas_activos as $usuario)
                <li>
                    <strong>{{ $usuario->nombre }} ({{ $usuario->rol }}):</strong> 
                    {{ $usuario->actividades }} actividades
                </li>
            @endforeach
        </ul>
    </div>

    <div class="resumen">
        <h2>Tiempo Promedio de Aprobación por Rol</h2>
        <ul>
            @foreach ($data->tiempo_promedio_por_rol as $rol => $tiempo)
                <li><strong>{{ $rol }}:</strong> {{ round($tiempo, 2) }} horas</li>
            @endforeach
        </ul>
    </div>

    <h2>Detalle de Usuarios</h2>
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
            @foreach ($data->detalle_usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->nombre }}</td>
                    <td>{{ $usuario->rol }}</td>
                    <td>{{ $usuario->actividades }}</td>
                    <td>{{ round($usuario->tiempo_promedio, 2) }} horas</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>