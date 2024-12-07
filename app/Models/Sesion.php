<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sesion extends Model
{
    use HasFactory;
<<<<<<< HEAD
    
=======

>>>>>>> 202c96f (Quinta version proyecto)
    protected $table = 'Sesiones';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'token_sesion',
        'fecha_inicio',
        'fecha_fin',
        'usuario'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario', 'id_usuario');
    }

    public function getKeyName()
    {
        return 'id';
    }
}
