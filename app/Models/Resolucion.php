<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resolucion extends Model
{
    use HasFactory;

    protected $table = 'Resolucion_Incidencias';
    protected $primaryKey = 'id_resolucion';
    public $timestamps = false;

    protected $fillable = [
        'estado',
        'fecha_resolucion',
        'incidencia',
    ];

    public function incidencia()
    {
        return $this->belongsTo(Incidencia::class, 'incidencia', 'id_incidencia');
    }
}
