<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use App\Models\ResolucionIncidencias;

class SeguimientoFalloActualizado
{
    use Dispatchable;

    public $resolucion;

    public function __construct(ResolucionIncidencias $resolucion)
    {
        $this->resolucion = $resolucion;
    }
}