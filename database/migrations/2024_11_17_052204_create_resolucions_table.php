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
            $table->enum('estado', [
                'Pendiente',
                'En Proceso',
                'Resuelto'
            ]);
            $table->timestamp('fecha_resolucion')->nullable()->defaultValue(null);
            $table->foreignId('incidencia')
                  ->constrained('Incidencias', 'id_incidencia')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Resolucion_Incidencias');
    }
};
