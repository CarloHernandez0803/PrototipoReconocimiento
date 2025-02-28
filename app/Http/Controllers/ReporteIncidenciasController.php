<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Incidencia;
use App\Models\ResolucionIncidencia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteIncidenciasController extends Controller
{
    public function index(Request $request)
    {
        try {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            $query = Incidencia::query()
                ->select([
                    'Incidencias.tipo_experiencia',
                    DB::raw('COUNT(*) as total'),
                    DB::raw('AVG(TIMESTAMPDIFF(HOUR, Incidencias.fecha_reporte, Resolucion_Incidencias.fecha_resolucion)) as tiempo_promedio'),
                    'Resolucion_Incidencias.estado as estado_resolucion',
                    DB::raw('CONCAT(u1.nombre, " ", u1.apellidos) as reportado_por')
                ])
                ->leftJoin('Resolucion_Incidencias', 'Incidencias.id_incidencia', '=', 'Resolucion_Incidencias.incidencia')
                ->leftJoin('Usuarios as u1', 'Incidencias.coordinador', '=', 'u1.id_usuario');

            if ($startDate && $endDate) {
                $query->whereBetween('Incidencias.fecha_reporte', [
                    date('Y-m-d 00:00:00', strtotime($startDate)),
                    date('Y-m-d 23:59:59', strtotime($endDate))
                ]);
            }

            $incidencias = $query
                ->groupBy('Incidencias.tipo_experiencia', 'Resolucion_Incidencias.estado', 'u1.nombre', 'u1.apellidos')
                ->get();

            $totalIncidencias = $incidencias->sum('total');
            $incidenciasResueltas = $incidencias->where('estado_resolucion', 'Resuelto')->sum('total');
            $porcentajeResueltas = $totalIncidencias > 0 ? ($incidenciasResueltas / $totalIncidencias) * 100 : 0;

            $incidenciasFrecuentes = $incidencias
                ->groupBy('tipo_experiencia')
                ->map(function ($group) {
                    return $group->sum('total');
                })
                ->sortDesc()
                ->take(5);

            $tiempoPromedioPorTipo = $incidencias
                ->groupBy('tipo_experiencia')
                ->map(function ($group) {
                    return $group->avg('tiempo_promedio');
                });

            return response()->json([
                'resumen' => [
                    'total_incidencias' => $totalIncidencias,
                    'incidencias_resueltas' => $incidenciasResueltas,
                    'porcentaje_resueltas' => round($porcentajeResueltas, 2),
                    'tiempo_promedio_resolucion' => $incidencias->avg('tiempo_promedio'),
                ],
                'incidencias_frecuentes' => $incidenciasFrecuentes,
                'tiempo_promedio_por_tipo' => $tiempoPromedioPorTipo,
                'detalle_incidencias' => $incidencias,
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en ReporteIncidenciasController: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error al procesar el reporte de incidencias',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function downloadPDF(Request $request)
    {
        $data = $this->index($request)->getData();

        $pdf = Pdf::loadView('reportes.incidencias', compact('data'));
        return $pdf->download('reporte_incidencias.pdf');
    }
}