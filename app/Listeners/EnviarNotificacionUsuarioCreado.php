<?php

namespace App\Listeners;

use App\Events\UsuarioCreado;
use App\Notifications\UsuarioCreadoNotification;

class EnviarNotificacionUsuarioCreado
{
    public function __construct()
    {
        //
    }

    public function handle(UsuarioCreado $event)
    {
        $event->usuario->notify(new UsuarioCreadoNotification($event->usuario));
    }
}
