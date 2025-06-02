<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Experiencia;

class ExperienciaUsuarioRegistradaNotification extends Notification
{
    protected $experiencia;

    public function __construct(Experiencia $experiencia)
    {
        $this->experiencia = $experiencia;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nueva Experiencia de Usuario Registrada')
            ->markdown('emails.experiencia_usuario_registrada', ['experiencia' => $this->experiencia]);
    }
}