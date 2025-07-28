<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Crear Experiencia de Usuario
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <form method="post" action="{{ route('experiencias.store') }}" class="p-6">
                @csrf

                <div class="mt-4">
                    <x-label for="tipo" value="{{ __('Tipo de experiencia') }}" />
                    <select id="tipo_experiencia" name="tipo_experiencia" class="block mt-1 w-full">
                        <option value="Positiva">{{ __('Positiva') }}</option>
                        <option value="Neutra">{{ __('Neutra') }}</option>
                        <option value="Negativa">{{ __('Negativa') }}</option>
                    </select>
                </div>

                <div class="mt-4">
                    <x-label for="descripcion" value="{{ __('Descripción') }}" />
                    <x-input id="descripcion" class="block mt-1 w-full" type="text" name="descripcion" :value="old('descripcion')" required/>
                    @error('descripcion')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <x-label for="impacto" value="{{ __('Impacto') }}" />
                    <select id="impacto" name="impacto" class="block mt-1 w-full">
                        <option value="Alto">{{ __('Alto') }}</option>
                        <option value="Medio">{{ __('Medio') }}</option>
                        <option value="Bajo">{{ __('Bajo') }}</option>
                    </select>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <a href="{{ route('experiencias.index') }}" class="mr-4 inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:shadow-outline-gray transition ease-in-out duration-150">
                        {{ __('Cancelar') }}
                    </a>
                    <x-button class="ms-4 bg-purple-900">
                        {{ __('Registrar') }}
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>