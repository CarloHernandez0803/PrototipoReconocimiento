<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Usuarios', function (Blueprint $table) { 
            $table->id('id_usuario'); 
            $table->string('nombre', 45); 
            $table->string('apellidos', 60); 
            $table->string('correo', 45); 
            $table->string('contraseña', 30); 
            $table->enum('rol', [
                'Administrador', 
                'Coordinador', 
                'Alumno'
            ]);
            $table->timestamp('fecha_registro')->useCurrent(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Usuarios');
    }
};
