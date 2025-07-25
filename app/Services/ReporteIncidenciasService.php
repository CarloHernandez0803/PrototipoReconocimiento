<?php

namespace App\Services;

use App\Models\Incidencia;
use App\Models\ResolucionIncidencia;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReporteIncidenciasService
{
    public function generarReporte($startDate = null, $endDate = null)
    {
        // Validar y formatear fechas de inicio y fin de rango de fechas
        $fechas = $this->validarFechas($startDate, $endDate);

        // Obtener datos de incidencias con resoluciones y coordinadores relacionados en una sola consulta de base de datos
        $query = Incidencia::query()
            ->select([
                'Incidencias.id_incidencia',
                'Incidencias.tipo_experiencia',
                'Incidencias.fecha_reporte',
                'Resolucion_Incidencias.fecha_resolucion',
                'Resolucion_Incidencias.estado',
                DB::raw('CONCAT(u1.nombre, " ", u1.apellidos) as reportado_por')
            ])
            ->leftJoin('Resolucion_Incidencias', 'Incidencias.id_incidencia', '=', 'Resolucion_Incidencias.incidencia')
            ->leftJoin('Usuarios as u1', 'Incidencias.coordinador', '=', 'u1.id_usuario');

        // Aplicar filtros de fecha si existen
        if ($fechas['startDate'] && $fechas['endDate']) {
            $query->whereBetween('Incidencias.fecha_reporte', [
                $fechas['startDate'],
                $fechas['endDate']
            ]);
        }

        // Ejecutar consulta y obtener resultados
        $incidencias = $query->get();

        // Procesar datos
        return $this->procesarDatos($incidencias);
    }

    protected function validarFechas($startDate, $endDate)
    {
        try {
            $start = $startDate ? Carbon::parse($startDate)->startOfDay() : null;
            $end = $endDate ? Carbon::parse($endDate)->endOfDay() : null;

            // Validar que la fecha final no sea anterior a la inicial
            if ($start && $end && $end->lt($start)) {
                throw new \Exception("La fecha final no puede ser anterior a la fecha inicial");
            }

            return [
                'startDate' => $start,
                'endDate' => $end
            ];
        } catch (\Exception $e) {
            throw new \Exception("Formato de fecha inválido: " . $e->getMessage());
        }
    }

    protected function procesarDatos($incidencias)
    {
        if ($incidencias->isEmpty()) {
            return [
                'resumen' => $this->resumenVacio(),
                'detalle' => []
            ];
        }

        // Calcular tiempos de resolución solo para incidencias resueltas
        $incidenciasResueltas = $incidencias->where('estado', 'Resuelto')
            ->whereNotNull('fecha_resolucion');

        $tiemposResolucion = $incidenciasResueltas->map(function ($incidencia) {
            return Carbon::parse($incidencia->fecha_reporte)
                ->diffInHours(Carbon::parse($incidencia->fecha_resolucion));
        });

        // Agrupar por tipo de experiencia
        $agrupado = $incidencias->groupBy('tipo_experiencia');

        $detalle = $agrupado->map(function ($grupo, $tipo) {
            $resueltas = $grupo->where('estado', 'Resuelto');
            
            return [
                'tipo' => $tipo,
                'total' => $grupo->count(),
                'resueltas' => $resueltas->count(),
                'tiempo_promedio' => $resueltas->avg(function ($item) {
                    return Carbon::parse($item->fecha_reporte)
                        ->diffInHours(Carbon::parse($item->fecha_resolucion));
                })
            ];
        });

        return [
            'resumen' => [
                'total_incidencias' => $incidencias->count(),
                'incidencias_resueltas' => $incidenciasResueltas->count(),
                'porcentaje_resueltas' => $incidencias->count() > 0 
                    ? round(($incidenciasResueltas->count() / $incidencias->count()) * 100, 2)
                    : 0,
                'tiempo_promedio_resolucion' => $tiemposResolucion->isNotEmpty()
                    ? round($tiemposResolucion->avg(), 2)
                    : null,
            ],
            'detalle' => $detalle->values()->all()
        ];
    }

    protected function resumenVacio()
    {
        return [
            'total_incidencias' => 0,
            'incidencias_resueltas' => 0,
            'porcentaje_resueltas' => 0,
            'tiempo_promedio_resolucion' => null
        ];
    }
}