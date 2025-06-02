<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Lista de Preguntas y Respuestas del Sistema
        </h2>
    </x-slot>

    <div>
        <div class="max-w-6xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="block mb-8">
                <a href="{{ route('preguntas.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Añadir Pregunta</a>
            </div>
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 w-full">
                                <thead>
                                    <tr>
                                        <th scope="col" width="50" class="px-6 py-3 bg-purple-900 text-left text-xs font-medium text-white uppercase tracking-wider">
                                            ID
                                        </th>
                                        <th scope="col" class="px-6 py-3 bg-purple-900 text-left text-xs font-medium text-white uppercase tracking-wider">
                                            Pregunta
                                        </th>
                                        <th scope="col" class="px-6 py-3 bg-purple-900 text-left text-xs font-medium text-white uppercase tracking-wider">
                                            Descripción
                                        </th>
                                        <th scope="col" class="px-6 py-3 bg-purple-900 text-left text-xs font-medium text-white uppercase tracking-wider">
                                            Respuesta
                                        </th>
                                        <th scope="col" width="200" class="px-6 py-3 bg-purple-900">
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($preguntas as $pregunta)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $pregunta->id_pregunta }}
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    {{ $pregunta->titulo }}
                                                </span>
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $pregunta->descripcion }}
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $pregunta->respuesta ? $pregunta->respuesta : __('Sin respuesta') }}
                                            </td>

                                            @if(Auth::user()->id_usuario === $pregunta->usuario || Auth::user()->rol === 'Administrador')
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <a href="{{ route('preguntas.show', $pregunta->id_pregunta) }}" class="text-blue-600 hover:text-blue-900 mb-2 mr-2">Ver</a>
                                                    <a href="{{ route('preguntas.edit', $pregunta->id_pregunta) }}" class="text-indigo-600 hover:text-indigo-900 mb-2 mr-2">Editar</a>
                                                    <form class="inline-block" action="{{ route('preguntas.destroy', $pregunta->id_pregunta) }}" method="POST" onsubmit="return confirm('¿Estás seguro?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="submit" class="text-red-600 hover:text-red-900 mb-2 mr-2" value="Eliminar">
                                                    </form>
                                                </td>
                                            @else
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <span class="text-black-600 hover:text-black-900 mb-2 mr-2">Sin acciones disponibles</span>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                {{ $preguntas->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
