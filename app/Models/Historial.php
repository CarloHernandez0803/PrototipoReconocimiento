<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Historial extends Model
{
    protected $table = 'Historial_Entrenamiento';
    protected $primaryKey = 'id_historial';
    public $timestamps = false;

    protected $fillable = [
        'hiperparametros',
        'modelo',
        'pesos',
        'acierto',
        'perdida',
        'tiempo_entrenamiento',
        'fecha_creacion',
        'usuario',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario', 'id_usuario');
    }
}
