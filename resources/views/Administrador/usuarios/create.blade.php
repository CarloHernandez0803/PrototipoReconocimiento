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

                <div>
                    <x-label for="nombre" value="{{ __('Nombre') }}" />
                    <x-input id="nombre" class="block mt-1 w-full" type="text" name="nombre" :value="old('nombre')" required autofocus />
                </div>

                <div class="mt-4">
                    <x-label for="apellido" value="{{ __('Apellido') }}" />
                    <x-input id="apellido" class="block mt-1 w-full" type="text" name="apellido" :value="old('apellido')" required />
                </div>

                <div class="mt-4">
                    <x-label for="correo" value="{{ __('Correo') }}" />
                    <x-input id="correo" class="block mt-1 w-full" type="email" name="correo" :value="old('correo')" required />
                </div>

                <div class="mt-4">
                    <x-label for="password" value="{{ __('Contraseña') }}" />
                    <x-input id="password" class="block mt-1 w-full" type="password" name="password" required />
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
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-button class="ms-4">
                        {{ __('Create') }}
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
