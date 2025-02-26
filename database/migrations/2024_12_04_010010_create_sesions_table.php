<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Sesiones', function (Blueprint $table) {
            $table->id('id_sesion');
            $table->string('token_sesion', 255);
            $table->timestamp('fecha_inicio')->useCurrent();
            $table->timestamp('fecha_fin')->nullable()->default(null);
            $table->foreignId('usuario')
                  ->constrained('Usuarios', 'id_usuario')
                  ->cascadeOnDelete()
                  ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sesions');
    }
};
