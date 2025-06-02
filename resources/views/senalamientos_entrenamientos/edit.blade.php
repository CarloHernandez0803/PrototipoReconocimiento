<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Lote de Señalamientos para Entrenamiento') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <form method="post" action="{{ route('senalamientos_entrenamientos.update', $lote->id_senalamiento_entrenamiento) }}" enctype="multipart/form-data">
                @csrf
                @method('put')
                
                <div class="px-4 py-5 sm:p-6 space-y-6">
                    <!-- Nombre del Lote -->
                    <div>
                        <x-label for="nombre_lote" value="{{ __('Nombre del lote*') }}" />
                        <x-input id="nombre_lote" name="nombre_lote" type="text" class="mt-1 block w-full" 
                                 value="{{ old('nombre_lote', $lote->nombre_lote) }}" required />
                        <x-input-error for="nombre_lote" class="mt-2" />
                    </div>

                    <!-- Descripción -->
                    <div>
                        <x-label for="descripcion" value="{{ __('Descripción*') }}" />
                        <textarea id="descripcion" name="descripcion" rows="3"
                                  class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('descripcion', $lote->descripcion) }}</textarea>
                        <x-input-error for="descripcion" class="mt-2" />
                    </div>

                    <!-- Categoría -->
                    <div>
                        <x-label for="categoria" value="{{ __('Categoría*') }}" />
                        <select id="categoria" name="categoria" required
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            @foreach(['Semáforo', 'Restrictiva', 'Advertencia', 'Tráfico', 'Informativa'] as $opcion)
                                <option value="{{ $opcion }}" {{ old('categoria', $lote->categoria) == $opcion ? 'selected' : '' }}>
                                    {{ $opcion }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error for="categoria" class="mt-2" />
                    </div>

                    <!-- Imágenes Actuales -->
                    <div>
                        <x-label value="{{ __('Imágenes Actuales') }}" />
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-2">
                            @foreach($imagenes as $index => $ruta)
                                <div class="relative group">
                                    <div class="h-32 w-full bg-gray-100 rounded-md shadow-sm flex items-center justify-center overflow-hidden border">
                                        <img src="{{ route('senalamientos.entrenamiento.imagen', ['id' => $lote->id_senalamiento_entrenamiento, 'index' => $index]) }}?v={{ time() }}" 
                                            alt="Imagen {{ $index + 1 }}" 
                                            class="max-h-full max-w-full object-contain"
                                            onerror="this.onerror=null;this.src='{{ asset('images/image-not-found.png') }}'">
                                    </div>
                                    <div class="absolute top-1 right-1">
                                        <input type="checkbox" 
                                            name="eliminar_imagenes[]" 
                                            value="{{ $index }}" 
                                            id="eliminar_{{ $index }}"
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </div>
                                    <label for="eliminar_{{ $index }}" class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity bg-black bg-opacity-30 cursor-pointer">
                                        <span class="bg-white text-red-600 px-2 py-1 rounded text-xs font-bold">Eliminar</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Marque las imágenes que desea eliminar</p>
                    </div>

                    <!-- Nuevas Imágenes -->
                    <div>
                        <x-label for="imagenes" value="{{ __('Agregar Nuevas Imágenes') }}" />
                        <input type="file" name="imagenes[]" id="imagenes" multiple
                               class="mt-1 block w-full text-sm text-gray-500
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-md file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-indigo-50 file:text-indigo-700
                                      hover:file:bg-indigo-100"
                               accept=".jpg,.jpeg,.png">
                        <x-input-error for="imagenes" class="mt-2" />
                        <x-input-error for="imagenes.*" class="mt-2" />
                        <p class="mt-1 text-sm text-gray-500">Formatos aceptados: JPG, JPEG, PNG (Máx. 2MB cada una)</p>
                    </div>
                </div>

                <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <a href="{{ route('senalamientos_entrenamientos.index') }}" class="mr-4 inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:shadow-outline-gray transition ease-in-out duration-150">
                        {{ __('Cancelar') }}
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-purple-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 active:bg-purple-900 focus:outline-none focus:border-purple-900 focus:shadow-outline-purple disabled:opacity-25 transition ease-in-out duration-150">
                        {{ __('Guardar Cambios') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>