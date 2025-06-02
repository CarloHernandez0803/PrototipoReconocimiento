<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use App\Models\Resolucion;

class SeguimientoFalloActualizado
{
    use Dispatchable;

    public $resolucion;

    public function __construct(Resolucion $resolucion)
    {
        $this->resolucion = $resolucion;
    }
}