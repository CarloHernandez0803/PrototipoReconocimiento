<?php

namespace Database\Factories;

use App\Models\Solicitud;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SolicitudFactory extends Factory
{
    protected $model = Solicitud::class;

    public function definition(): array
    {
        return [
            'estado' => $this->faker->randomElement(['Pendiente', 'Aprobada']),
            'fecha_solicitud' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'fecha_respuesta' => function (array $attributes) {
                return $attributes['estado'] === 'Aprobada' ? now() : null;
            },
            'administrador' => function () {
                return \App\Models\Usuario::where('rol', 'Administrador')->inRandomOrder()->first()->id_usuario;
            },
            'coordinador' => function () {
                return \App\Models\Usuario::where('rol', 'Coordinador')->inRandomOrder()->first()->id_usuario;
            },
            'alumno' => function () {
                return \App\Models\Usuario::where('rol', 'Alumno')->inRandomOrder()->first()->id_usuario;
            },
        ];
    }
}