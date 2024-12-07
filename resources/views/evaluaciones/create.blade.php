<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Crear Pregunta del Sistema
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <form method="post" action="{{ route('preguntas.store') }}" class="p-6">
                @csrf

                <div class="mt-4">
                    <x-label for="titulo" value="{{ __('Título') }}" />
                    <x-input id="titulo" class="block mt-1 w-full" type="text" maxlength="255" name="titulo" :value="old('titulo')" required/>
                </div>

                <div class="mt-4">
                    <x-label for="descripcion" value="{{ __('Descripción') }}" />
                    <x-input id="descripcion" class="block mt-1 w-full" type="text" name="descripcion" :value="old('descripcion')" required/>
                </div>

                <div class="mt-4">
                    <x-label for="categoria" value="{{ __('Categoría de pregunta') }}" />
                    <select id="categoria" name="categoria" class="block mt-1 w-full">
                        <option value="Funcionalidad del Sistema">{{ __('Funcionalidad del Sistema') }}</option>
                        <option value="Reportes de Errores">{{ __('Reportes de Errores') }}</option>
                        <option value="Solicitudes de Mejora">{{ __('Solicitudes de Mejora') }}</option>
                        <option value="Otros">{{ __('Otros') }}</option>
                    </select>
                </div>

                <div class="mt-4">
                    <x-label for="estado" value="{{ __('Estado de la pregunta') }}" />
                    <select id="estado" name="estado" class="block mt-1 w-full">
                        <option value="Pendiente">{{ __('Pendiente') }}</option>
                        <option value="Respondida">{{ __('Respondida') }}</option>
                        <option value="Resuelta">{{ __('Resuelta') }}</option>
                    </select>
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
