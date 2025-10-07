<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incidencia extends Model
{
    use HasFactory;

    protected $table = 'Incidencias';
    protected $primaryKey = 'id_incidencia';
    public $timestamps = false;

    protected $fillable = [
        'tipo_incidencia',
        'descripcion',
        'fecha_reporte',
        'coordinador',
    ];

    protected $casts = [
        'fecha_reporte' => 'datetime',
    ];

    public function resoluciones()
    {
        return $this->hasMany(Resolucion::class, 'incidencia', 'id_incidencia')->orderBy('fecha_resolucion', 'desc');
    }

    public function usuarioCoordinador()
    {
        return $this->belongsTo(Usuario::class, 'coordinador', 'id_usuario');
    }

    public function getEstadoActualAttribute()
    {
        return $this->resoluciones->first()->estado ?? 'Pendiente';
    }
}
