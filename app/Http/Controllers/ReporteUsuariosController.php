<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Solicitud;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReporteUsuariosController extends Controller
{
    public function index(Request $request)
    {
        try {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            $query = Usuario::query();

            $baseQuery = $query->select([
                'usuarios.id_usuario',
                'usuarios.nombre',
                'usuarios.rol',
                DB::raw('COUNT(DISTINCT solicitudes_prueba.id_solicitud) as actividades'),
                DB::raw('AVG(CASE 
                    WHEN solicitudes_prueba.fecha_respuesta IS NOT NULL 
                    THEN TIMESTAMPDIFF(HOUR, solicitudes_prueba.fecha_solicitud, solicitudes_prueba.fecha_respuesta)
                    ELSE NULL 
                END) as tiempo_promedio')
            ])
            ->leftJoin('solicitudes_prueba', 'usuarios.id_usuario', '=', 'solicitudes_prueba.alumno');

            if ($startDate && $endDate) {
                $baseQuery->where(function($q) use ($startDate, $endDate) {
                    $q->whereBetween('solicitudes_prueba.fecha_solicitud', [
                        date('Y-m-d 00:00:00', strtotime($startDate)),
                        date('Y-m-d 23:59:59', strtotime($endDate))
                    ]);
                });
            }

            $usuarios = $baseQuery
                ->groupBy('usuarios.id_usuario', 'usuarios.nombre', 'usuarios.rol')
                ->get()
                ->map(function ($usuario) {
                    return [
                        'nombre' => $usuario->nombre,
                        'rol' => $usuario->rol,
                        'actividades' => (int)$usuario->actividades,
                        'tiempo_promedio' => $usuario->tiempo_promedio ? round($usuario->tiempo_promedio, 2) : null
                    ];
                });

            return response()->json($usuarios);

        } catch (\Exception $e) {
            Log::error('Error en ReporteUsuariosController: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Ha ocurrido un error al procesar la solicitud',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}