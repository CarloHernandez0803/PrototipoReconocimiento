<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prueba extends Model
{
    use HasFactory;
    
    protected $fillable = ['historial_id', 'imagen_path', 'estado', 'resultado'];
    protected $casts = ['resultado' => 'array'];
}