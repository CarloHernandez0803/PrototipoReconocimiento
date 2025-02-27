<?php

namespace App\Listeners;

use App\Events\SolicitudPruebaRecibida;
use App\Notifications\SolicitudPruebaRecibidaNotification;

class EnviarNotificacionSolicitudPruebaRecibida
{
    public function __construct()
    {
        //
    }

    public function handle(SolicitudPruebaRecibida $event)
    {
        $administradores = Usuario::where('rol', 'Administrador')->get();
        foreach ($administradores as $admin) {
            $admin->notify(new SolicitudPruebaRecibidaNotification($event->solicitud));
        }

        $event->solicitud->alumno->notify(new SolicitudPruebaRecibidaNotification($event->solicitud));
    }
}