<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Incidencias', function (Blueprint $table) {
            $table->id('id_incidencia');
            $table->enum('tipo_experiencia', [
                'Error de Sistema',
                'Problema de Rendimiento',
                'Fallo de Seguridad',
                'Actualizaciones Fallidas',
                'Incidencias en Datos',
                'Problema de Usabilidad',
                'Solicitudes de Mejora',
                'Otros'
            ]);
            $table->text('descripcion');
            $table->timestamp('fecha_reporte')->useCurrent();
            $table->foreignId('coordinador')
                  ->nullable()
                  ->constrained('Usuarios', 'id_usuario')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Incidencias');
    }
};
