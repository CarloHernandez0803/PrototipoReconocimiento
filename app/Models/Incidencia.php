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
        'tipo_experiencia',
        'descripcion',
        'fecha_reporte',
        'coordinador',
    ];

<<<<<<< HEAD
=======
    protected $casts = [
        'fecha_reporte' => 'datetime',
    ];

>>>>>>> 202c96f (Quinta version proyecto)
    public function coordinador()
    {
        return $this->belongsTo(Usuario::class, 'coordinador', 'id_usuario');
    }
}
