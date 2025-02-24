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
        \Log::info('Evento UsuarioCreado disparado', ['usuario' => $event->usuario->id_usuario]);

        if ($event->usuario) {
            \Log::info('Usuario no es nulo', ['usuario' => $event->usuario]);
            $event->usuario->notify(new UsuarioCreadoNotification($event->usuario));
            \Log::info('Notificación enviada');
        } else {
            \Log::error('Usuario es nulo');
        }
    }
}
