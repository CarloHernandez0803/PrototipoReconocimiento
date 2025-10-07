<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Reporte de Incidencia
        </h2>
    </x-slot>

    <div>
        <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form method="post" action="{{ route('incidencias.update', $incidencia->id_incidencia) }}">
                    @csrf
                    @method('put')
                    <div class="shadow overflow-hidden sm:rounded-md">
                        <div class="px-4 py-5 bg-white sm:p-6">
                            <label for="tipo_incidencia" class="block font-medium text-sm text-gray-700">{{ __('Tipo de incidencia') }}</label>
                            <select name="tipo_incidencia" id="tipo_incidencia" class="form-select rounded-md shadow-sm mt-1 block w-full">
                                <option value="Error de Sistema" {{ old('tipo_incidencia', $incidencia->tipo_incidencia) == 'Error de Sistema' ? 'selected' : '' }}>
                                    {{ __('Error de Sistema') }}
                                </option>
                                <option value="Problema de Rendimiento" {{ old('tipo_incidencia', $incidencia->tipo_incidencia) == 'Problema de Rendimiento' ? 'selected' : '' }}>
                                    {{ __('Problema de Rendimiento') }}
                                </option>
                                <option value="Fallo de Seguridad" {{ old('tipo_incidencia', $incidencia->tipo_incidencia) == 'Fallo de Seguridad' ? 'selected' : '' }}>
                                    {{ __('Fallo de Seguridad') }}
                                </option>
                                <option value="Actualizaciones Fallidas" {{ old('tipo_incidencia', $incidencia->tipo_incidencia) == 'Actualizaciones Fallidas' ? 'selected' : '' }}>
                                    {{ __('Actualizaciones Fallidas') }}
                                </option>
                                <option value="Incidencias en Datos" {{ old('tipo_incidencia', $incidencia->tipo_incidencia) == 'Incidencias en Datos' ? 'selected' : '' }}>
                                    {{ __('Incidencias en Datos') }}
                                </option>
                                <option value="Problema de Usabilidad" {{ old('tipo_incidencia', $incidencia->tipo_incidencia) == 'Problema de Usabilidad' ? 'selected' : '' }}>
                                    {{ __('Problema de Usabilidad') }}
                                </option>
                                <option value="Solicitudes de Mejora" {{ old('tipo_incidencia', $incidencia->tipo_incidencia) == 'Solicitudes de Mejora' ? 'selected' : '' }}>
                                    {{ __('Solicitudes de Mejora') }}
                                </option>
                                <option value="Otros" {{ old('tipo_incidencia', $incidencia->tipo_incidencia) == 'Otros' ? 'selected' : '' }}>
                                    {{ __('Otros') }}
                                </option>
                            </select>
                            @error('tipo_incidencia')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="px-4 py-5 bg-white sm:p-6">
                            <label for="descripcion" class="block font-medium text-sm text-gray-700">Descripción</label>
                            <textarea name="descripcion" id="descripcion" rows="4" class="form-textarea rounded-md shadow-sm mt-1 block w-full">{{ old('descripcion', $incidencia->descripcion) }}</textarea>
                            @error('descripcion')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <a href="{{ route('incidencias.index') }}" class="mr-4 inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:shadow-outline-gray transition ease-in-out duration-150">
                                {{ __('Cancelar') }}
                            </a>
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
