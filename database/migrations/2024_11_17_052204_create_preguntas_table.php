<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Preguntas_Respuestas', function (Blueprint $table) {
            $table->id('id_pregunta');
            $table->string('titulo', 255);
            $table->text('descripcion');
            $table->enum('categoria', [
                'Funcionalidad del Sistema',
                'Reportes de Errores',
                'Solicitudes de Mejora',
                'Otros'
            ]);
            $table->enum('estado', [
                'Pendiente',
                'Respondida',
                'Resuelta'
            ]);
            $table->text('respuesta')->nullable()->default(null);
            $table->timestamp('fecha_pub')->useCurrent();
            $table->timestamp('fecha_act')->nullable()->default(null);
            $table->foreignId('usuario')
                  ->nullable()
                  ->constrained('Usuarios', 'id_usuario')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Preguntas_Respuestas');
    }
};
