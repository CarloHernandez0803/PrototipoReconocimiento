<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalles de la Pregunta y Respuesta al Sistema') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-6xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 w-full">
                                <tr class="border-b">
                                    <th scope="col" class="px-6 py-3 bg-purple-900 text-left text-xs font-medium text-white uppercase tracking-wider">
                                        {{ __('ID') }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">
                                        {{ $pregunta->id_pregunta }}
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <th scope="col" class="px-6 py-3 bg-purple-900 text-left text-xs font-medium text-white uppercase tracking-wider">
                                        {{ __('Título de pregunta') }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">
                                        {{ $pregunta->titulo }}
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <th scope="col" class="px-6 py-3 bg-purple-900 text-left text-xs font-medium text-white uppercase tracking-wider">
                                        {{ __('Descripción') }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">
                                        {{ $pregunta->descripcion }}
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <th scope="col" class="px-6 py-3 bg-purple-900 text-left text-xs font-medium text-white uppercase tracking-wider">
                                        {{ __('Categoría de Pregunta') }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">
                                        {{ $pregunta->categoria }}
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <th scope="col" class="px-6 py-3 bg-purple-900 text-left text-xs font-medium text-white uppercase tracking-wider">
                                        {{ __('Estado de Pregunta') }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">
                                        {{ $pregunta->estado }}
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <th scope="col" class="px-6 py-3 bg-purple-900 text-left text-xs font-medium text-white uppercase tracking-wider">
                                        {{ __('Respuesta') }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">
                                        {{ $pregunta->respuesta ? $pregunta->respuesta : __('Sin respuesta') }}
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <th scope="col" class="px-6 py-3 bg-purple-900 text-left text-xs font-medium text-white uppercase tracking-wider">
                                        {{ __('Fecha de publicación') }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">
                                        {{ $pregunta->fecha_pub ? $pregunta->fecha_pub->format('d/m/Y H:i') : __('No disponible') }}
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <th scope="col" class="px-6 py-3 bg-purple-900 text-left text-xs font-medium text-white uppercase tracking-wider">
                                        {{ __('Fecha de actualización') }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">
                                        {{ $pregunta->fecha_act ? $pregunta->fecha_act->format('d/m/Y H:i') : __('No disponible') }}
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <th scope="col" class="px-6 py-3 bg-purple-900 text-left text-xs font-medium text-white uppercase tracking-wider">
                                        {{ __('Usuario') }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">
                                        {{ $pregunta->usuarioUser->nombre }} {{ $pregunta->usuarioUser->apellidos }} ({{ $pregunta->usuarioUser->rol }})
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block mt-8">
                <a href="{{ route('preguntas.index') }}" class="bg-purple-900 hover:bg-gray-300 text-white font-bold py-2 px-4 rounded">{{ __('Volver a la lista') }}</a>
            </div>
        </div>
    </div>
</x-app-layout>
