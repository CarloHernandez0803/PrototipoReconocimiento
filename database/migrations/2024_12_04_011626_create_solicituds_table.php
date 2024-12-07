<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Solicitudes_Prueba', function (Blueprint $table) {
            $table->id('id_solicitud');
            $table->enum('estado', [
                'Pendiente',
                'Aprobada'
            ]);
            $table->timestamp('fecha_solicitud')->nullable();
            $table->timestamp('fecha_respuesta')->nullable()->default(null);
            $table->foreignId('administrador')
                  ->nullable()
                  ->constrained('Usuarios', 'id_usuario')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
            $table->foreignId('coordinador')
                  ->constrained('Usuarios', 'id_usuario')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->foreignId('alumno')
                  ->constrained('Usuarios', 'id_usuario')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Solicitudes_Prueba');
    }
};
