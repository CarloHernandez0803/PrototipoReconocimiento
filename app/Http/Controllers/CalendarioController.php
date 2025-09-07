<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Incidencia;
use App\Models\Solicitud;

class CalendarioController extends Controller
{
    public function index()
    {
        return view('calendario.index');
    }

    public function getEventos()
    {
        try {
            $user = auth()->user();
            $eventos = collect();

            if ($user->rol === 'Administrador') {
                // Administradores ven todas las incidencias y solicitudes
                $incidencias = Incidencia::select('id_incidencia as id', 'fecha_reporte as start', 'tipo_experiencia', 'descripcion')
                    ->get()
                    ->map(function ($incidencia) {
                        return [
                            'id' => $incidencia->id,
                            'title' => 'Incidencia. ' . $incidencia->tipo_experiencia,
                            'start' => $incidencia->start,
                            'color' => '#ff0000',
                            'extendedProps' => [
                                'tipo' => 'Incidencia. ' . $incidencia->tipo_experiencia,
                                'estado' => 'Reportada',
                                'descripcion' => $incidencia->descripcion
                            ]
                        ];
                    });

                $pruebas = Solicitud::select('id_solicitud as id', 'fecha_solicitud as start', 'estado')
                    ->get()
                    ->map(function ($prueba) {
                        return [
                            'id' => $prueba->id,
                            'title' => 'Solicitud de prueba',
                            'start' => $prueba->start,
                            'color' => '#00ff00',
                            'extendedProps' => [
                                'tipo' => 'Solicitud de Prueba',
                                'estado' => $prueba->estado
                            ]
                        ];
                    });

                $eventos = $incidencias->merge($pruebas);
            } elseif ($user->rol === 'Coordinador') {
                // Coordinadores solo ven sus propias solicitudes (sin incidencias)
                $pruebas = Solicitud::where('coordinador', $user->id_usuario)
                    ->select('id_solicitud as id', 'fecha_solicitud as start', 'estado')
                    ->get()
                    ->map(function ($prueba) {
                        return [
                            'id' => $prueba->id,
                            'title' => 'Solicitud de prueba',
                            'start' => $prueba->start,
                            'color' => '#00ff00',
                            'extendedProps' => [
                                'tipo' => 'Solicitud de Prueba',
                                'estado' => $prueba->estado
                            ]
                        ];
                    });

                $eventos = $pruebas;
            } else {
                // Para otros roles (como Alumno), no se muestra ningún evento
                $eventos = collect();
            }

            return response()->json($eventos);
        } catch (\Exception $e) {
            \Log::error('Error al obtener eventos: ' . $e->getMessage());
            return response()->json(['error' => 'Error al obtener eventos'], 500);
        }
    }
}