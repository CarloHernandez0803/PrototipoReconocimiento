<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use App\Models\Experiencia;

class ExperienciaUsuarioRegistrada
{
    use Dispatchable;

    public $experiencia;

    public function __construct(Experiencia $experiencia)
    {
        $this->experiencia = $experiencia;
    }
}