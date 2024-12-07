<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SenEntrenamiento extends Model
{
    protected $table = 'Senalamientos_Entrenamiento';
    protected $primaryKey = 'id_senalamiento_entrenamiento';
    public $timestamps = false;

    protected $fillable = [
        'nombre_lote',
        'descripcion',
        'rutas',
        'categoria',
        'fecha_creacion',
    ];
}
