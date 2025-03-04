<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Crear Usuario
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <form method="post" action="{{ route('usuarios.store') }}" class="p-6">
                @csrf

                <div class="mt-4">
                    <x-label for="nombre" value="{{ __('Nombre') }}" />
                    <x-input id="nombre" class="block mt-1 w-full" type="text" name="nombre" :value="old('nombre')" required autofocus />
                    @error('nombre')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <x-label for="apellidos" value="{{ __('Apellidos') }}" />
                    <x-input id="apellidos" class="block mt-1 w-full" type="text" name="apellidos" :value="old('apellidos')" required />
                    @error('apellidos')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <x-label for="correo" value="{{ __('Correo') }}" />
                    <x-input id="correo" class="block mt-1 w-full" type="email" name="correo" :value="old('correo')" required />
                    @error('correo')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <x-label for="password" value="{{ __('Contraseña') }}" />
                    <x-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                    @error('password')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <x-label for="password_confirmation" value="{{ __('Confirmar Contraseña') }}" />
                    <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                </div>

                <div class="mt-4">
                    <x-label for="rol" value="{{ __('Rol') }}" />
                    <select id="rol" name="rol" class="block mt-1 w-full">
                        <option value="Administrador">{{ __('Administrador') }}</option>
                        <option value="Coordinador">{{ __('Coordinador') }}</option>
                        <option value="Alumno">{{ __('Alumno') }}</option>
                    </select>
                    @error('rol')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
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