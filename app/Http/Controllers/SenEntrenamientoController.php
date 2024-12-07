<?php

namespace App\Http\Controllers;

use App\Models\SenEntrenamiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SenEntrenamientoController extends Controller
{
    public function index()
    {
        $senalamientos = SenEntrenamiento::all(); //Extrae todos los lotes registrados
        return view('senalamientos_entrenamientos.index', compact('senalamientos')); //Muestra la vista de los lotes
    }

    public function create()
    {
        return view('senalamientos_entrenamientos.create'); //Muestra la vista para crear un lote
    }

    public function store(Request $request)
    {
        $request->validate([ //Validaciones de los campos del formulario
            'nombre_lote' => 'required|string|max:45',
            'descripcion' => 'required|string',
            'categoria' => 'required|in:Semáforo,Restrictiva,Advertencia,Tráfico,Informativa',
            'imagenes' => 'required|array',
            'imagenes.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        /* Guardar las imágenes */
        $rutas = []; //Generar un array para almacenar las rutas de las imagenes
        foreach ($request->file('imagenes') as $imagen) { //Recorrer todas las imagenes
            $ruta = $imagen->store('entrenamientos/' . strtolower($request->categoria), 'public'); //Guardar las imagenes en la carpeta correspondiente
            $rutas[] = Storage::url($ruta); //Almacenar las rutas de las imagenes en el array
        }

        /* Crear el lote */
        SenEntrenamiento::create([
            'nombre_lote' => $request->nombre_lote,
            'descripcion' => $request->descripcion,
            'categoria' => $request->categoria,
            'rutas' => json_encode($rutas)
        ]);

        return redirect()->route('senalamientos_entrenamientos.index')->with('success', 'Señalamiento creado exitosamente.'); //Redireccionar a la vista de lotes
    }

    public function show(string $id)
    {
        $senalamiento = SenEntrenamiento::findOrFail($id); //Busca el lote por su id
        $senalamiento->rutas = json_decode($senalamiento->rutas); //Decodifica las rutas
        return view('senalamientos_entrenamientos.show', compact('senalamiento')); //Muestra la vista del lote
    }

    public function edit(string $id)
    {
        $senalamiento = SenEntrenamiento::findOrFail($id); //Busca el lote por su id
        $senalamiento->rutas = json_decode($senalamiento->rutas); //Decodifica las rutas
        return view('senalamientos_entrenamientos.edit', compact('senalamiento')); //Muestra la vista para editar el lote
    }

    public function update(Request $request, string $id)
    {
        /* Validaciones */
        $request->validate([
            'nombre_lote' => 'required|string|max:45',
            'descripcion' => 'required|string',
            'categoria' => 'required|in:Semáforo,Restrictiva,Advertencia,Tráfico,Informativa',
            'imagenes.*' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $senalamiento = SenEntrenamiento::findOrFail($id); //Busca el lote por su id
        $datos = $request->only(['nombre_lote', 'descripcion', 'categoria']); //Obtiene los datos del formulario

        if ($request->hasFile('imagenes')) {// Si hay nuevas imágenes
            /* Eliminar imágenes anteriores */
            $rutasAnteriores = json_decode($senalamiento->rutas, true); //Obtiene las rutas anteriores
            foreach ($rutasAnteriores as $ruta) { //Recorre las rutas anteriores
                Storage::disk('public')->delete(str_replace('/storage/', '', $ruta)); //Elimina las rutas anteriores
            }

            /* Guardar nuevas imágenes */
            $nuevasRutas = [];  //Generar un array para almacenar las rutas de las imagenes
            foreach ($request->file('imagenes') as $imagen) { //Recorrer todas las imagenes
                $ruta = $imagen->store('entrenamientos/' . strtolower($request->categoria), 'public'); //Guardar las imagenes en la carpeta correspondiente
                $nuevasRutas[] = Storage::url($ruta); //Almacenar las rutas de las imagenes en el array
            }
            $datos['rutas'] = json_encode($nuevasRutas); //Almacenar las rutas de las imagenes
        }

        $senalamiento->update($datos); //Actualiza el lote

        return redirect()->route('senalamientos_entrenamientos.index')->with('success', 'Señalamiento actualizado exitosamente.'); //Redireccionar a la vista de lotes
    }

    public function destroy(string $id)
    {
        $senalamiento = SenEntrenamiento::findOrFail($id); //Busca el lote por su id

        /* Eliminar imágenes */
        $rutas = json_decode($senalamiento->rutas, true); //Obtiene las rutas
        foreach ($rutas as $ruta) { //Recorre las rutas
            Storage::disk('public')->delete(str_replace('/storage/', '', $ruta)); //Elimina las rutas anteriores del lote 
        }

        $senalamiento->delete(); //Elimina el lote

        return redirect()->route('senalamientos_entrenamientos.index')->with('success', 'Señalamiento eliminado exitosamente.'); //Redireccionar a la vista de lotes
    }
}
