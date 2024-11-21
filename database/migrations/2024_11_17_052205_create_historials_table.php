<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Historial_Entrenamiento', function (Blueprint $table) { 
            $table->id('id_historial'); 
            $table->json('hiperparametros'); 
            $table->string('modelo', 255); 
            $table->string('pesos', 255); 
            $table->double('acierto', 8, 2); 
            $table->double('perdida', 8, 2); 
            $table->integer('tiempo_entrenamiento');
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->foreignId('usuario')
                  ->constrained('Usuarios', 'id_usuario')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Historial_Entrenamiento');
    }
};
