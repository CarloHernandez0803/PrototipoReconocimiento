<?php

namespace App\Services;

use App\Models\Usuario;
use App\Models\Solicitud;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReporteUsuariosService
{
    public function generarReporte($startDate = null, $endDate = null)
    {
        try {
            // Validar fechas
            $fechas = $this->validarFechas($startDate, $endDate);

            $query = Usuario::query()
                ->select([
                    'usuarios.id_usuario',
                    'usuarios.nombre',
                    'usuarios.apellidos',
                    'usuarios.rol',
                    DB::raw('COUNT(DISTINCT solicitudes_prueba.id_solicitud) as total_actividades'),
                    DB::raw('AVG(
                        CASE 
                            WHEN solicitudes_prueba.fecha_respuesta IS NOT NULL 
                            AND solicitudes_prueba.fecha_solicitud IS NOT NULL
                            THEN TIMESTAMPDIFF(HOUR, solicitudes_prueba.fecha_solicitud, solicitudes_prueba.fecha_respuesta)
                            ELSE NULL 
                        END
                    ) as tiempo_promedio')
                ])
                ->leftJoin('solicitudes_prueba', 'usuarios.id_usuario', '=', 'solicitudes_prueba.alumno')
                ->groupBy('usuarios.id_usuario', 'usuarios.nombre', 'usuarios.apellidos', 'usuarios.rol');

            // Aplicar filtros de fecha si existen
            if ($fechas['startDate'] && $fechas['endDate']) {
                $query->whereBetween('solicitudes_prueba.fecha_solicitud', [
                    $fechas['startDate'],
                    $fechas['endDate']
                ]);
            }

            $usuarios = $query->get()
                ->map(function ($usuario) {
                    return [
                        'id_usuario' => $usuario->id_usuario,
                        'nombre_completo' => trim("{$usuario->nombre} {$usuario->apellidos}"),
                        'rol' => $usuario->rol,
                        'total_actividades' => (int)$usuario->total_actividades,
                        'tiempo_promedio' => $usuario->tiempo_promedio ? abs(round($usuario->tiempo_promedio, 2)) : null
                    ];
                });

            // Calcular métricas del resumen
            $resumen = [
                'total_actividades' => $usuarios->sum('total_actividades'),
                'tiempo_promedio_aprobacion' => $usuarios->avg('tiempo_promedio'),
                'usuarios_activos' => $usuarios->count(),
                'rango_fechas' => $fechas['rango_fechas']
            ];

            // Usuarios más activos (top 5)
            $usuariosMasActivos = $usuarios
                ->sortByDesc('total_actividades')
                ->take(5)
                ->pluck('total_actividades', 'nombre_completo');

            // Tiempo promedio por rol
            $tiempoPorRol = $usuarios
                ->groupBy('rol')
                ->map(function($group) {
                    return $group->avg('tiempo_promedio');
                });

            return [
                'resumen' => $resumen,
                'usuarios_mas_activos' => $usuariosMasActivos,
                'tiempo_promedio_por_rol' => $tiempoPorRol,
                'detalle_usuarios' => $usuarios
            ];

        } catch (\Exception $e) {
            throw new \Exception("Error al generar reporte: " . $e->getMessage());
        }
    }

    protected function validarFechas($startDate, $endDate)
    {
        try {
            $start = $startDate ? Carbon::parse($startDate)->startOfDay() : null;
            $end = $endDate ? Carbon::parse($endDate)->endOfDay() : null;

            if ($start && $end && $end->lt($start)) {
                throw new \Exception("La fecha final no puede ser anterior a la inicial");
            }

            return [
                'startDate' => $start,
                'endDate' => $end,
                'rango_fechas' => $start && $end ? 
                    $start->format('d/m/Y') . ' - ' . $end->format('d/m/Y') : 
                    'Todos los registros'
            ];
        } catch (\Exception $e) {
            throw new \Exception("Formato de fecha inválido: " . $e->getMessage());
        }
    }
}