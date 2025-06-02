<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalles del Usuario') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-6xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full table-fixed divide-y divide-gray-200 w-full">
                                <tr class="border-b">
                                    <th scope="col" class="px-6 py-3 w-1/6 bg-purple-900 text-left text-xs font-medium text-white uppercase tracking-wider">
                                        {{ __('ID') }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">
                                        {{ $usuario->id_usuario }}
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <th scope="col" class="px-6 py-3 w-1/6 bg-purple-900 text-left text-xs font-medium text-white uppercase tracking-wider">
                                        {{ __('Nombre') }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">
                                        {{ $usuario->nombre }}
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <th scope="col" class="px-6 py-3 w-1/6 bg-purple-900 text-left text-xs font-medium text-white uppercase tracking-wider">
                                        {{ __('Apellidos') }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">
                                        {{ $usuario->apellidos }}
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <th scope="col" class="px-6 py-3 w-1/6 bg-purple-900 text-left text-xs font-medium text-white uppercase tracking-wider">
                                        {{ __('Correo') }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">
                                        {{ $usuario->correo }}
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <th scope="col" class="px-6 py-3 w-1/6 bg-purple-900 text-left text-xs font-medium text-white uppercase tracking-wider">
                                        {{ __('Rol') }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-purple-900">
                                        {{ $usuario->rol }}
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <th scope="col" class="px-6 py-3 w-1/6 bg-purple-900 text-left text-xs font-medium text-white divide-purple-900 uppercase tracking-wider">
                                        {{ __('Fecha de Registro') }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">
                                        {{ $usuario->fecha_registro ? $usuario->fecha_registro->format('d/m/Y H:i') : __('No disponible') }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block mt-8">
                <a href="{{ route('usuarios.index') }}" class="bg-purple-900 hover:bg-gray-300 text-white font-bold py-2 px-4 rounded">{{ __('Volver a la lista') }}</a>
            </div>
        </div>
    </div>
</x-app-layout>
