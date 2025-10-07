<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('Senalamientos_Prueba', function (Blueprint $table) {
            $table->unsignedBigInteger('usuario')->after('fecha_creacion');
            $table->foreign('usuario')->references('id_usuario')->on('Usuarios')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('Senalamientos_Prueba', function (Blueprint $table) {
            $table->dropForeign(['usuario']);
            $table->dropColumn('usuario');
        });
    }
};