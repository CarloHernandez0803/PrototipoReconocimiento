<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Añadir Actualización para Incidencia #{{ $incidencia->id_incidencia }}
        </h2>
    </x-slot>
    <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <form method="post" action="{{ route('resoluciones.store', $incidencia->id_incidencia) }}" class="p-6 space-y-6">
                @csrf
                <div>
                    <label for="estado" class="block font-medium text-sm text-gray-700">Nuevo Estado</label>
                    <select name="estado" id="estado" class="form-select rounded-md shadow-sm mt-1 block w-full">
                        <option value="En Proceso" @selected($incidencia->estado_actual == 'En Proceso')>En Proceso</option>
                        <option value="Resuelto" @selected($incidencia->estado_actual == 'Resuelto')>Resuelto</option>
                    </select>
                </div>
                <div>
                    <label for="comentario" class="block font-medium text-sm text-gray-700">Comentario / Acciones Realizadas</label>
                    <textarea name="comentario" id="comentario" rows="4" class="form-textarea rounded-md shadow-sm mt-1 block w-full" required>{{ old('comentario') }}</textarea>
                    @error('comentario') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="flex items-center justify-end">
                    <a href="{{ route('incidencias.timeline') }}" class="mr-4 inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:shadow-outline-gray transition ease-in-out duration-150">
                        {{ __('Cancelar') }}
                    </a>
                    <button type="submit" class="px-4 py-2 bg-purple-900 text-white rounded">Guardar Actualización</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>