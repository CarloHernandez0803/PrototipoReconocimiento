<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use App\Models\Solicitud;

class SolicitudPruebaRespondida
{
    use Dispatchable;

    public $solicitud;

    public function __construct(Solicitud $solicitud)
    {
        $this->solicitud = $solicitud;
    }
}