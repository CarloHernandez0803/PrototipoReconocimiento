<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Registrar Resolución para Incidencia #{{ $incidencia->id_incidencia }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <form method="post" action="{{ route('resoluciones.store', $incidencia->id_incidencia) }}">
                @csrf

                <div class="px-4 py-5 bg-white sm:p-6">
                    <label for="estado" class="block font-medium text-sm text-gray-700">{{ __('Estado') }}</label>
                    <select name="estado" id="estado" class="form-select rounded-md shadow-sm mt-1 block w-full">
                        <option value="Pendiente">{{ __('Pendiente') }}</option>
                        <option value="En Proceso">{{ __('En Proceso') }}</option>
                        <option value="Resuelto">{{ __('Resuelto') }}</option>
                    </select>
                    @error('estado')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="px-4 py-5 bg-white sm:p-6">
                    <label for="fecha_resolucion" class="block font-medium text-sm text-gray-700">{{ __('Fecha de Resolución') }}</label>
                    <input type="date" name="fecha_resolucion" id="fecha_resolucion" class="form-input rounded-md shadow-sm mt-1 block w-full" />
                    @error('fecha_resolucion')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="px-4 py-3 bg-gray-50 sm:px-6">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-purple-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
                        {{ __('Guardar Resolución') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
