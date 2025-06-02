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
                    <select id="categoria_senal" name="categoria_senal" class="block mt-1 w-full">
                        <option value="Advertencia">{{ __('Advertencia') }}</option>
                        <option value="Informativa">{{ __('Informativa') }}</option>
                        <option value="Restrictiva">{{ __('Restrictiva') }}</option>
                        <option value="Semáforo">{{ __('Semáforo') }}</option>
                        <option value="Tráfico">{{ __('Tráfico') }}</option>
                    </select>
                </div>

                <div class="mt-4">
                    <x-label for="senales_correctas" value="{{ __('Cantidad de señales correctas') }}" />
                    <x-input id="senales_correctas" class="block mt-1 w-full" type="number" name="senales_correctas" :value="old('senales_correctas')" required />
                    @error('senales_correctas')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <x-label for="senales_totales" value="{{ __('Cantidad de señales totales') }}" />
                    <x-input id="senales_totales" class="block mt-1 w-full" type="number" name="senales_totales" :value="old('senales_totales')" required />
                    @error('senales_totales')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <x-label for="calificacion_media" value="{{ __('Calificación media (1-5)') }}" />
                    <div class="flex items-center space-x-2 mt-2" id="rating-container">
                        @for ($i = 1; $i <= 5; $i++)
                            <label class="cursor-pointer">
                                <input type="radio" name="calificacion_media" value="{{ $i }}" class="hidden" required />
                                <svg class="w-8 h-8 star text-gray-400 transition duration-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 15l-5.878 3.09 1.122-6.54L0 6.909l6.56-.955L10 0l3.44 5.954 6.56.955-4.744 4.64 1.122 6.54z"/>
                                </svg>
                            </label>
                        @endfor
                    </div>
                    @error('calificacion_media')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <x-label for="comentarios" value="{{ __('Comentarios') }}" />
                    <x-input id="comentarios" class="block mt-1 w-full" type="text" name="comentarios" :value="old('comentarios')" required/>
                    @error('comentarios')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-button class="ms-4 bg-purple-900">
                        {{ __('Registrar') }}
                    </x-button>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const stars = document.querySelectorAll('#rating-container input[type="radio"]');

                        stars.forEach((star, idx) => {
                            star.addEventListener('change', () => {
                                // Remover color de todas las estrellas
                                document.querySelectorAll('#rating-container svg').forEach(svg => {
                                    svg.classList.remove('text-yellow-400');
                                    svg.classList.add('text-gray-400');
                                });

                                // Agregar color hasta la estrella seleccionada
                                for (let i = 0; i <= idx; i++) {
                                    document.querySelectorAll('#rating-container svg')[i].classList.remove('text-gray-400');
                                    document.querySelectorAll('#rating-container svg')[i].classList.add('text-yellow-400');
                                }
                            });
                        });
                    });
                </script>
            </form>
        </div>
    </div>
</x-app-layout>