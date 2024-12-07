<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Crear Reporte de Incidencia
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <form method="post" action="{{ route('evaluaciones.store') }}" class="p-6">
                @csrf

                <div class="mt-4">
                    <x-label for="tipo" value="{{ __('Tipo de incidencia') }}" />
                    <select id="tipo" name="tipo" class="block mt-1 w-full">
                        <option value="Error de Sistema">{{ __('Error de Sistema') }}</option>
                        <option value="Problema de Rendimiento">{{ __('Problema de Rendimiento') }}</option>
                        <option value="Fallo de Seguridad">{{ __('Fallo de Seguridad') }}</option>
                        <option value="Actualizaciones Fallidas">{{ __('Actualizaciones Fallidas') }}</option>
                        <option value="Incidencias en Datos">{{ __('Incidencias en Datos') }}</option>
                        <option value="Problema de Usabilidad">{{ __('Problema de Usabilidad') }}</option>
                        <option value="Solicitudes de Mejora">{{ __('Solicitudes de Mejora') }}</option>
                        <option value="Otros">{{ __('Otros') }}</option>
                    </select>
                </div>

                <div class="mt-4">
                    <x-label for="descripcion" value="{{ __('Descripción') }}" />
                    <x-input id="descripcion" class="block mt-1 w-full" type="text" name="descripcion" :value="old('descripcion')" required/>
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
