<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ModuloPrueba extends Controller
{
    /*public function index()
    {
        $imagePath = storage_path('app/public/images');
        $images = array_diff(scandir($imagePath), ['.', '..']);

        return view('modulo_prueba.index', compact('images'));
    }

    public function classify(Request $request, $image)
    {
        // Ruta completa de la imagen seleccionada
        $imagePath = storage_path('app/public/images/' . $image);

        // Ejecutar el script de Python para clasificar la imagen
        $process = new Process([
            'python3',
            base_path('scripts/clasificar.py'),
            $imagePath,
        ]);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        // Obtener el resultado de la clasificación
        $result = trim($process->getOutput());

        // Devolver la vista con el resultado
        return view('modulo_prueba.index', [
            'result' => $result,
            'selectedImage' => $image,
            'images' => array_diff(scandir(storage_path('app/public/images')), ['.', '..']),
        ]);
    }*/

    public function index()
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
    }
}
