<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Incidencia;

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

    protected $casts = [
        'fecha_resolucion' => 'datetime',
    ];

    public function incidencia()
    {
        return $this->belongsTo(Incidencia::class, 'incidencia', 'id_incidencia');
    }
}
