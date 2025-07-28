<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Usuario
        </h2>
    </x-slot>

    <div>
        <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form method="post" action="{{ route('usuarios.update', $usuario->id_usuario) }}">
                    @csrf
                    @method('PUT') <div class="shadow overflow-hidden sm:rounded-md">
                        <div class="px-4 py-5 bg-white sm:p-6">
                            <label for="nombre" class="block font-medium text-sm text-gray-700">{{ __('Nombre') }}</label>
                            <input type="text" name="nombre" id="nombre" class="form-input rounded-md shadow-sm mt-1 block w-full"
                                   value="{{ old('nombre', $usuario->nombre) }}" />
                            @error('nombre')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="px-4 py-5 bg-white sm:p-6">
                            <label for="apellidos" class="block font-medium text-sm text-gray-700">{{ __('Apellidos') }}</label>
                            <input type="text" name="apellidos" id="apellidos" class="form-input rounded-md shadow-sm mt-1 block w-full"
                                   value="{{ old('apellidos', $usuario->apellidos) }}" />
                            @error('apellidos')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="px-4 py-5 bg-white sm:p-6">
                            <label for="correo" class="block font-medium text-sm text-gray-700">{{ __('Correo') }}</label>
                            <input type="email" name="correo" id="correo" class="form-input rounded-md shadow-sm mt-1 block w-full"
                                   value="{{ old('correo', $usuario->correo) }}" />
                            @error('correo')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="px-4 py-5 bg-white sm:p-6">
                            <label for="password" class="block font-medium text-sm text-gray-700">{{ __('Nueva Contraseña (Opcional)') }}</label>
                            <input type="password" name="password" id="password" class="form-input rounded-md shadow-sm mt-1 block w-full" />
                            <p class="text-xs text-gray-500 mt-1">Dejar en blanco para no cambiar la contraseña actual.</p>
                            @if ($errors->has('password'))
                                <div class="mt-2 space-y-1">
                                    @foreach ($errors->get('password') as $error)
                                        <p class="text-sm text-red-600">{{ $error }}</p>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="px-4 py-5 bg-white sm:p-6">
                             <label for="password_confirmation" class="block font-medium text-sm text-gray-700">{{ __('Confirmar Nueva Contraseña') }}</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-input rounded-md shadow-sm mt-1 block w-full" />
                        </div>

                        <div class="px-4 py-5 bg-white sm:p-6">
                            <label for="rol" class="block font-medium text-sm text-gray-700">{{ __('Rol') }}</label>
                            <select name="rol" id="rol" class="form-select rounded-md shadow-sm mt-1 block w-full">
                                <option value="Administrador" {{ old('rol', $usuario->rol) == 'Administrador' ? 'selected' : '' }}>
                                    {{ __('Administrador') }}
                                </option>
                                <option value="Coordinador" {{ old('rol', $usuario->rol) == 'Coordinador' ? 'selected' : '' }}>
                                    {{ __('Coordinador') }}
                                </option>
                                <option value="Alumno" {{ old('rol', $usuario->rol) == 'Alumno' ? 'selected' : '' }}>
                                    {{ __('Alumno') }}
                                </option>
                            </select>
                            @error('rol')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <a href="{{ route('usuarios.index') }}" class="mr-4 inline-flex items-center px-4 py-2 bg-gray-300 border rounded-md font-semibold text-xs text-gray-800 uppercase hover:bg-gray-400">
                                {{ __('Cancelar') }}
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-purple-900 border rounded-md font-semibold text-xs text-white uppercase hover:bg-purple-800">
                                {{ __('Actualizar') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>