<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Resolucion;

class Incidencia extends Model
{
    use HasFactory;

    protected $table = 'Incidencias';
    protected $primaryKey = 'id_incidencia';
    public $timestamps = false;

    protected $fillable = [
        'tipo_experiencia',
        'descripcion',
        'fecha_reporte',
        'coordinador',
    ];

    protected $casts = [
        'fecha_reporte' => 'datetime',
    ];

    public function resolucion()
    {
        return $this->hasOne(Resolucion::class, 'incidencia', 'id_incidencia');
    }

    public function coordinador()
    {
        return $this->belongsTo(Usuario::class, 'coordinador', 'id_usuario');
    }
}
