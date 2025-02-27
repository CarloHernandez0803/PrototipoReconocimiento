<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Experiencias</title>
    <style>
        body { font-family: Arial, sans-serif; }
        h1 { text-align: center; font-size: 24px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Reporte de Experiencias</h1>
    <table>
        <thead>
            <tr>
                <th>Tipo de Experiencia</th>
                <th>Impacto</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data->datasets as $dataset)
                @foreach ($dataset['data'] as $index => $value)
                    <tr>
                        <td>{{ $dataset['label'] }}</td>
                        <td>{{ $data->labels[$index] }}</td>
                        <td>{{ $value }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>