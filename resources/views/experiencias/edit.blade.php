<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Experiencia de Usuario
        </h2>
    </x-slot>

    <div>
        <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form method="post" action="{{ route('experiencias.update', $experiencia->id_experiencia) }}">
                    @csrf
                    @method('put')
                    <div class="shadow overflow-hidden sm:rounded-md">
                        <div class="px-4 py-5 bg-white sm:p-6">
                            <label for="tipo" class="block font-medium text-sm text-gray-700">{{ __('Tipo de experiencia') }}</label>
                            <select name="tipo" id="tipo" class="form-select rounded-md shadow-sm mt-1 block w-full">
                                <option value="Positiva" {{ old('tipo', $experiencia->tipo_experiencia) == 'Positiva' ? 'selected' : '' }}>
                                    {{ __('Positiva') }}
                                </option>
                                <option value="Neutra" {{ old('tipo', $experiencia->tipo_experiencia) == 'Neutra' ? 'selected' : '' }}>
                                    {{ __('Neutra') }}
                                </option>
                                <option value="Negativa" {{ old('tipo', $experiencia->tipo_experiencia) == 'Negativa' ? 'selected' : '' }}>
                                    {{ __('Negativa') }}
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

                        <div class="px-4 py-5 bg-white sm:p-6">
                            <label for="impacto" class="block font-medium text-sm text-gray-700">{{ __('Impacto') }}</label>
                            <select name="impacto" id="impacto" class="form-select rounded-md shadow-sm mt-1 block w-full">
                                <option value="Alto" {{ old('impacto', $experiencia->tipo_experiencia) == 'Alto' ? 'selected' : '' }}>
                                    {{ __('Alto') }}
                                </option>
                                <option value="Medio" {{ old('impacto', $experiencia->tipo_experiencia) == 'Medio' ? 'selected' : '' }}>
                                    {{ __('Medio') }}
                                </option>
                                <option value="Bajo" {{ old('impacto', $experiencia->tipo_experiencia) == 'Bajo' ? 'selected' : '' }}>
                                    {{ __('Bajo') }}
                                </option>
                            </select>
                            @error('impacto')
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
