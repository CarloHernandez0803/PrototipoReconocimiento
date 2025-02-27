<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SenEntrenamiento;

class ReporteRecursosController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = SenEntrenamiento::query();

        if ($startDate && $endDate) {
            $query->whereBetween('fecha_creacion', [$startDate, $endDate]);
        }

        $recursos = $query->selectRaw('categoria, DATE(fecha_creacion) as fecha, COUNT(*) as total')
            ->groupBy('categoria', 'fecha')
            ->get();

        $labels = $recursos->pluck('fecha');
        $datasets = [
            [
                'label' => 'Recursos',
                'data' => $recursos->pluck('total'),
                'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                'borderColor' => 'rgba(75, 192, 192, 1)',
                'borderWidth' => 1,
            ],
        ];

        return response()->json([
            'labels' => $labels,
            'datasets' => $datasets,
        ]);
    }

    public function downloadPDF(Request $request)
    {
        $data = $this->index($request)->getData();

        $pdf = Pdf::loadView('reportes.recursos', compact('data'));
        return $pdf->download('reporte_recursos.pdf');
    }

    public function downloadExcel(Request $request)
    {
        $data = $this->index($request)->getData();
        return Excel::download(new RecursoExport($data), 'reporte_recursos.xlsx');
    }
}
