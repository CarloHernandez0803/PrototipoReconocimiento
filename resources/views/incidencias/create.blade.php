<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Crear Reporte de Incidencia
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <form method="post" action="{{ route('incidencias.store') }}" class="p-6 space-y-6">
                @csrf

                <div>
                    <label for="tipo_experiencia" class="block font-medium text-sm text-gray-700">Tipo de incidencia</label>
                    <select id="tipo_experiencia" name="tipo_experiencia" class="form-select rounded-md shadow-sm mt-1 block w-full">
                        <option value="Error de Sistema">Error de Sistema</option>
                        <option value="Problema de Rendimiento">Problema de Rendimiento</option>
                        <option value="Fallo de Seguridad">Fallo de Seguridad</option>
                        <option value="Actualizaciones Fallidas">Actualizaciones Fallidas</option>
                        <option value="Incidencias en Datos">Incidencias en Datos</option>
                        <option value="Problema de Usabilidad">Problema de Usabilidad</option>
                        <option value="Solicitudes de Mejora">Solicitudes de Mejora</option>
                        <option value="Otros">Otros</option>
                    </select>
                    @error('tipo_experiencia')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="descripcion" class="block font-medium text-sm text-gray-700">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="4" class="form-textarea rounded-md shadow-sm mt-1 block w-full">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end">
                    <a href="{{ route('incidencias.index') }}" class="mr-4 inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:shadow-outline-gray transition ease-in-out duration-150">
                        {{ __('Cancelar') }}
                    </a>
                    <button type="submit" class="px-4 py-2 bg-purple-900 text-white rounded hover:bg-purple-800">
                        Registrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>