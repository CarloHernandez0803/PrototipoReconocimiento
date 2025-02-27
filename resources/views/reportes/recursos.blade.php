<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Recursos</title>
    <style>
        body { font-family: Arial, sans-serif; }
        h1 { text-align: center; font-size: 24px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Reporte de Recursos</h1>
    <table>
        <thead>
            <tr>
                <th>Categoría</th>
                <th>Fecha</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data->labels as $index => $label)
                <tr>
                    <td>{{ $label }}</td>
                    <td>{{ $data->datasets[0]['data'][$index] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>