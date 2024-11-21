<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    use HasFactory;

    protected $table = 'Notificaciones';
    protected $primaryKey = 'id_notificacion';
    public $timestamps = false;

    protected $fillable = [
        'tipo_notificacion',
        'contenido',
        'fecha_senvio',
        'usuario',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario', 'id_usuario');
    }
}
