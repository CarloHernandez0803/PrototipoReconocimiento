<?php

namespace App\Listeners;

use App\Events\SeguimientoFalloActualizado;
use App\Notifications\SeguimientoFalloActualizadoNotification;

class EnviarNotificacionSeguimientoFalloActualizado
{
    public function __construct()
    {
        //
    }

    public function handle(SeguimientoFalloActualizado $event)
    {
        $event->resolucion->incidencia->coordinador->notify(new SeguimientoFalloActualizadoNotification($event->resolucion));
    }
}