<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Incidencia;
use App\Models\ResolucionIncidencia;
use Illuminate\Support\Facades\DB;

class ReporteIncidenciasController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Obtener fechas de filtro
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            // Consulta base para obtener las incidencias
            $query = Incidencia::query()
                ->select([
                    'Incidencias.tipo_experiencia',
                    DB::raw('COUNT(*) as total'),
                    DB::raw('AVG(TIMESTAMPDIFF(HOUR, Incidencias.fecha_reporte, Resolucion_Incidencias.fecha_resolucion)) as tiempo_promedio'),
                    'Resolucion_Incidencias.estado as estado_resolucion',
                    'u1.nombre as reportado_por' 
                ])
                ->leftJoin('Resolucion_Incidencias', 'Incidencias.id_incidencia', '=', 'Resolucion_Incidencias.incidencia')
                ->leftJoin('Usuarios as u1', 'Incidencias.coordinador', '=', 'u1.id_usuario');

            // Aplicar filtro de fechas si se proporcionan
            if ($startDate && $endDate) {
                $query->whereBetween('Incidencias.fecha_reporte', [
                    date('Y-m-d 00:00:00', strtotime($startDate)),
                    date('Y-m-d 23:59:59', strtotime($endDate))
                ]);
            }

            // Agrupar y obtener los resultados
            $incidencias = $query
                ->groupBy('Incidencias.tipo_experiencia', 'Resolucion_Incidencias.estado', 'u1.nombre')
                ->get();

            // Devolver los datos en formato JSON
            return response()->json([
                'incidencias' => $incidencias
            ]);

        } catch (\Exception $e) {
            // Registrar el error y devolver una respuesta de error
            \Log::error('Error en ReporteIncidenciasController: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error al procesar el reporte de incidencias',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}