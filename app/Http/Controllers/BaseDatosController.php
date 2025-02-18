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
                $tableName = $table->{'Tables_in_' . env('DB_DATABASE')};

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
        try 
        {
            if (!$request->hasFile('backup_file')) {
                return response()->json(['message' => 'No se ha subido un archivo válido.'], 400);
            }

            $file = $request->file('backup_file');
            if ($file->getClientOriginalExtension() !== 'sql') {
                return response()->json(['message' => 'El archivo debe tener extensión .sql.'], 400);
            }

            $content = file_get_contents($file->getRealPath());

            DB::unprepared($content);

            return response()->json(['message' => 'Base de datos restaurada correctamente.']);
        } 
        catch (\Exception $e) 
        {
            Log::error("Error en restore: " . $e->getMessage());
            return response()->json(['message' => 'Error al restaurar la base de datos.'], 500);
        }
    }
}