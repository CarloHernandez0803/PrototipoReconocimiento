<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ModuloPrueba extends Controller
{
    public function index()
    {
        $categorias = ['Semáforo', 'Restrictiva', 'Advertencia', 'Tráfico', 'Informativa'];
        $imagenes = [];
        
        if(request()->has('categoria')) {
            $imagenes = Storage::disk('ftp')
                ->files('datasets/pruebas/'.request('categoria'));
        }
        
        return view('modulo_prueba.index', [
            'categorias' => $categorias,
            'imagenes' => $imagenes,
            'resultado' => session('resultado'),
            'selectedImage' => session('selectedImage')
        ]);
    }

    public function classify($image)
    {
        try {
            $process = new Process([
                config('app.python_path', 'python3'),
                base_path('scripts/clasificar.py'),
                $image
            ]);
            
            $process->setTimeout(120);
            $process->run();
            
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
            
            $resultado = json_decode($process->getOutput(), true);
            
            return back()
                ->with('resultado', $resultado)
                ->with('selectedImage', $image);
                
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /*public function index()
    {
        // Simular la lista de imágenes disponibles en el servidor
        $images = [
            'imagen1.jpg',
            'imagen2.jpg',
            'imagen3.jpg',
            'imagen4.jpg',
        ];

        return view('modulo_prueba.index', compact('images'));
    }

    public function classify(Request $request, $image)
    {
        // Simular el resultado de la clasificación
        $result = "Es un semáforo"; // Resultado simulado
        $selectedImage = $image; // Imagen seleccionada

        // Simular la lista de imágenes disponibles en el servidor
        $images = [
            'imagen1.jpg',
            'imagen2.jpg',
            'imagen3.jpg',
            'imagen4.jpg',
        ];

        return view('modulo_prueba.index', [
            'result' => $result,
            'selectedImage' => $selectedImage,
            'images' => $images,
        ]);
    }*/
}
