<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Experiencias_Usuario', function (Blueprint $table) {
            $table->id('id_experiencia');
            $table->enum('tipo_experiencia', [
                'Positiva',
                'Negativa',
                'Neutra'
            ]);
            $table->text('descripcion');
            $table->enum('impacto', [
                'Alto',
                'Medio',
                'Bajo'
            ]);
            $table->timestamp('fecha_experiencia')->useCurrent();
            $table->foreignId('usuario')
                  ->nullable()
                  ->constrained('Usuarios', 'id_usuario')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Experiencias_Usuario');
    }
};
