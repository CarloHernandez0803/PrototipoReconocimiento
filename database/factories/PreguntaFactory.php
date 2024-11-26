<?php

namespace Database\Factories;

use App\Models\Pregunta;
use Illuminate\Database\Eloquent\Factories\Factory;

class PreguntaFactory extends Factory
{
    protected $model = Pregunta::class;

    public function definition(): array
    {
        return [
            'titulo' => rtrim($this->faker->sentence(), '.') . '?', // Removemos el punto final y agregamos ?
            'descripcion' => $this->faker->paragraph(),
            'categoria' => $this->faker->randomElement([
                'Funcionalidad del Sistema',
                'Reportes de Errores',
                'Solicitudes de Mejora',
                'Otros'
            ]),
            'estado' => $this->faker->randomElement([
                'Pendiente',
                'Respondida',
                'Resuelta'
            ]),
            'respuesta' => $this->faker->optional(0.7)->paragraph(), // 70% de probabilidad de tener respuesta
            'fecha_act' => $this->faker->optional(0.7)->dateTimeThisYear(), // Solo si tiene respuesta
        ];
    }
}