<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReporteIncidenciasService; // Importar el Service
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class ReporteIncidenciasController extends Controller
{
    protected $reporteService;

    public function __construct(ReporteIncidenciasService $reporteService)
    {
        $this->reporteService = $reporteService;
    }

    public function index(Request $request)
    {
        try {
            $data = $this->reporteService->generarReporte(
                $request->input('start_date'),
                $request->input('end_date')
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'resumen' => $data['resumen'],
                    'detalle' => $data['detalle']
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error en ReporteIncidenciasController@index: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al generar el reporte.'], 500);
        }
    }

    public function downloadPDF(Request $request)
    {
        try {
            $data = $this->reporteService->generarReporte(
                $request->input('start_date'),
                $request->input('end_date')
            );
            $pdf = Pdf::loadView('reportes.incidencias', $data);
            return $pdf->download('reporte_incidencias_' . now()->format('Ymd_His') . '.pdf');
        } catch (\Exception $e) {
            Log::error('Error al generar PDF de incidencias: ' . $e->getMessage());
            return back()->with('error', 'Error al generar el PDF: ' . $e->getMessage());
        }
    }
}