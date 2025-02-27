<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\ResolucionIncidencias;

class SeguimientoFalloActualizadoNotification extends Notification
{
    protected $resolucion;

    public function __construct(ResolucionIncidencias $resolucion)
    {
        $this->resolucion = $resolucion;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Actualización en el Seguimiento del Fallo Reportado')
            ->markdown('emails.seguimiento_fallo_actualizado', ['resolucion' => $this->resolucion]);
    }
}