<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Solicitud;

class SolicitudPruebaRecibidaNotification extends Notification
{
    protected $solicitud;

    public function __construct(Solicitud $solicitud)
    {
        $this->solicitud = $solicitud;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nueva Solicitud de Prueba Recibida')
            ->markdown('emails.solicitud_prueba_recibida', ['solicitud' => $this->solicitud]);
    }
}