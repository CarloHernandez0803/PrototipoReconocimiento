<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Historial;
use App\Jobs\ClassifyImageJob;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ModuloPrueba extends Controller
{
    public function index()
    {
        $modelos_entrenados = Historial::where('modelo', '!=', 'pendiente')
                                       ->orderBy('fecha_creacion', 'desc')
                                       ->get();
        return view('modulo_prueba.index', ['modelos' => $modelos_entrenados]);
    }

    public function classify(Request $request)
    {
        $request->validate([
            'historial_id' => 'required|exists:Historial_Entrenamiento,id_historial',
            'imagen'       => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        $classificationId = 'classification-' . Str::uuid();
        
        $file = $request->file('imagen');
        $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
        $uploadPath = 'uploads/clasificaciones/';
        $relativePath = $uploadPath . $filename;
        
        // Mover el archivo a public/uploads/clasificaciones/
        $file->move(public_path($uploadPath), $filename);

        // Guardar la información inicial en la caché por 10 minutos
        Cache::put($classificationId, [
            'estado'       => 'encolado',
            'historial_id' => $request->input('historial_id'),
            'imagen_path'  => $relativePath, // Guardamos la ruta relativa a public/
            'resultado'    => null,
        ], now()->addMinutes(10));

        ClassifyImageJob::dispatch($classificationId);

        return response()->json([
            'status'            => 'success',
            'classification_id' => $classificationId,
        ]);
    }

    public function checkStatus(string $classificationId)
    {
        $data = Cache::get($classificationId, ['estado' => 'expirado']);
        
        // Añadimos la URL de la imagen a la respuesta para el frontend
        if (!empty($data['imagen_path'])) {
            $data['imagen_url'] = asset($data['imagen_path']);
        }
        
        // Limpiar el archivo físico solo cuando el trabajo se ha completado
        if (($data['estado'] ?? '') === 'completado' && !empty($data['imagen_path'])) {
            $absolutePath = public_path($data['imagen_path']);
            if (File::exists($absolutePath)) {
                File::delete($absolutePath);
            }
        }

        return response()->json($data);
    }
}