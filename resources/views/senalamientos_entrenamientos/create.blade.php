<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Crear Lote de Señalamientos para Entrenamiento
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <form method="post" action="{{ route('senalamientos_entrenamientos.store') }}" class="p-6">
                @csrf

                <div class="mt-4">
                    <x-label for="nombre_lote" value="{{ __('Nombre del Lote') }}" />
                    <x-input id="nombre_lote" name="nombre_lote" type="text" class="block mt-1 w-full" value="{{ old('nombre_lote') }}" required />
                </div>

                <div class="mt-4">
                    <x-label for="descripcion" value="{{ __('Descripción') }}" />
                    <textarea id="descripcion" name="descripcion" rows="3" class="block mt-1 w-full rounded-md shadow-sm">{{ old('descripcion') }}</textarea>
                </div>

                <div class="mt-4">
                    <x-label for="categoria" value="{{ __('Categoría') }}" />
                    <select id="categoria" name="categoria" class="block mt-1 w-full rounded-md shadow-sm">
                        <option value="Semáforo">{{ __('Semáforo') }}</option>
                        <option value="Restrictiva">{{ __('Restrictiva') }}</option>
                        <option value="Advertencia">{{ __('Advertencia') }}</option>
                        <option value="Tráfico">{{ __('Tráfico') }}</option>
                        <option value="Informativa">{{ __('Informativa') }}</option>
                    </select>
                </div>

                <div class="mt-4">
                    <x-label for="imagenes" value="{{ __('Imágenes') }}" />
                    <input type="file" id="imagenes" name="imagenes[]" class="block mt-1 w-full" multiple required />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-button class="ms-4 bg-purple-900">
                        {{ __('Registrar') }}
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
