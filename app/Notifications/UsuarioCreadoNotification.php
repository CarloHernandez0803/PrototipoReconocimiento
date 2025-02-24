<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UsuarioCreadoNotification extends Notification
{
    protected $usuario;

    public function __construct($usuario)
    {
        $this->usuario = $usuario;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        \Log::info('Preparando correo para el usuario:', ['usuario' => $this->usuario->id_usuario]);

        return (new MailMessage)
            ->subject('¡Bienvenido a ' . config('app.name') . '!')
            ->markdown('emails.usuario_creado', ['usuario' => $this->usuario]);
    }
}