<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use App\Models\ExperienciaUsuario;

class ExperienciaUsuarioRegistrada
{
    use Dispatchable;

    public $experiencia;

    public function __construct(ExperienciaUsuario $experiencia)
    {
        $this->experiencia = $experiencia;
    }
}