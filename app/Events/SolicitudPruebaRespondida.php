<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use App\Models\SolicitudPrueba;

class SolicitudPruebaRespondida
{
    use Dispatchable;

    public $solicitud;

    public function __construct(SolicitudPrueba $solicitud)
    {
        $this->solicitud = $solicitud;
    }
}