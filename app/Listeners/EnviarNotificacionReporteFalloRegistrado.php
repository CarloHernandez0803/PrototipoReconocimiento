<?php

namespace App\Listeners;

use App\Events\ReporteFalloRegistrado;
use App\Notifications\ReporteFalloRegistradoNotification;
use App\Models\Usuario;

class EnviarNotificacionReporteFalloRegistrado
{
    public function __construct()
    {
        //
    }

    public function handle(ReporteFalloRegistrado $event)
    {
        $administradores = Usuario::where('rol', 'Administrador')->get();
        foreach ($administradores as $admin) {
            $admin->notify(new ReporteFalloRegistradoNotification($event->incidencia));
        }
    }
}