<?php

namespace Database\Factories;

use App\Models\Solicitud;
use Illuminate\Database\Eloquent\Factories\Factory;

class SolicitudFactory extends Factory
{
    protected $model = Solicitud::class;

    public function definition(): array
    {
        return [
            'estado' => $this->faker->randomElement([
                'Pendiente',
                'Aprobada'
            ]),
            'fecha_respuesta' => function (array $attributes) {
                // Solo establecemos fecha_respuesta si el estado es 'Aprobada'
                return $attributes['estado'] === 'Aprobada' 
                    ? now()
                    : null;
            },
        ];
    }
}