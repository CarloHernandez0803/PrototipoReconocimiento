<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detalles del Usuario #{{ $usuario->id_usuario }}
            </h2>
            <a href="{{ route('usuarios.index') }}" class="px-4 py-2 bg-purple-900 text-white rounded hover:bg-purple-800 transition duration-300 text-sm font-medium">
                Volver a la Lista
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            
            <div class="px-4 py-5 sm:px-6 bg-purple-900">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <img class="h-12 w-12 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($usuario->nombre) }}&color=7F9CF5&background=EBF4FF" alt="Avatar de {{ $usuario->nombre }}">
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold leading-6 text-white">
                            {{ $usuario->nombre }} {{ $usuario->apellidos }}
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-white">
                            ID de Usuario: {{ $usuario->id_usuario }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Nombre Completo</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $usuario->nombre }} {{ $usuario->apellidos }}</dd>
                    </div>

                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Correo Electrónico</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $usuario->correo }}</dd>
                    </div>

                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Rol</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                             <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($usuario->rol === 'Administrador') bg-purple-100 text-purple-800
                                @elseif($usuario->rol === 'Coordinador') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $usuario->rol }}
                            </span>
                        </dd>
                    </div>

                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Fecha de Registro</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $usuario->fecha_registro ? \Carbon\Carbon::parse($usuario->fecha_registro)->format('d/m/Y H:i:s') : 'No disponible' }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</x-app-layout>