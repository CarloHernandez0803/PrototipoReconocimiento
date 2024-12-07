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
                </div>

                <div class="mt-4">
                    <x-label for="alumno" value="{{ __('Alumno Responsable') }}" />
                    <select id="alumno" name="alumno" class="block mt-1 w-full" required>
                        <option value="">{{ __('Seleccionar Alumno') }}</option>
                        @foreach ($alumnos as $alumno)
                            <option value="{{ $alumno->id_usuario }}">{{ $alumno->nombre . ' ' . $alumno->apellidos }}</option>
                        @endforeach
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
