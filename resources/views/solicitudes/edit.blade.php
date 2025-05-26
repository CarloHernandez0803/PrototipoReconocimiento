<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Solicitud de Prueba
        </h2>
    </x-slot>

    <div>
        <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form method="post" action="{{ route('preguntas.update', $pregunta->id_pregunta) }}">
                    @csrf
                    @method('put')
                    <div class="shadow overflow-hidden sm:rounded-md">
                        <div class="px-4 py-5 bg-white sm:p-6">
                            <x-label for="fecha_solicitud" value="{{ __('Fecha de Solicitud') }}" />
                            <x-input id="fecha_solicitud" class="block mt-1 w-full" type="date" name="fecha_solicitud" :value="old('fecha_solicitud', $solicitud->fecha_solicitud)" required />
                        </div>

                        <div class="px-4 py-5 bg-white sm:p-6">
                            <x-label for="alumno" value="{{ __('Alumno Responsable') }}" />
                            <select id="alumno" name="alumno" class="block mt-1 w-full" required>
                                <option value="">{{ __('Seleccionar Alumno') }}</option>
                                @foreach ($alumnos as $alumno)
                                    <option value="{{ $alumno->id_usuario }}" {{ $solicitud->alumno == $alumno->id_usuario ? 'selected' : '' }}>
                                        {{ $alumno->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        @if(Auth::user()->rol === 'Administrador')
                            <div class="px-4 py-5 bg-white sm:p-6">
                                <x-label for="estado" value="{{ __('Estado') }}" />
                                <select id="estado" name="estado" class="block mt-1 w-full">
                                    <option value="Pendiente" {{ $solicitud->estado === 'Pendiente' ? 'selected' : '' }}>
                                        {{ __('Pendiente') }}
                                    </option>
                                    <option value="Aprobada" {{ $solicitud->estado === 'Aprobada' ? 'selected' : '' }}>
                                        {{ __('Aprobada') }}
                                    </option>
                                </select>
                            </div>
                        @endif

                        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <a href="{{ route('senalamientos_pruebas.index') }}" class="mr-4 inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:shadow-outline-gray transition ease-in-out duration-150">
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
