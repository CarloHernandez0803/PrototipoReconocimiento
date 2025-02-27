<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use App\Models\Incidencia;

class ReporteFalloRegistrado
{
    use Dispatchable;

    public $incidencia;

    public function __construct(Incidencia $incidencia)
    {
        $this->incidencia = $incidencia;
    }
}