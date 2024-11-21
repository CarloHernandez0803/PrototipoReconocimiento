<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    use HasFactory;

    protected $table = 'Evaluaciones_Red';
    protected $primaryKey = 'id_evaluacion';
    public $timestamps = false;

    protected $fillable = [
        'categoria_senal',
        'senales_correctas',
        'senales_totales',
        'calificacion_media',
        'comentarios',
        'fecha_evaluacion',
        'alumno',
    ];

    public function alumno()
    {
        return $this->belongsTo(Usuario::class, 'alumno', 'id_usuario');
    }
}
