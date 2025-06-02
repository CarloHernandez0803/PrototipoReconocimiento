<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Preguntas y Respuesta del Sistema
        </h2>
    </x-slot>

    <div>
        <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form method="post" action="{{ route('preguntas.update', $pregunta->id_pregunta) }}">
                    @csrf
                    @method('put')
                    <div class="shadow overflow-hidden sm:rounded-md">
                        @if(Auth::user()->id_usuario === $pregunta->usuario)
                            <div class="px-4 py-5 bg-white sm:p-6">
                                <label for="titulo" class="block font-medium text-sm text-gray-700">{{ __('Título') }}</label>
                                <input type="text" maxlength="255" name="titulo" id="titulo" class="form-input rounded-md shadow-sm mt-1 block w-full" 
                                    value="{{ old('titulo', $pregunta->titulo) }}" />
                                @error('titulo')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="px-4 py-5 bg-white sm:p-6">
                                <label for="descripcion" class="block font-medium text-sm text-gray-700">{{ __('Descripción') }}</label>
                                <input type="text" name="descripcion" id="descripcion" class="form-input rounded-md shadow-sm mt-1 block w-full" 
                                    value="{{ old('descripcion', $pregunta->descripcion) }}" />
                                @error('descripcion')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        
                            <div class="px-4 py-5 bg-white sm:p-6">
                                <label for="categoria" class="block font-medium text-sm text-gray-700">{{ __('Categoría de pregunta') }}</label>
                                <select name="categoria" id="categoria" class="form-select rounded-md shadow-sm mt-1 block w-full">
                                    <option value="Funcionalidad del Sistema" {{ old('categoria', $pregunta->categoria) == 'Funcionalidad del Sistema' ? 'selected' : '' }}>
                                        {{ __('Funcionalidad del Sistema') }}
                                    </option>
                                    <option value="Reportes de Errores" {{ old('categoria', $pregunta->categoria) == 'Reportes de Errores' ? 'selected' : '' }}>
                                        {{ __('Reportes de Errores') }}
                                    </option>
                                    <option value="Solicitudes de Mejora" {{ old('categoria', $pregunta->categoria) == 'Solicitudes de Mejora' ? 'selected' : '' }}>
                                        {{ __('Solicitudes de Mejora') }}
                                    </option>
                                    <option value="Otros" {{ old('categoria', $pregunta->categoria) == 'Otros' ? 'selected' : '' }}>
                                        {{ __('Otros') }}
                                    </option>
                                </select>
                                @error('categoria')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        @if(Auth::user()->rol === 'Administrador')
                            <div class="px-4 py-5 bg-white sm:p-6">
                                <label for="estado" class="block font-medium text-sm text-gray-700">{{ __('Estado de la pregunta') }}</label>
                                <select name="estado" id="estado" class="form-select rounded-md shadow-sm mt-1 block w-full">
                                    <option value="Pendiente" {{ old('estado', $pregunta->estado) == 'Pendiente' ? 'selected' : '' }}>
                                        {{ __('Pendiente') }}
                                    </option>
                                    <option value="Respondida" {{ old('estado', $pregunta->estado) == 'Respondida' ? 'selected' : '' }}>
                                        {{ __('Respondida') }}
                                    </option>
                                    <option value="Resuelta" {{ old('estado', $pregunta->estado) == 'Resuelta' ? 'selected' : '' }}>
                                        {{ __('Resuelta') }}
                                    </option>
                                </select>
                                @error('estado')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="px-4 py-5 bg-white sm:p-6">
                                <label for="respuesta" class="block font-medium text-sm text-gray-700">{{ __('Respuesta') }}</label>
                                <input type="text" name="respuesta" id="respuesta" class="form-input rounded-md shadow-sm mt-1 block w-full" />
                                @error('respuesta')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <a href="{{ route('preguntas.index') }}" class="mr-4 inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:shadow-outline-gray transition ease-in-out duration-150">
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
