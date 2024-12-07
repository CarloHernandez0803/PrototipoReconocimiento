<?php

namespace Database\Factories;

use App\Models\Solicitud;
use Illuminate\Database\Eloquent\Factories\Factory;
<<<<<<< HEAD
=======
use Illuminate\Support\Str;
>>>>>>> 202c96f (Quinta version proyecto)

class SolicitudFactory extends Factory
{
    protected $model = Solicitud::class;

    public function definition(): array
    {
        return [
<<<<<<< HEAD
            'estado' => $this->faker->randomElement([
                'Pendiente',
                'Aprobada'
            ]),
            'fecha_respuesta' => function (array $attributes) {
                // Solo establecemos fecha_respuesta si el estado es 'Aprobada'
                return $attributes['estado'] === 'Aprobada' 
                    ? now()
                    : null;
=======
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
>>>>>>> 202c96f (Quinta version proyecto)
            },
        ];
    }
}