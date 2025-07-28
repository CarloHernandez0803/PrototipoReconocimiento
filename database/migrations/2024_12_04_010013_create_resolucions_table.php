<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Resolucion_Incidencias', function (Blueprint $table) {
            $table->id('id_resolucion');
            
            $table->foreignId('incidencia')
                ->constrained('Incidencias', 'id_incidencia')
                ->onDelete('cascade');
            
            $table->foreignId('id_administrador')
                ->constrained('Usuarios', 'id_usuario');

            $table->enum('estado', ['Pendiente', 'En Proceso', 'Resuelto']);
            
            $table->text('comentario');

            $table->timestamp('fecha_resolucion')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Resolucion_Incidencias');
    }
};
