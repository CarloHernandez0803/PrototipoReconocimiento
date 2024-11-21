<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prueba extends Model
{
    protected $table = 'Senalamientos_Prueba';
    protected $primaryKey = 'id_senalamiento_prueba';
    public $timestamps = false;

    protected $fillable = [
        'nombre_lote',
        'descripcion',
        'rutas',
        'categoria',
        'fecha_creacion',
    ];
}
