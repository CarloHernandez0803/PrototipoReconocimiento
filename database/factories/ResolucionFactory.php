<?php

namespace Database\Factories;

use App\Models\Resolucion;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResolucionFactory extends Factory
{
    protected $model = Resolucion::class;

    public function definition(): array
    {
        return [
            'estado' => $this->faker->randomElement([
                'Pendiente',
                'En Proceso',
                'Resuelto'
            ]),
            'fecha_resolucion' => function (array $attributes) {
                // Solo establecemos fecha si el estado es 'Resuelto'
                return $attributes['estado'] === 'Resuelto' 
                    ? $this->faker->dateTimeThisYear() 
                    : null;
            },
        ];
    }
}