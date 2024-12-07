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
                            <label for="tipo" class="block font-medium text-sm text-gray-700">{{ __('Tipo de incidencia') }}</label>
                            <select name="tipo" id="tipo" class="form-select rounded-md shadow-sm mt-1 block w-full">
                                <option value="Error de Sistema" {{ old('tipo', $incidencia->tipo_experiencia) == 'Error de Sistema' ? 'selected' : '' }}>
                                    {{ __('Error de Sistema') }}
                                </option>
                                <option value="Problema de Rendimiento" {{ old('tipo', $incidencia->tipo_experiencia) == 'Problema de Rendimiento' ? 'selected' : '' }}>
                                    {{ __('Problema de Rendimiento') }}
                                </option>
                                <option value="Fallo de Seguridad" {{ old('tipo', $incidencia->tipo_experiencia) == 'Fallo de Seguridad' ? 'selected' : '' }}>
                                    {{ __('Fallo de Seguridad') }}
                                </option>
                                <option value="Actualizaciones Fallidas" {{ old('tipo', $incidencia->tipo_experiencia) == 'Actualizaciones Fallidas' ? 'selected' : '' }}>
                                    {{ __('Actualizaciones Fallidas') }}
                                </option>
                                <option value="Incidencias en Datos" {{ old('tipo', $incidencia->tipo_experiencia) == 'Incidencias en Datos' ? 'selected' : '' }}>
                                    {{ __('Incidencias en Datos') }}
                                </option>
                                <option value="Problema de Usabilidad" {{ old('tipo', $incidencia->tipo_experiencia) == 'Problema de Usabilidad' ? 'selected' : '' }}>
                                    {{ __('Problema de Usabilidad') }}
                                </option>
                                <option value="Solicitudes de Mejora" {{ old('tipo', $incidencia->tipo_experiencia) == 'Solicitudes de Mejora' ? 'selected' : '' }}>
                                    {{ __('Solicitudes de Mejora') }}
                                </option>
                                <option value="Otros" {{ old('tipo', $incidencia->tipo_experiencia) == 'Otros' ? 'selected' : '' }}>
                                    {{ __('Otros') }}
                                </option>
                            </select>
                            @error('tipo')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="px-4 py-5 bg-white sm:p-6">
                            <label for="descripcion" class="block font-medium text-sm text-gray-700">{{ __('Descripción') }}</label>
                            <input type="text" name="descripcion" id="descripcion" class="form-input rounded-md shadow-sm mt-1 block w-full" />
                            @error('descripcion')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
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
