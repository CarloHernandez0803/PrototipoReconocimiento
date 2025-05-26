<?php

namespace App\Http\Controllers;

use App\Models\SenEntrenamiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SenEntrenamientoController extends Controller
{
    public function index()
    {
        $senalamientos = SenEntrenamiento::paginate(10);
        return view('senalamientos_entrenamientos.index', compact('senalamientos'));
    }

    public function create()
    {
        return view('senalamientos_entrenamientos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_lote' => 'required|string|max:45',
            'descripcion' => 'required|string',
            'categoria' => 'required|in:Semáforo,Restrictiva,Advertencia,Tráfico,Informativa',
            'imagenes' => 'required|array',
            'imagenes.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $rutas = [];
        foreach ($request->file('imagenes') as $imagen) {
            $nombreOriginal = $imagen->getClientOriginalName();
            $rutaRelativa = "datasets/entrenamientos/{$request->categoria}/{$nombreOriginal}";
            
            Storage::disk('ftp')->put($rutaRelativa, file_get_contents($imagen));
            
            $rutas[] = [
                'ruta_relativa' => $rutaRelativa,
                'url_publica' => env('FTP_BASE_URL').'/'.$rutaRelativa
            ];
        }

        SenEntrenamiento::create([
            'nombre_lote' => $request->nombre_lote,
            'descripcion' => $request->descripcion,
            'categoria' => $request->categoria,
            'rutas' => json_encode($rutas)
        ]);

        return redirect()->route('senalamientos_entrenamientos.index')->with('success', 'Lote subido correctamente');
    }

    public function show(string $id)
    {
        $lote = SenEntrenamiento::findOrFail($id);
        return view('senalamientos_entrenamientos.show', [
            'lote' => $lote,
            'imagenes' => $lote->rutas
        ]);
    }

    public function edit(string $id)
    {
        $lote = SenEntrenamiento::findOrFail($id);
        return view('senalamientos_entrenamientos.edit', [
            'lote' => $lote,
            'imagenes' => $lote->rutas
        ]);
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nombre_lote' => 'required|string|max:45',
            'descripcion' => 'required|string',
            'categoria' => 'required|in:Semáforo,Restrictiva,Advertencia,Tráfico,Informativa',
            'imagenes.*' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
            'eliminar_imagenes' => 'sometimes|array',
            'eliminar_imagenes.*' => 'integer'
        ]);

        $lote = SenEntrenamiento::findOrFail($id);
        $rutas = $lote->rutas; // Usamos el accessor que definimos

        // Eliminar imágenes seleccionadas
        if ($request->has('eliminar_imagenes')) {
            foreach ($request->eliminar_imagenes as $index) {
                if (isset($rutas[$index])) {
                    Storage::disk('ftp')->delete($rutas[$index]['ruta_relativa']);
                    unset($rutas[$index]);
                }
            }
            $rutas = array_values($rutas); // Reindexar
        }

        // Agregar nuevas imágenes
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $imagen) {
                $nombreOriginal = $imagen->getClientOriginalName();
                $rutaRelativa = "datasets/entrenamientos/{$validated['categoria']}/{$nombreOriginal}";
                
                Storage::disk('ftp')->put($rutaRelativa, file_get_contents($imagen));
                
                $rutas[] = [
                    'ruta_relativa' => $rutaRelativa,
                    'url_publica' => rtrim(env('FTP_BASE_URL'), '/').'/'.ltrim($rutaRelativa, '/').'?'.time() // Cache buster
                ];
            }
        }

        $lote->update([
            'nombre_lote' => $validated['nombre_lote'],
            'descripcion' => $validated['descripcion'],
            'categoria' => $validated['categoria'],
            'rutas' => json_encode($rutas)
        ]);

        return redirect()->route('senalamientos_entrenamientos.index')->with('success', 'Lote actualizado correctamente');
    }

    public function destroy(string $id)
    {
        $lote = SenEntrenamiento::findOrFail($id);
        
        $rutas = $lote->rutas;

        foreach ($rutas as $ruta) {
            if (!empty($ruta['ruta_relativa'])) {
                try {
                    Storage::disk('ftp')->delete($ruta['ruta_relativa']);
                } catch (\Exception $e) {
                    \Log::error("Error eliminando archivo FTP: {$ruta['ruta_relativa']} - {$e->getMessage()}");
                }
            }
        }

        $lote->delete();

        return redirect()->route('senalamientos_entrenamientos.index')->with('success', 'Lote eliminado correctamente');
    }

    public function mostrarImagen($id, $index)
    {
        $lote = SenEntrenamiento::findOrFail($id);
        $rutas = $lote->rutas;

        if (!isset($rutas[$index])) {
            abort(404);
        }

        $ruta = $rutas[$index]['ruta_relativa'] ?? $rutas[$index];

        try {
            $archivo = Storage::disk('ftp')->get($ruta);
            $tipo = Storage::disk('ftp')->mimeType($ruta);

            return response($archivo)
                ->header('Content-Type', $tipo)
                ->header('Content-Disposition', 'inline; filename="'.basename($ruta).'"');
        } catch (\Exception $e) {
            return response()->file(public_path('images/placeholder.png'));
        }
    }
}