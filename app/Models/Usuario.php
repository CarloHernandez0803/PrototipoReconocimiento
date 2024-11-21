<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, Notifiable;
    use HasFactory;

    protected $table = 'Usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'apellidos',
        'correo',
        'contraseña',
        'rol',
    ];

    protected $hidden = [
        'contraseña',
    ];

    public function sesiones()
    {
        return $this->hasMany(Sesion::class, 'usuario', 'id_usuario');
    }

    public function solicitudesComoCoordinador()
    {
        return $this->hasMany(SolicitudPrueba::class, 'coordinador', 'id_usuario');
    }

    public function solicitudesComoAdministrador()
    {
        return $this->hasMany(SolicitudPrueba::class, 'administrador', 'id_usuario');
    }

    public function evaluaciones()
    {
        return $this->hasMany(EvaluacionRed::class, 'alumno', 'id_usuario');
    }

    public function experiencias()
    {
        return $this->hasMany(ExperienciaUsuario::class, 'usuario', 'id_usuario');
    }

    public function incidencias()
    {
        return $this->hasMany(Incidencia::class, 'coordinador', 'id_usuario');
    }

    public function resolucionesIncidencias()
    {
        return $this->hasMany(ResolucionIncidencia::class, 'administrador', 'id_usuario');
    }

    public function preguntasRespuestas()
    {
        return $this->hasMany(PreguntaRespuesta::class, 'usuario', 'id_usuario');
    }

    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class, 'usuario', 'id_usuario');
    }
}
