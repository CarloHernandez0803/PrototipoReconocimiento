<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    use HasFactory;

    protected $table = 'Preguntas_Respuestas';
    protected $primaryKey = 'id_pregunta';
    public $timestamps = false;

    protected $fillable = [
        'titulo',
        'descripcion',
        'categoria',
        'estado',
        'respuesta',
        'fecha_pub',
        'fecha_act',
        'usuario',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario', 'id_usuario');
    }
}
