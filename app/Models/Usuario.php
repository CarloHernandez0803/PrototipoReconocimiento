<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Sesion;
use App\Models\Historial;
use App\Models\Evaluacion;
use App\Models\Notificacion;
use App\Models\SenPrueba;
use App\Models\SenEntrenamiento;
use App\Models\Incidencia;
use App\Models\Experiencia;
use App\Models\Solicitud;
use App\Models\Resolucion;
use App\Models\Pregunta;

class Usuario extends Model implements AuthenticatableContract
{
    use HasFactory, Authenticatable;

    protected $table = 'Usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'apellidos',
        'correo',
        'contraseña',
        'rol'
    ];

    protected $hidden = [
        'contraseña',
        'remember_token'
    ];

    protected $casts = [
        'fecha_registro' => 'datetime',
    ];

    public function setContraseñaAttribute($value)
    {
        $this->attributes['contraseña'] = Str::startsWith($value, '$2y$') 
            ? $value 
            : Hash::make($value);
    }

    public function verificarContraseña($plainPassword)
    {
        return Hash::check($plainPassword, $this->contraseña);
    }

    public function sesiones()
    {
        return $this->hasMany(Sesion::class, 'usuario', 'id_usuario');
    }

    public function evaluaciones()
    {
        return $this->hasMany(Evaluacion::class, 'alumno', 'id_usuario');
    }

    public function experiencias()
    {
        return $this->hasMany(Experiencia::class, 'usuario', 'id_usuario');
    }

    public function incidencias()
    {
        return $this->hasMany(Incidencia::class, 'coordinador', 'id_usuario');
    }

    public function solicitudes()
    {
        return $this->hasMany(Solicitud::class, 'alumno', 'id_usuario');
    }

    public function preguntas()
    {
        return $this->hasMany(Pregunta::class, 'usuario', 'id_usuario');
    }

    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class, 'usuario', 'id_usuario');
    }
}
