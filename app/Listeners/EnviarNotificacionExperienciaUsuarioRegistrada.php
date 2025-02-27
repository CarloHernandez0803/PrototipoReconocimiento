<?php

namespace App\Listeners;

use App\Events\ExperienciaUsuarioRegistrada;
use App\Notifications\ExperienciaUsuarioRegistradaNotification;

class EnviarNotificacionExperienciaUsuarioRegistrada
{
    public function __construct()
    {
        //
    }

    public function handle(ExperienciaUsuarioRegistrada $event)
    {
        $administradores = Usuario::where('rol', 'Administrador')->get();
        foreach ($administradores as $admin) {
            $admin->notify(new ExperienciaUsuarioRegistradaNotification($event->experiencia));
        }
    }
}