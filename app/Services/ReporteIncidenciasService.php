<?php

namespace App\Services;

use App\Models\Incidencia;
use Carbon\Carbon;

class ReporteIncidenciasService
{
    public function generarReporte($startDate = null, $endDate = null)
    {
        $query = Incidencia::with(['resoluciones.administrador', 'usuarioCoordinador']);

        if ($startDate && $endDate) {
            $query->whereBetween('fecha_reporte', [ Carbon::parse($startDate)->startOfDay(), Carbon::parse($endDate)->endOfDay() ]);
        }

        $incidencias = $query->get();
        return $this->procesarDatos($incidencias, $startDate, $endDate);
    }

    protected function procesarDatos($incidencias, $startDate, $endDate)
    {
        if ($incidencias->isEmpty()) {
            return [
                'resumen' => ['total_incidencias' => 0, 'incidencias_resueltas' => 0, 'porcentaje_resueltas' => 0, 'tiempo_promedio_resolucion' => null, 'rango_fechas' => 'N/A'],
                'detalle' => [], 'incidenciasFrecuentes' => collect(), 'tiemposPorTipo' => collect(),
            ];
        }
        
        $incidenciasResueltas = $incidencias->filter(fn ($incidencia) => $incidencia->estado_actual === 'Resuelto');
        $totalIncidencias = $incidencias->count();

        $resumen = [
            'total_incidencias' => $totalIncidencias,
            'incidencias_resueltas' => $incidenciasResueltas->count(),
            'porcentaje_resueltas' => $totalIncidencias > 0 ? ($incidenciasResueltas->count() / $totalIncidencias) * 100 : 0,
            'tiempo_promedio_resolucion' => $incidenciasResueltas->avg(function ($incidencia) {
                $resolucionFinal = $incidencia->resoluciones->where('estado', 'Resuelto')->first();
                return $resolucionFinal ? $incidencia->fecha_reporte->diffInHours($resolucionFinal->fecha_resolucion) : null;
            }),
             'rango_fechas' => $startDate && $endDate ? Carbon::parse($startDate)->format('d/m/Y') . ' - ' . Carbon::parse($endDate)->format('d/m/Y') : 'Todos los registros'
        ];

        $detalle = $incidencias->groupBy('tipo_experiencia')->map(function ($group, $tipo) {
            $resueltasEnGrupo = $group->filter(fn($i) => $i->estado_actual === 'Resuelto');
            $tiempoPromedioGrupo = $resueltasEnGrupo->avg(function ($item) {
                $resolucionFinal = $item->resoluciones->where('estado', 'Resuelto')->first();
                return $resolucionFinal ? $item->fecha_reporte->diffInHours($resolucionFinal->fecha_resolucion) : null;
            });
            return [
                'tipo_experiencia' => $tipo,
                'total' => $group->count(),
                'coordinador_nombre' => $group->first()->usuarioCoordinador->nombre ?? 'N/A',
                'estado' => $resueltasEnGrupo->isNotEmpty() ? 'Resuelto' : 'Pendiente',
                'tiempo_promedio' => $tiempoPromedioGrupo,
            ];
        });

        $incidenciasFrecuentes = $incidencias->groupBy('tipo_experiencia')->map->count()->sortDesc()->take(5);
        $tiemposPorTipo = $detalle->pluck('tiempo_promedio', 'tipo_experiencia');

        return [
            'resumen' => $resumen,
            'detalle' => $detalle->values(),
            'incidenciasFrecuentes' => $incidenciasFrecuentes,
            'tiemposPorTipo' => $tiemposPorTipo,
        ];
    }
}