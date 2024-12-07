<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Crear Evaluación al Sistema de Reconocimiento
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <form method="post" action="{{ route('evaluaciones.store') }}" class="p-6">
                @csrf

                <div class="mt-4">
                    <x-label for="categoria" value="{{ __('Categoría de señales') }}" />
                    <select id="categoria" name="categoria" class="block mt-1 w-full">
                        <option value="Advertencia">{{ __('Advertencia') }}</option>
                        <option value="Informativa">{{ __('Informativa') }}</option>
                        <option value="Restrictiva">{{ __('Restrictiva') }}</option>
                        <option value="Semáforo">{{ __('Semáforo') }}</option>
                        <option value="Tráfico">{{ __('Tráfico') }}</option>
                    </select>
                </div>

                <div class="mt-4">
                    <x-label for="correctas" value="{{ __('Cantidad de señales correctas') }}" />
                    <x-input id="correctas" class="block mt-1 w-full" type="number" name="correctas" :value="old('correctas')" required />
                </div>

                <div class="mt-4">
                    <x-label for="totales" value="{{ __('Cantidad de señales totales') }}" />
                    <x-input id="totales" class="block mt-1 w-full" type="number" name="totales" :value="old('totales')" required />
                </div>

                <div class="mt-4">
                    <x-label for="calificacion_media" value="{{ __('Calificación media (1-5)') }}" />

                    <div class="flex items-center space-x-2 mt-2" id="rating-container">
                        @for ($i = 1; $i <= 5; $i++)
                            <label class="cursor-pointer">
                                <input type="radio" name="calificacion_media" value="{{ $i }}" class="hidden peer" required />
                                <svg class="w-8 h-8 text-gray-400 peer-checked:text-yellow-400 transition duration-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 15l-5.878 3.09 1.122-6.54L0 6.909l6.56-.955L10 0l3.44 5.954 6.56.955-4.744 4.64 1.122 6.54z"/>
                                </svg>
                            </label>
                        @endfor
                    </div>
                </div>

                <div class="mt-4">
                    <x-label for="comentarios" value="{{ __('Comentarios') }}" />
                    <x-input id="comentarios" class="block mt-1 w-full" type="text" name="comentarios" :value="old('comentarios')" required/>
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
