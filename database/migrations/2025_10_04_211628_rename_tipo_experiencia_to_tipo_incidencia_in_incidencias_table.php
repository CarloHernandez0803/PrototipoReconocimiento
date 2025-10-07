<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE Incidencias CHANGE tipo_experiencia tipo_incidencia ENUM('Error de Sistema','Problema de Rendimiento','Fallo de Seguridad','Actualizaciones Fallidas','Incidencias en Datos','Problema de Usabilidad','Solicitudes de Mejora','Otros')");
    }

    public function down()
    {
        DB::statement("ALTER TABLE Incidencias CHANGE tipo_incidencia tipo_experiencia ENUM('Error de Sistema','Problema de Rendimiento','Fallo de Seguridad','Actualizaciones Fallidas','Incidencias en Datos','Problema de Usabilidad','Solicitudes de Mejora','Otros')");
    }
};