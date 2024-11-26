<?php

namespace Database\Factories;

use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UsuarioFactory extends Factory
{
    protected $model = Usuario::class;

    public function definition(): array
    {
        return [
            'nombre' => $this->faker->firstName(),
            'apellidos' => $this->faker->lastName(),
            'correo' => $this->faker->unique()->safeEmail(),
            'contraseña' => Hash::make('password'),
            'rol' => $this->faker->randomElement(['Administrador', 'Coordinador', 'Alumno']),
        ];
    }
}