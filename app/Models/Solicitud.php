<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    use HasFactory;

    protected $table = 'Solicitudes_Prueba';
    protected $primaryKey = 'id_solicitud';
    public $timestamps = false;

    protected $fillable = [
        'estado',
        'fecha_solicitud',
        'fecha_respuesta',
        'administrador',
        'coordinador',
    ];

    public function administrador()
    {
        return $this->belongsTo(Usuario::class, 'administrador', 'id_usuario');
    }

    public function coordinador()
    {
        return $this->belongsTo(Usuario::class, 'coordinador', 'id_usuario');
    }
}
