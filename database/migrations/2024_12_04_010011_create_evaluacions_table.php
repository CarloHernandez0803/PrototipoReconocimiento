<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Evaluaciones_Red', function (Blueprint $table) {
            $table->id('id_evaluacion');
            $table->enum('categoria_senal', [
                'Semáforo',
                'Restrictiva',
                'Advertencia',
                'Tráfico',
                'Informativa'
            ]);
            $table->integer('senales_correctas');
            $table->integer('senales_totales');
            $table->integer('calificacion_media');
            $table->text('comentarios');
            $table->timestamp('fecha_evaluacion')->useCurrent();
            $table->foreignId('alumno')
                  ->constrained('Usuarios', 'id_usuario')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Evaluaciones_Red');
    }
};
