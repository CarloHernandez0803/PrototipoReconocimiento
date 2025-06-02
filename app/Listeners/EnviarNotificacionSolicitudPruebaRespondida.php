<?php

namespace App\Listeners;

use App\Events\SolicitudPruebaRespondida;
use App\Notifications\SolicitudPruebaRespondidaNotification;

class EnviarNotificacionSolicitudPruebaRespondida
{
    public function __construct()
    {
        //
    }

    public function handle(SolicitudPruebaRespondida $event)
    {
        $event->solicitud->usuarioCoordinador->notify(new SolicitudPruebaRespondidaNotification($event->solicitud));

        $event->solicitud->usuarioAlumno->notify(new SolicitudPruebaRespondidaNotification($event->solicitud));
    }
}