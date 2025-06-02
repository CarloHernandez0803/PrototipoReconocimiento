<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Evaluación al Sistema de Reconocimiento
        </h2>
    </x-slot>

    <div>
        <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form method="post" action="{{ route('evaluaciones.update', $evaluacion->id_evaluacion) }}">
                    @csrf
                    @method('put')
                    <div class="shadow overflow-hidden sm:rounded-md">
                        <div class="px-4 py-5 bg-white sm:p-6">
                            <label for="categoria_senal" class="block font-medium text-sm text-gray-700">{{ __('Categoría de señales') }}</label>
                            <select name="categoria_senal" id="categoria_senal" class="form-select rounded-md shadow-sm mt-1 block w-full">
                                <option value="Advertencia" {{ old('categoria_senal', $evaluacion->categoria_senal) == 'Advertencia' ? 'selected' : '' }}>
                                    {{ __('Advertencia') }}
                                </option>
                                <option value="Informativa" {{ old('categoria_senal', $evaluacion->categoria_senal) == 'Informativa' ? 'selected' : '' }}>
                                    {{ __('Informativa') }}
                                </option>
                                <option value="Restrictiva" {{ old('categoria_senal', $evaluacion->categoria_senal) == 'Restrictiva' ? 'selected' : '' }}>
                                    {{ __('Restrictiva') }}
                                </option>
                                <option value="Semáforo" {{ old('categoria_senal', $evaluacion->categoria_senal) == 'Semáforo' ? 'selected' : '' }}>
                                    {{ __('Semáforo') }}
                                </option>
                                <option value="Tráfico" {{ old('categoria_senal', $evaluacion->categoria_senal) == 'Tráfico' ? 'selected' : '' }}>
                                    {{ __('Tráfico') }}
                                </option>
                            </select>
                            @error('categoria_senal')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="px-4 py-5 bg-white sm:p-6">
                            <label for="senales_correctas" class="block font-medium text-sm text-gray-700">{{ __('Cantidad de señales correctas') }}</label>
                            <input type="number" name="senales_correctas" id="senales_correctas" class="form-input rounded-md shadow-sm mt-1 block w-full"
                                   value="{{ old('senales_correctas', $evaluacion->senales_correctas) }}" />
                            @error('senales_correctas')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="px-4 py-5 bg-white sm:p-6">
                            <label for="senales_totales" class="block font-medium text-sm text-gray-700">{{ __('Cantidad de señales totales') }}</label>
                            <input type="number" name="senales_totales" id="senales_totales" class="form-input rounded-md shadow-sm mt-1 block w-full"
                                   value="{{ old('senales_totales', $evaluacion->senales_totales) }}" />
                            @error('senales_totales')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="px-4 py-5 bg-white sm:p-6">
                            <label for="calificacion_media" class="block font-medium text-sm text-gray-700">{{ __('Calificación media (1-5)') }}</label>
                            <div class="flex items-center space-x-2 mt-2 rating-container">
                                @for ($i = 1; $i <= 5; $i++)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="calificacion_media" value="{{ $i }}" class="hidden"
                                            {{ old('calificacion_media', $evaluacion->calificacion_media) == $i ? 'checked' : '' }} required />
                                        <svg class="w-8 h-8 text-gray-400 transition duration-200" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 15l-5.878 3.09 1.122-6.54L0 6.909l6.56-.955L10 0l3.44 5.954 6.56.955-4.744 4.64 1.122 6.54z"/>
                                        </svg>
                                    </label>
                                @endfor
                            </div>
                            @error('calificacion_media')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <script>
                                document.addEventListener('DOMContentLoaded', () => {
                                    const container = document.querySelector('.rating-container');
                                    const radios = container.querySelectorAll('input[type="radio"]');
                                    const stars = container.querySelectorAll('svg');

                                    function updateStars(index) {
                                        stars.forEach((star, i) => {
                                            if (i <= index) {
                                                star.classList.add('text-yellow-400');
                                                star.classList.remove('text-gray-400');
                                            } else {
                                                star.classList.remove('text-yellow-400');
                                                star.classList.add('text-gray-400');
                                            }
                                        });
                                    }

                                    radios.forEach((radio, idx) => {
                                        if (radio.checked) updateStars(idx);
                                        radio.addEventListener('change', () => updateStars(idx));
                                    });
                                });
                            </script>
                        </div>

                        <div class="px-4 py-5 bg-white sm:p-6">
                            <label for="comentarios" class="block font-medium text-sm text-gray-700">{{ __('Comentarios') }}</label>
                            <input type="text" name="comentarios" id="comentarios" class="form-input rounded-md shadow-sm mt-1 block w-full" />
                            @error('comentarios')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <a href="{{ route('evaluaciones.index') }}" class="mr-4 inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:shadow-outline-gray transition ease-in-out duration-150">
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
