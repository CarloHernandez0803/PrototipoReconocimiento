<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BaseDatosController extends Controller
{
    public function index()
    {
        return view('base_datos.base_datos');
    }

    public function backup()
    {
        try 
        {
            $filename = "backup_" . now()->format('Y-m-d_H-i-s') . ".sql";

            $tables = DB::select('SHOW TABLES');

            $sqlContent = "";

            foreach ($tables as $table) 
            {
                $tableName = collect((array) $table)->first();

                $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
                $sqlContent .= "-- Estructura de la tabla `{$tableName}`\n";
                $sqlContent .= $createTable[0]->{'Create Table'} . ";\n\n";

                $rows = DB::table($tableName)->get();
                if ($rows->count() > 0) 
                {
                    $sqlContent .= "-- Datos de la tabla `{$tableName}`\n";
                    foreach ($rows as $row) 
                    {
                        $columns = array_keys((array) $row);
                        $values = array_map(function ($value) {
                            return is_null($value) ? 'NULL' : "'" . addslashes($value) . "'";
                        }, (array) $row);

                        $sqlContent .= "INSERT INTO `{$tableName}` (`" . implode('`, `', $columns) . "`) VALUES (" . implode(', ', $values) . ");\n";
                    }
                    $sqlContent .= "\n";
                }
            }

            return response()->streamDownload(
                function () use ($sqlContent) {
                    echo $sqlContent; 
                },
                $filename,
                [
                    'Content-Type' => 'application/sql',
                    'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                ]
            );
        } 
        catch (\Exception $e) 
        {
            Log::error("Error en backup: " . $e->getMessage());
            return response()->json(['message' => 'Error al generar el respaldo.'], 500);
        }
    }

    public function restore(Request $request)
    {
        try {
            // Verifica si se ha subido un archivo
            if (!$request->hasFile('backup_file')) {
                return response()->json(['message' => 'No se ha subido un archivo válido.'], 400);
            }

            // Verifica la extensión del archivo
            $file = $request->file('backup_file');
            if ($file->getClientOriginalExtension() !== 'sql') {
                return response()->json(['message' => 'El archivo debe tener extensión .sql.'], 400);
            }

            // Lee el contenido del archivo
            $content = file_get_contents($file->getRealPath());

            // Verifica si el contenido está vacío
            if (empty($content)) {
                return response()->json(['message' => 'El archivo está vacío.'], 400);
            }

            // Desactiva temporalmente las restricciones de clave foránea
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // Obtiene todas las tablas de la base de datos
            $tables = DB::select('SHOW TABLES');

            // Elimina todas las tablas existentes
            foreach ($tables as $table) {
                $tableName = collect((array) $table)->first();
                DB::statement("DROP TABLE IF EXISTS `{$tableName}`;");
            }

            // Ejecuta las consultas SQL del archivo
            DB::unprepared($content);

            // Reactiva las restricciones de clave foránea
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            return response()->json(['message' => 'Base de datos restaurada correctamente.']);
        } catch (\Exception $e) {
            // Registra el error en el log
            Log::error("Error en restore: " . $e->getMessage());

            // Devuelve un mensaje de error detallado
            return response()->json([
                'message' => 'Error al restaurar la base de datos.',
                'error' => $e->getMessage() // Devuelve el mensaje de error para depuración
            ], 500);
        }
    }
}