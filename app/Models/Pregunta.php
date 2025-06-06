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

    protected $casts = [
        'fecha_pub' => 'datetime',
        'fecha_act' => 'datetime',
    ];

    public function usuarioUser()
    {
        return $this->belongsTo(Usuario::class, 'usuario', 'id_usuario');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->fecha_act = now(); // Establece la fecha actual al momento de la creación
        });

        static::updating(function ($model) {
            $model->fecha_act = now(); // Establece la fecha actual al momento de la actualización
        });
    }
}
