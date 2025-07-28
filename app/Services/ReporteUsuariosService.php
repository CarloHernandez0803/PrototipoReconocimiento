<?php

namespace App\Services;

use App\Models\Solicitud;
use App\Models\Usuario;
use Carbon\Carbon;

class ReporteUsuariosService
{
    public function generarReporte($startDate = null, $endDate = null)
    {
        $query = Solicitud::with(['usuarioAlumno', 'usuarioCoordinador', 'usuarioAdministrador']);

        if ($startDate && $endDate) {
            $query->whereBetween('fecha_solicitud', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }

        $solicitudes = $query->get();
        return $this->procesarDatos($solicitudes, $startDate, $endDate);
    }

    protected function procesarDatos($solicitudes, $startDate, $endDate)
    {
        $usuarios = collect();

        foreach ($solicitudes as $solicitud) {
            $tiempo = null;
            if ($solicitud->fecha_respuesta) {
                $tiempo = $solicitud->fecha_solicitud->diffInHours($solicitud->fecha_respuesta);
            }
            if ($solicitud->usuarioAlumno) $this->acumularDatosUsuario($usuarios, $solicitud->usuarioAlumno, $tiempo);
            if ($solicitud->usuarioCoordinador) $this->acumularDatosUsuario($usuarios, $solicitud->usuarioCoordinador, $tiempo);
            if ($solicitud->usuarioAdministrador) $this->acumularDatosUsuario($usuarios, $solicitud->usuarioAdministrador, $tiempo);
        }

        $detalleUsuarios = $usuarios->map(function ($usuario) {
            if (!empty($usuario['tiempos'])) {
                $usuario['tiempo_promedio'] = collect($usuario['tiempos'])->avg();
            }
            unset($usuario['tiempos']);
            return $usuario;
        });

        // --- CORRECCIÓN CLAVE AQUÍ ---
        // Añadimos ->pluck() para formatear los datos como la vista espera (nombre => total)
        $usuariosMasActivos = $detalleUsuarios
            ->sortByDesc('total_actividades')
            ->take(5)
            ->pluck('total_actividades', 'nombre_completo');
        // -----------------------------

        $tiempoPromedioGeneral = $detalleUsuarios->whereNotNull('tiempo_promedio')->avg('tiempo_promedio');

        $resumen = [
            'total_actividades' => $detalleUsuarios->sum('total_actividades'),
            'tiempo_promedio_general' => $tiempoPromedioGeneral,
            'usuarios_activos' => $detalleUsuarios->count(),
            'rango_fechas' => $startDate && $endDate ? Carbon::parse($startDate)->format('d/m/Y') . ' - ' . Carbon::parse($endDate)->format('d/m/Y') : 'Todos los registros'
        ];

        $tiempoPorRol = $detalleUsuarios->groupBy('rol')->map->avg('tiempo_promedio');

        return [
            'resumen' => $resumen,
            'usuarios_mas_activos' => $usuariosMasActivos,
            'tiempo_promedio_por_rol' => $tiempoPorRol,
            'detalle_usuarios' => $detalleUsuarios->values()
        ];
    }

    protected function acumularDatosUsuario($usuarios, $usuario, $tiempo)
    {
        $id = $usuario->id_usuario;
        if (!$usuarios->has($id)) {
            $usuarios->put($id, [
                'id_usuario' => $id,
                'nombre_completo' => trim($usuario->nombre . ' ' . $usuario->apellidos),
                'rol' => $usuario->rol,
                'total_actividades' => 0,
                'tiempo_promedio' => null,
                'tiempos' => []
            ]);
        }
        $userData = $usuarios->get($id);
        $userData['total_actividades']++;
        if ($tiempo !== null) {
            $userData['tiempos'][] = abs($tiempo); // Usar abs() para evitar tiempos negativos
        }
        $usuarios->put($id, $userData);
    }
}