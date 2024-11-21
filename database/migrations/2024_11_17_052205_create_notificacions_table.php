<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Notificaciones', function (Blueprint $table) {
            $table->id('id_notificacion');
            $table->enum('tipo_notificacion', [
                'Creación de Cuenta',
                'Recepción de Solicitud de Prueba',
                'Respuesta a Solicitud de Prueba',
                'Experimentación',
                'Evaluación Red Neuronal',
                'Registro de Reporte de Fallo',
                'Seguimiento de Fallo'
            ]);
            $table->text('contenido');
            $table->timestamp('fecha_senvio')->useCurrent();
            $table->foreignId('usuario')
                  ->constrained('Usuarios', 'id_usuario')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Notificaciones');
    }
};
