<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Solicitud;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\SolicitudExport;
use Maatwebsite\Excel\Facades\Excel;

class ReporteSolicitudesController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Solicitud::query();

        if ($startDate && $endDate) {
            $query->whereBetween('fecha_solicitud', [$startDate, $endDate]);
        }

        $solicitudes = $query->selectRaw('estado, COUNT(*) as total')
            ->groupBy('estado')
            ->get();

        $labels = $solicitudes->pluck('estado');
        $datasets = [
            [
                'label' => 'Solicitudes',
                'data' => $solicitudes->pluck('total'),
                'backgroundColor' => ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)'],
                'borderColor' => ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)'],
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

        $pdf = Pdf::loadView('reportes.solicitudes', compact('data'));
        return $pdf->download('reporte_solicitudes.pdf');
    }

    public function downloadExcel(Request $request)
    {
        $data = $this->index($request)->getData();
        return Excel::download(new SolicitudExport($data), 'reporte_solicitudes.xlsx');
    }
}
