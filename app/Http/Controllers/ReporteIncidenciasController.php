<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Incidencia;
use App\Models\Resolucion;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteIncidenciasController extends Controller
{
    public function index(Request $request)
    {
        try {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            // Base query para incidencias con relaciones
            $query = Incidencia::with(['resolucion', 'usuarioCoordinador'])
                ->select([
                    'incidencias.*',
                    DB::raw('CONCAT(usuarios.nombre, " ", usuarios.apellidos) as coordinador_nombre')
                ])
                ->leftJoin('usuarios', 'incidencias.coordinador', '=', 'usuarios.id_usuario');

            // Aplicar filtros de fecha si existen
            if ($startDate && $endDate) {
                $query->whereBetween('incidencias.fecha_reporte', [
                    Carbon::parse($startDate)->startOfDay(),
                    Carbon::parse($endDate)->endOfDay()
                ]);
            }

            // Obtener datos agrupados por tipo y coordinador
            $incidenciasAgrupadas = $query->get()
                ->groupBy(['tipo_experiencia', 'coordinador'])
                ->map(function ($tipos, $tipo) {
                    return $tipos->map(function ($coordinadores, $coordinadorId) use ($tipo) {
                        $first = $coordinadores->first();
                        return [
                            'tipo_experiencia' => $tipo,
                            'total' => $coordinadores->count(),
                            'coordinador_nombre' => $first->coordinador_nombre ?? 'Desconocido',
                            'estado' => $coordinadores->every(function ($item) {
                                return optional($item->resolucion)->estado === 'Resuelto';
                            }) ? 'Resuelto' : 'Pendiente',
                            'tiempo_promedio' => $coordinadores->avg(function ($item) {
                                if ($item->resolucion && $item->resolucion->fecha_resolucion) {
                                    return Carbon::parse($item->fecha_reporte)
                                        ->diffInHours(Carbon::parse($item->resolucion->fecha_resolucion));
                                }
                                return null;
                            })
                        ];
                    });
                })
                ->flatten(1);

            // Calcular métricas del resumen
            $resumen = [
                'total_incidencias' => $incidenciasAgrupadas->sum('total'),
                'incidencias_resueltas' => $incidenciasAgrupadas->where('estado', 'Resuelto')->sum('total'),
                'porcentaje_resueltas' => $incidenciasAgrupadas->sum('total') > 0 ? 
                    ($incidenciasAgrupadas->where('estado', 'Resuelto')->sum('total') / $incidenciasAgrupadas->sum('total')) * 100 : 0,
                'tiempo_promedio_resolucion' => $incidenciasAgrupadas->where('estado', 'Resuelto')
                    ->avg('tiempo_promedio')
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'resumen' => $resumen,
                    'detalle' => $incidenciasAgrupadas->values()
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en ReporteIncidenciasController: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al generar el reporte',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function downloadPDF(Request $request)
    {
        try {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            // Base query para incidencias con relaciones
            $query = Incidencia::with(['resolucion', 'usuarioCoordinador'])
                ->select([
                    'incidencias.*',
                    DB::raw('CONCAT(usuarios.nombre, " ", usuarios.apellidos) as coordinador_nombre')
                ])
                ->leftJoin('usuarios', 'incidencias.coordinador', '=', 'usuarios.id_usuario');

            // Aplicar filtros de fecha si existen
            if ($startDate && $endDate) {
                $query->whereBetween('incidencias.fecha_reporte', [
                    Carbon::parse($startDate)->startOfDay(),
                    Carbon::parse($endDate)->endOfDay()
                ]);
            }

            // Obtener todos los registros (sin agrupar aún)
            $incidencias = $query->get();

            // Agrupar por tipo y coordinador para el detalle
            $incidenciasAgrupadas = $incidencias
                ->groupBy(['tipo_experiencia', 'coordinador'])
                ->map(function ($tipos, $tipo) {
                    return $tipos->map(function ($coordinadores, $coordinadorId) use ($tipo) {
                        $first = $coordinadores->first();
                        return [
                            'tipo_experiencia' => $tipo,
                            'total' => $coordinadores->count(),
                            'coordinador_nombre' => $first->coordinador_nombre ?? 'Desconocido',
                            'estado' => $coordinadores->every(function ($item) {
                                return optional($item->resolucion)->estado === 'Resuelto';
                            }) ? 'Resuelto' : 'Pendiente',
                            'tiempo_promedio' => $coordinadores->avg(function ($item) {
                                if ($item->resolucion && $item->resolucion->fecha_resolucion) {
                                    return Carbon::parse($item->fecha_reporte)
                                        ->diffInHours(Carbon::parse($item->resolucion->fecha_resolucion));
                                }
                                return null;
                            })
                        ];
                    });
                })
                ->flatten(1);

            // Calcular métricas del resumen
            $resumen = [
                'total_incidencias' => $incidencias->count(),
                'incidencias_resueltas' => $incidencias->filter(function($item) {
                    return optional($item->resolucion)->estado === 'Resuelto';
                })->count(),
                'porcentaje_resueltas' => $incidencias->count() > 0 ? 
                    ($incidencias->filter(function($item) {
                        return optional($item->resolucion)->estado === 'Resuelto';
                    })->count() / $incidencias->count()) * 100 : 0,
                'tiempo_promedio_resolucion' => $incidencias->filter(function($item) {
                    return optional($item->resolucion)->estado === 'Resuelto';
                })->avg(function($item) {
                    if ($item->resolucion && $item->resolucion->fecha_resolucion) {
                        return Carbon::parse($item->fecha_reporte)
                            ->diffInHours(Carbon::parse($item->resolucion->fecha_resolucion));
                    }
                    return null;
                }),
                'rango_fechas' => $startDate && $endDate ? 
                    Carbon::parse($startDate)->format('d/m/Y') . ' - ' . Carbon::parse($endDate)->format('d/m/Y') : 
                    'Todos los registros'
            ];

            // Calcular incidencias más frecuentes (top 5)
            $incidenciasFrecuentes = $incidencias
                ->groupBy('tipo_experiencia')
                ->map->count()
                ->sortDesc()
                ->take(5);

            // Calcular tiempos por tipo
            $tiemposPorTipo = $incidencias
                ->groupBy('tipo_experiencia')
                ->map(function($items) {
                    return $items->avg(function($item) {
                        if ($item->resolucion && $item->resolucion->fecha_resolucion) {
                            return Carbon::parse($item->fecha_reporte)
                                ->diffInHours(Carbon::parse($item->resolucion->fecha_resolucion));
                        }
                        return null;
                    });
                });

            $pdf = Pdf::loadView('reportes.incidencias', [
                'resumen' => $resumen,
                'detalle' => $incidenciasAgrupadas->values(),
                'incidenciasFrecuentes' => $incidenciasFrecuentes,
                'tiemposPorTipo' => $tiemposPorTipo
            ]);

            $fileName = 'reporte_incidencias_' . now()->format('Ymd_His') . '.pdf';

            return $pdf->download($fileName);

        } catch (\Exception $e) {
            \Log::error('Error al generar PDF: ' . $e->getMessage());
            return back()->with('error', 'Error al generar el PDF: ' . $e->getMessage());
        }
    }
}