<?php

namespace App\Events;

//use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
//use Illuminate\Queue\SerializesModels;
use App\Models\Usuario;

class UsuarioCreado
{
    use Dispatchable/*, InteractsWithSockets, SerializesModels*/;

    public $usuario;

    public function __construct(Usuario $usuario)
    {
        $this->usuario = $usuario;
    }
}
