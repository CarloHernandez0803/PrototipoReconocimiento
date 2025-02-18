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

            return response()->json($eventos);
        } catch (\Exception $e) {
            // Log del error para depuración
            \Log::error('Error al obtener eventos: ' . $e->getMessage());
            return response()->json(['error' => 'Error al obtener eventos'], 500);
        }
    }
}
