<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Lote de Señalamientos para Entrenamiento
        </h2>
    </x-slot>

    <div>
        <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form method="post" action="{{ route('senalamientos_entrenamientos.update', $senalamiento->id_senalamiento_entrenamiento) }}" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="shadow overflow-hidden sm:rounded-md">
                        <div class="px-4 py-5 bg-white sm:p-6">
                            <label for="nombre_lote" class="block font-medium text-sm text-gray-700">{{ __('Nombre del lote') }}</label>
                            <input type="text" name="nombre_lote" id="nombre_lote" value="{{ old('nombre_lote', $senalamiento->nombre_lote) }}" class="form-input rounded-md shadow-sm mt-1 block w-full" />
                            @error('nombre_lote')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="px-4 py-5 bg-white sm:p-6">
                            <label for="descripcion" class="block font-medium text-sm text-gray-700">{{ __('Descripción') }}</label>
                            <textarea name="descripcion" id="descripcion" class="form-input rounded-md shadow-sm mt-1 block w-full">{{ old('descripcion', $senalamiento->descripcion) }}</textarea>
                            @error('descripcion')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    
                        <div class="px-4 py-5 bg-white sm:p-6">
                            <label for="categoria" class="block font-medium text-sm text-gray-700">{{ __('Categoría') }}</label>
                            <select name="categoria" id="categoria" class="form-select rounded-md shadow-sm mt-1 block w-full">
                                <option value="Semáforo" {{ old('categoria', $senalamiento->categoria) == 'Semáforo' ? 'selected' : '' }}>{{ __('Semáforo') }}</option>
                                <option value="Restrictiva" {{ old('categoria', $senalamiento->categoria) == 'Restrictiva' ? 'selected' : '' }}>{{ __('Restrictiva') }}</option>
                                <option value="Advertencia" {{ old('categoria', $senalamiento->categoria) == 'Advertencia' ? 'selected' : '' }}>{{ __('Advertencia') }}</option>
                                <option value="Tráfico" {{ old('categoria', $senalamiento->categoria) == 'Tráfico' ? 'selected' : '' }}>{{ __('Tráfico') }}</option>
                                <option value="Informativa" {{ old('categoria', $senalamiento->categoria) == 'Informativa' ? 'selected' : '' }}>{{ __('Informativa') }}</option>
                            </select>
                            @error('categoria')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="px-4 py-5 bg-white sm:p-6">
                            <label for="imagenes" class="block font-medium text-sm text-gray-700">{{ __('Actualizar imágenes') }}</label>
                            <input type="file" name="imagenes[]" id="imagenes" multiple class="form-input rounded-md shadow-sm mt-1 block w-full" />
                            <small class="text-gray-600">{{ __('Puedes subir múltiples imágenes (máx. 2 MB por archivo).') }}</small>
                            @error('imagenes')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('imagenes.*')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="px-4 py-5 bg-white sm:p-6">
                            <label class="block font-medium text-sm text-gray-700">{{ __('Imágenes actuales') }}</label>
                            <div class="grid grid-cols-3 gap-4">
                                @foreach (json_decode($senalamiento->rutas) as $ruta)
                                    <img src="{{ Storage::disk('ftp')->url($ruta) }}" alt="Imagen del señalamiento" class="h-32 w-32 object-cover">
                                @endforeach
                            </div>
                        </div>

                        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-purple-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Actualizar') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
