<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Lote de Prueba') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <form method="POST" action="{{ route('senalamientos_pruebas.store') }}" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf

                <!-- Nombre del Lote -->
                <div>
                    <x-label for="nombre_lote" value="{{ __('Nombre del Lote*') }}" />
                    <x-input id="nombre_lote" name="nombre_lote" type="text" class="mt-1 block w-full" 
                             value="{{ old('nombre_lote') }}" required autofocus />
                    <x-input-error for="nombre_lote" class="mt-2" />
                </div>

                <!-- Descripción -->
                <div>
                    <x-label for="descripcion" value="{{ __('Descripción*') }}" />
                    <textarea id="descripcion" name="descripcion" rows="3"
                              class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('descripcion') }}</textarea>
                    <x-input-error for="descripcion" class="mt-2" />
                </div>

                <!-- Categoría -->
                <div>
                    <x-label for="categoria" value="{{ __('Categoría*') }}" />
                    <select id="categoria" name="categoria" required
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        @foreach(['Semáforo', 'Restrictiva', 'Advertencia', 'Tráfico', 'Informativa'] as $categoria)
                            <option value="{{ $categoria }}" {{ old('categoria') == $categoria ? 'selected' : '' }}>
                                {{ $categoria }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error for="categoria" class="mt-2" />
                </div>

                <!-- Subida de Imágenes -->
                <div>
                    <x-label for="imagenes" value="{{ __('Imágenes*') }}" />
                    <div class="mt-1 flex items-center">
                        <input type="file" id="imagenes" name="imagenes[]" multiple
                               class="block w-full text-sm text-gray-500
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-md file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-indigo-50 file:text-indigo-700
                                      hover:file:bg-indigo-100"
                               accept=".jpg,.jpeg,.png" required>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">
                        Formatos aceptados: JPG, JPEG, PNG (Máx. 2MB cada imagen)
                    </p>
                    <x-input-error for="imagenes" class="mt-2" />
                    <x-input-error for="imagenes.*" class="mt-2" />
                </div>

                <!-- Botón de Submit -->
                <div class="flex justify-end">
                    <a href="{{ route('senalamientos_pruebas.index') }}" class="mr-4 inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:shadow-outline-gray transition ease-in-out duration-150">
                        {{ __('Cancelar') }}
                    </a>
                    <x-button type="submit" class="bg-indigo-600 hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900">
                        {{ __('Guardar Lote') }}
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>