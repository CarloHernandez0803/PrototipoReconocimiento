<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Crear Solicitud de Prueba
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <form method="post" action="{{ route('solicitudes.store') }}" class="p-6">
                @csrf

                <div class="mt-4">
                    <x-label for="fecha_solicitud" value="{{ __('Fecha de Solicitud') }}" />
                    <x-input id="fecha_solicitud" class="block mt-1 w-full" type="date" name="fecha_solicitud" :value="old('fecha_solicitud')" required />
                    @error('fecha_solicitud')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <x-label for="alumno" value="{{ __('Alumno Responsable') }}" />
                    <select id="alumno" name="alumno" class="block mt-1 w-full" required>
                        <option value="">{{ __('Seleccionar Alumno') }}</option>
                        @foreach ($alumnos as $alumno)
                            <option value="{{ $alumno->id_usuario }}">{{ $alumno->nombre . ' ' . $alumno->apellidos }}</option>
                        @endforeach
                    </select>
                    @error('alumno')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end mt-4">
                    <a href="{{ route('solicitudes.index') }}" class="mr-4 inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:shadow-outline-gray transition ease-in-out duration-150">
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
