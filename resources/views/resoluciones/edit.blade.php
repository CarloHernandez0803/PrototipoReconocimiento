<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Resolución para Incidencia #{{ $resolucion->incidencia }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <form method="post" action="{{ route('resoluciones.update', $resolucion->id_resolucion) }}">
                @csrf
                @method('put')
                <div class="px-4 py-5 bg-white sm:p-6">
                    <label for="estado" class="block font-medium text-sm text-gray-700">
                        {{ __('Estado de Resolución') }}
                    </label>
                    <select name="estado" id="estado" class="form-select rounded-md shadow-sm mt-1 block w-full">
                        <option value="Pendiente" {{ old('estado', $resolucion->estado) == 'Pendiente' ? 'selected' : '' }}>
                            {{ __('Pendiente') }}
                        </option>
                        <option value="En Proceso" {{ old('estado', $resolucion->estado) == 'En Proceso' ? 'selected' : '' }}>
                            {{ __('En Proceso') }}
                        </option>
                        <option value="Resuelto" {{ old('estado', $resolucion->estado) == 'Resuelto' ? 'selected' : '' }}>
                            {{ __('Resuelto') }}
                        </option>
                    </select>
                    @error('estado')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="px-4 py-3 bg-gray-50 sm:px-6">
                    <a href="{{ route('incidencias.show', $resolucion->incidenciaRegistrada->id_incidencia) }}" class="mr-4 inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:shadow-outline-gray transition ease-in-out duration-150">
                        {{ __('Cancelar') }}
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-purple-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
                        {{ __('Actualizar Resolución') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
