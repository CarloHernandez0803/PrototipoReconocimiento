<?php

namespace Database\Factories;

use App\Models\Evaluacion;
use Illuminate\Database\Eloquent\Factories\Factory;

class EvaluacionFactory extends Factory
{
    protected $model = Evaluacion::class;

    public function definition(): array
    {
        $senales_totales = $this->faker->numberBetween(10, 25);
        $senales_correctas = $this->faker->numberBetween(0, $senales_totales);
        $calificacion_media = round(($senales_correctas / $senales_totales) * 100);

        return [
            'categoria_senal' => $this->faker->randomElement([
                'Semáforo',
                'Restrictiva',
                'Advertencia',
                'Tráfico',
                'Informativa'
            ]),
            'senales_correctas' => $senales_correctas,
            'senales_totales' => $senales_totales,
            'calificacion_media' => $calificacion_media,
            'comentarios' => $this->faker->paragraph(),
        ];
    }
}