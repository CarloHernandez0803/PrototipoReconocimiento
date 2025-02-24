<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Listeners\EnviarNotificacionUsuarioCreado;
use App\Events\UsuarioCreado;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UsuarioCreado::class => [
            EnviarNotificacionUsuarioCreado::class,
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
