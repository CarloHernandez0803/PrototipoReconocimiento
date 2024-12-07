<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experiencia extends Model
{
    use HasFactory;

    protected $table = 'Experiencias_Usuario';
    protected $primaryKey = 'id_experiencia';
    public $timestamps = false;

    protected $fillable = [
        'tipo_experiencia',
        'descripcion',
        'impacto',
        'fecha_experiencia',
        'usuario',
    ];

<<<<<<< HEAD
=======
    protected $casts = [
        'fecha_experiencia' => 'datetime',
    ];

>>>>>>> 202c96f (Quinta version proyecto)
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario', 'id_usuario');
    }
}
