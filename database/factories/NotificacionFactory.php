<?php

namespace Database\Factories;

use App\Models\Notificacion;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificacionFactory extends Factory
{
    protected $model = Notificacion::class;

    public function definition(): array
    {
        return [
            'tipo_notificacion' => $this->faker->randomElement([
                'Creación de Cuenta',
                'Recepción de Solicitud de Prueba',
                'Respuesta a Solicitud de Prueba',
                'Experimentación',
                'Evaluación Red Neuronal',
                'Registro de Reporte de Fallo',
                'Seguimiento de Fallo'
            ]),
            'contenido' => $this->faker->paragraph(),
        ];
    }
}