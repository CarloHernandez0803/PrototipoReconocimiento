<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Experiencia;

class ReporteExperienciasController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Experiencia::query();

        if ($startDate && $endDate) {
            $query->whereBetween('fecha_experiencia', [$startDate, $endDate]);
        }

        $experiencias = $query->selectRaw('tipo_experiencia, impacto, COUNT(*) as total')
            ->groupBy('tipo_experiencia', 'impacto')
            ->get();

        $labels = ['Positivo', 'Negativo', 'Neutro']; 
        $datasets = [
            'alto' => [0, 0, 0], 
            'medio' => [0, 0, 0],
            'bajo' => [0, 0, 0],
        ];

        // Llenar los datos
        foreach ($experiencias as $experiencia) {
            $tipo = strtolower($experiencia->tipo_experiencia);
            $impacto = strtolower($experiencia->impacto);

            if ($tipo === 'positiva') {
                $index = 0;
            } elseif ($tipo === 'negativa') {
                $index = 1;
            } else {
                $index = 2;
            }

            if ($impacto === 'alto') {
                $datasets['alto'][$index] += $experiencia->total;
            } elseif ($impacto === 'medio') {
                $datasets['medio'][$index] += $experiencia->total;
            } else {
                $datasets['bajo'][$index] += $experiencia->total;
            }
        }

        $chartData = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Alto',
                    'data' => $datasets['alto'],
                    'backgroundColor' => 'rgba(255, 99, 132, 0.6)', 
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Medio',
                    'data' => $datasets['medio'],
                    'backgroundColor' => 'rgba(54, 162, 235, 0.6)', 
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Bajo',
                    'data' => $datasets['bajo'],
                    'backgroundColor' => 'rgba(75, 192, 192, 0.6)', 
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 1,
                ],
            ],
        ];

        return response()->json($chartData);
    }
}
