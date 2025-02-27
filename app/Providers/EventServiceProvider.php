<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Listeners\EnviarNotificacionUsuarioCreado;
use App\Events\UsuarioCreado;
use App\Listeners\EnviarNotificacionAdminCreado;
use App\Listeners\EnviarNotificacionSolicitudPruebaRecibida;
use App\Events\SolicitudPruebaRecibida;
use App\Listeners\EnviarNotificacionSolicitudPruebaRespondida;
use App\Events\SolicitudPruebaRespondida;
use App\Listeners\EnviarNotificacionExperienciaUsuarioRegistrada;
use App\Events\ExperienciaUsuarioRegistrada;
use App\Listeners\EnviarNotificacionReporteFalloRegistrado;
use App\Events\ReporteFalloRegistrado;
use App\Listeners\EnviarNotificacionSeguimientoFalloActualizado;
use App\Events\SeguimientoFalloActualizado;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UsuarioCreado::class => [
            EnviarNotificacionUsuarioCreado::class,
        ],

        SolicitudPruebaRecibida::class => [
            EnviarNotificacionSolicitudPruebaRecibida::class,
        ],

        SolicitudPruebaRespondida::class => [
            EnviarNotificacionSolicitudPruebaRespondida::class,
        ],

        ExperienciaUsuarioRegistrada::class => [
            EnviarNotificacionExperienciaUsuarioRegistrada::class,
        ],

        ReporteFalloRegistrado::class => [
            EnviarNotificacionReporteFalloRegistrado::class,
        ],

        SeguimientoFalloActualizado::class => [
            EnviarNotificacionSeguimientoFalloActualizado::class,
        ],
    ];

    public function boot(): void
    {
        //
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
