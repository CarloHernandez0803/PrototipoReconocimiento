<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Incidencia;

class ReporteFalloRegistradoNotification extends Notification
{
    protected $incidencia;

    public function __construct(Incidencia $incidencia)
    {
        $this->incidencia = $incidencia;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nuevo Reporte de Fallo Registrado')
            ->markdown('emails.reporte_fallo_registrado', ['incidencia' => $this->incidencia]);
    }
}