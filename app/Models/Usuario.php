<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;

class Usuario extends Model implements AuthenticatableContract
{
    use HasFactory, Authenticatable, Notifiable;

    protected $table = 'Usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'apellidos',
        'correo',
        'contraseña',
        'rol',
        'remember_token',
    ];

    protected $hidden = [
        'contraseña',
        'remember_token',
    ];

    protected $casts = [
        'fecha_registro' => 'datetime',
    ];

    public function getAuthPassword()
    {
        return $this->contraseña;
    }

    public function routeNotificationForMail($notification)
    {
        return $this->correo;
    }

    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} {$this->apellido}";
    }
    
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

    public function incidenciasReportadas()
    {
        return $this->hasMany(Incidencia::class, 'coordinador', 'id_usuario');
    }

    public function resoluciones()
    {
        return $this->hasMany(Resolucion::class, 'usuario_resolucion', 'id_usuario');
    }
}
