<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Solicitud de Prueba #{{ $solicitud->id_solicitud }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form method="post" action="{{ route('solicitudes.update', $solicitud->id_solicitud) }}">
                    @csrf
                    @method('PUT')
                    <div class="shadow overflow-hidden sm:rounded-md">
                        
                        @if(Auth::user()->id_usuario === $solicitud->coordinador)
                            <div class="px-4 py-5 bg-white sm:p-6">
                                <label for="fecha_solicitud" class="block font-medium text-sm text-gray-700">Fecha de Solicitud</label>
                                <input type="date" name="fecha_solicitud" id="fecha_solicitud" class="form-input rounded-md shadow-sm mt-1 block w-full"
                                       value="{{ old('fecha_solicitud', $solicitud->fecha_solicitud->format('Y-m-d')) }}" required />
                                @error('fecha_solicitud')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div class="px-4 py-5 bg-white sm:p-6">
                                <label for="alumno" class="block font-medium text-sm text-gray-700">Alumno Responsable</label>
                                <select id="alumno" name="alumno" class="block mt-1 w-full form-select rounded-md shadow-sm" required>
                                    <option value="">Seleccionar Alumno</option>
                                    @foreach ($alumnos as $alumno)
                                        <option value="{{ $alumno->id_usuario }}" @selected(old('alumno', $solicitud->alumno) == $alumno->id_usuario)>
                                            {{ $alumno->nombre }} {{ $alumno->apellidos }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('alumno')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        @endif

                        @if(Auth::user()->rol === 'Administrador')
                             <div class="px-4 py-5 bg-white sm:p-6">
                                <p class="text-sm text-gray-600 mb-4">
                                    <strong>Solicitante:</strong> {{ $solicitud->usuarioAlumno->nombre }} {{ $solicitud->usuarioAlumno->apellidos }} <br>
                                    <strong>Fecha de Solicitud:</strong> {{ $solicitud->fecha_solicitud->format('d/m/Y') }}
                                </p>
                                <label for="estado" class="block font-medium text-sm text-gray-700">Estado de la Solicitud</label>
                                <select id="estado" name="estado" class="block mt-1 w-full form-select rounded-md shadow-sm">
                                    <option value="Pendiente" @selected($solicitud->estado === 'Pendiente')>Pendiente</option>
                                    <option value="Aprobada" @selected($solicitud->estado === 'Aprobada')>Aprobada</option>
                                </select>
                                @error('estado')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        @endif

                        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <a href="{{ route('solicitudes.index') }}" class="mr-4 inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:shadow-outline-gray transition ease-in-out duration-150">
                                {{ __('Cancelar') }}
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-purple-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-800">
                                Actualizar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>