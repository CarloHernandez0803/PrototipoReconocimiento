<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReporteUsuariosService; // <-- Importar el Service
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class ReporteUsuariosController extends Controller
{
    protected $reporteService;

    // Inyectamos el servicio en el constructor
    public function __construct(ReporteUsuariosService $reporteService)
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

            return response()->json(['success' => true, 'data' => $data]);

        } catch (\Exception $e) {
            Log::error('Error en ReporteUsuariosController@index: ' . $e->getMessage());
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

            $pdf = Pdf::loadView('reportes.usuarios', $data);
            return $pdf->download('reporte_usuarios_' . now()->format('Ymd_His') . '.pdf');

        } catch (\Exception $e) {
            Log::error('Error al generar PDF de usuarios: ' . $e->getMessage());
            return back()->with('error', 'Error al generar el PDF: ' . $e->getMessage());
        }
    }
}