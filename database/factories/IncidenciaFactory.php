<?php

namespace Database\Factories;

use App\Models\Incidencia;
use Illuminate\Database\Eloquent\Factories\Factory;

class IncidenciaFactory extends Factory
{
    protected $model = Incidencia::class;

    public function definition(): array
    {
        return [
            'tipo_experiencia' => $this->faker->randomElement([
                'Error de Sistema',
                'Problema de Rendimiento',
                'Fallo de Seguridad',
                'Actualizaciones Fallidas',
                'Incidencias en Datos',
                'Problema de Usabilidad',
                'Solicitudes de Mejora',
                'Otros'
            ]),
            'descripcion' => $this->faker->paragraph(),
        ];
    }
}