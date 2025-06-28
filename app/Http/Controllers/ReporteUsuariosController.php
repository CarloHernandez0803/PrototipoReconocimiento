<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Solicitud;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteUsuariosController extends Controller
{
    public function index(Request $request)
    {
        try {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            // Base query con relaciones
            $query = Solicitud::with([
                'usuarioAlumno',
                'usuarioCoordinador',
                'usuarioAdministrador'
            ])
            ->select([
                'solicitudes_prueba.*',
                DB::raw('CONCAT(alumno.nombre, " ", alumno.apellidos) as alumno_nombre'),
                DB::raw('CONCAT(coordinador.nombre, " ", coordinador.apellidos) as coordinador_nombre'),
                DB::raw('CONCAT(administrador.nombre, " ", administrador.apellidos) as administrador_nombre')
            ])
            ->leftJoin('usuarios as alumno', 'solicitudes_prueba.alumno', '=', 'alumno.id_usuario')
            ->leftJoin('usuarios as coordinador', 'solicitudes_prueba.coordinador', '=', 'coordinador.id_usuario')
            ->leftJoin('usuarios as administrador', 'solicitudes_prueba.administrador', '=', 'administrador.id_usuario');

            // Aplicar filtros de fecha si existen
            if ($startDate && $endDate) {
                $query->whereBetween('solicitudes_prueba.fecha_solicitud', [
                    Carbon::parse($startDate)->startOfDay(),
                    Carbon::parse($endDate)->endOfDay()
                ]);
            }

            $solicitudes = $query->get();

            // Procesamiento de datos
            $usuarios = $this->procesarUsuarios($solicitudes);
            $totalActividades = $solicitudes->count();
            $tiempoPromedioGeneral = $solicitudes->avg(function($item) {
                return $item->fecha_respuesta ? 
                    Carbon::parse($item->fecha_solicitud)->diffInHours(Carbon::parse($item->fecha_respuesta)) : 
                    null;
            });

            // Obtener usuarios más activos (top 5)
            $usuariosMasActivos = collect($usuarios)
                ->sortByDesc('total_actividades')
                ->take(5);

            // Calcular tiempo promedio por rol
            $tiempoPorRol = [
                'Alumno' => collect($usuarios)
                    ->where('rol', 'Alumno')
                    ->avg('tiempo_promedio'),
                'Coordinador' => collect($usuarios)
                    ->where('rol', 'Coordinador')
                    ->avg('tiempo_promedio'),
                'Administrador' => collect($usuarios)
                    ->where('rol', 'Administrador')
                    ->avg('tiempo_promedio')
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'resumen' => [
                        'total_actividades' => $totalActividades,
                        'tiempo_promedio_general' => $tiempoPromedioGeneral ? round(abs($tiempoPromedioGeneral), 2) : null,
                        'usuarios_activos' => count($usuarios),
                        'rango_fechas' => $startDate && $endDate ? 
                            Carbon::parse($startDate)->format('d/m/Y').' - '.Carbon::parse($endDate)->format('d/m/Y') : 
                            'Todos los registros'
                    ],
                    'usuarios_mas_activos' => $usuariosMasActivos,
                    'tiempo_promedio_por_rol' => $tiempoPorRol,
                    'detalle_usuarios' => array_values($usuarios)
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en ReporteUsuariosController: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al generar el reporte',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    protected function procesarUsuarios($solicitudes)
    {
        $usuarios = [];

        foreach ($solicitudes as $solicitud) {
            // Procesar alumno
            if ($solicitud->usuarioAlumno) {
                $this->procesarUsuario($usuarios, $solicitud->usuarioAlumno, $solicitud, 'Alumno');
            }

            // Procesar coordinador
            if ($solicitud->usuarioCoordinador) {
                $this->procesarUsuario($usuarios, $solicitud->usuarioCoordinador, $solicitud, 'Coordinador');
            }

            // Procesar administrador
            if ($solicitud->usuarioAdministrador) {
                $this->procesarUsuario($usuarios, $solicitud->usuarioAdministrador, $solicitud, 'Administrador');
            }
        }

        return $usuarios;
    }

    protected function procesarUsuario(&$usuarios, $usuario, $solicitud, $rol)
    {
        $id = $usuario->id_usuario;

        if (!isset($usuarios[$id])) {
            $usuarios[$id] = [
                'id_usuario' => $id,
                'nombre_completo' => trim($usuario->nombre . ' ' . $usuario->apellidos),
                'rol' => $rol,
                'total_actividades' => 0,
                'tiempo_promedio' => 0,
                'tiempos' => []
            ];
        }

        $usuarios[$id]['total_actividades']++;

        if ($solicitud->fecha_respuesta) {
            $tiempo = Carbon::parse($solicitud->fecha_solicitud)
                ->diffInHours(Carbon::parse($solicitud->fecha_respuesta));
            $usuarios[$id]['tiempos'][] = $tiempo;
            $usuarios[$id]['tiempo_promedio'] = array_sum($usuarios[$id]['tiempos']) / count($usuarios[$id]['tiempos']);
        }
    }

    public function downloadPDF(Request $request)
    {
        try {
            $response = $this->index($request);
            $data = $response->getData();

            if (!$data->success) {
                throw new \Exception($data->message ?? 'Error al generar el reporte');
            }

            $pdf = Pdf::loadView('reportes.usuarios', [
                'resumen' => (array)$data->data->resumen,
                'usuarios_mas_activos' => $data->data->usuarios_mas_activos,
                'tiempo_promedio_por_rol' => (array)$data->data->tiempo_promedio_por_rol,
                'detalle' => $data->data->detalle_usuarios
            ]);

            $fileName = 'reporte_incidencias_' . now()->format('Ymd_His') . '.pdf';

            return $pdf->download($fileName);

        } catch (\Exception $e) {
            \Log::error('Error al generar PDF: ' . $e->getMessage());
            return back()->with('error', 'Error al generar el PDF: ' . $e->getMessage());
        }
    }
}