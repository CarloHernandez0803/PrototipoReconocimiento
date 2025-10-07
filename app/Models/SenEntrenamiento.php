<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SenEntrenamiento extends Model
{
    use HasFactory;

    protected $table = 'Senalamientos_Entrenamiento';
    protected $primaryKey = 'id_senalamiento_entrenamiento';
    public $timestamps = false;

    protected $fillable = [
        'nombre_lote',
        'descripcion',
        'rutas',
        'categoria',
        'fecha_creacion',
        'usuario',
    ];

    protected $casts = [
        'fecha_creacion' => 'datetime',
    ];

    public function getRutasAttribute($value)
    {
        $decoded = is_array($value) ? $value : (json_decode($value, true) ?? []);

        return array_map(function ($item) {
            return is_array($item) ? $item : [
                'ruta_relativa' => (string) $item,
                'url_publica' => rtrim(env('FTP_BASE_URL'), '/').'/'.ltrim($item, '/')
            ];
        }, $decoded);
    }

    public function usuarioRelacion()
    {
        return $this->belongsTo(Usuario::class, 'usuario', 'id_usuario');
    }
}
