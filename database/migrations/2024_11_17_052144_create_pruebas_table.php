<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Senalamientos_Prueba', function (Blueprint $table) {
            $table->id('id_senalamiento_prueba');
            $table->string('nombre_lote', 45);
            $table->text('descripcion');
            $table->json('rutas');
            $table->enum('categoria', [
                'Semáforo',
                'Restrictiva',
                'Advertencia',
                'Tráfico',
                'Informativa'
            ]);
            $table->timestamp('fecha_creacion')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Senalamientos_Prueba');
    }
};
