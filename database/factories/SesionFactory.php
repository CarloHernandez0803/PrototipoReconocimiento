<?php

namespace Database\Factories;

use App\Models\Sesion;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SesionFactory extends Factory
{
    protected $model = Sesion::class;

    public function definition(): array
    {
        return [
            'token_sesion' => Str::random(60),
            'fecha_fin' => null,
        ];
    }
}