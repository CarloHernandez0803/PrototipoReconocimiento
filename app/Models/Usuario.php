<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

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
    ];

    public function setContraseñaAttribute($value)
    {
        $this->attributes['contraseña'] = Hash::make($value);
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
