<?php

namespace Database\Factories;

use App\Models\Experiencia;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExperienciaFactory extends Factory
{
    protected $model = Experiencia::class;

    public function definition(): array
    {
        return [
            'tipo_experiencia' => $this->faker->randomElement([
                'Positiva',
                'Negativa',
                'Neutra'
            ]),
            'descripcion' => $this->faker->paragraph(),
            'impacto' => $this->faker->randomElement([
                'Alto',
                'Medio',
                'Bajo'
            ]),
        ];
    }
}