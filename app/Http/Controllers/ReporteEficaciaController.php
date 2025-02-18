<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evaluacion;

class ReporteEficaciaController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Evaluacion::query();

        if ($startDate && $endDate) {
            $query->whereBetween('fecha_evaluacion', [$startDate, $endDate]);
        }

        $eficacia = $query->selectRaw('categoria_senal, AVG(calificacion_media) as promedio, SUM(senales_correctas) as correctas, SUM(senales_totales) as totales')
            ->groupBy('categoria_senal')
            ->get();

        $labels = $eficacia->pluck('categoria_senal');
        $datasets = [
            [
                'label' => 'Señales Correctas',
                'data' => $eficacia->pluck('correctas'),
                'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                'borderColor' => 'rgba(75, 192, 192, 1)',
                'borderWidth' => 1,
            ],
            [
                'label' => 'Señales Totales',
                'data' => $eficacia->pluck('totales'),
                'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                'borderColor' => 'rgba(255, 99, 132, 1)',
                'borderWidth' => 1,
            ],
        ];

        return response()->json([
            'labels' => $labels,
            'datasets' => $datasets,
        ]);
    }
}
