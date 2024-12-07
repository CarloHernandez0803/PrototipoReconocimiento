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
                    @method('put')
                    <div class="shadow overflow-hidden sm:rounded-md">
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
                            <label for="contraseña" class="block font-medium text-sm text-gray-700">{{ __('Contraseña') }}</label>
                            <input type="password" name="contraseña" id="contraseña" class="form-input rounded-md shadow-sm mt-1 block w-full" />
                            @error('contraseña')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
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
