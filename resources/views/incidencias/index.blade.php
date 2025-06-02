<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Lista de Reportes de Incidencias
        </h2>
    </x-slot>

    <div>
        <div class="max-w-6xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="block mb-8">
                <a href="{{ route('incidencias.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Añadir Incidencia</a>
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
                                            Tipo de incidencia
                                        </th>
                                        <th scope="col" class="px-6 py-3 bg-purple-900 text-left text-xs font-medium text-white uppercase tracking-wider">
                                            Descripción
                                        </th>
                                        <th scope="col" class="px-6 py-3 bg-purple-900 text-left text-xs font-medium text-white uppercase tracking-wider">
                                            Estado
                                        </th>
                                        <th scope="col" width="200" class="px-6 py-3 bg-purple-900">
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($incidencias as $incidencia)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $incidencia->id_incidencia }}
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    {{ $incidencia->tipo_experiencia }}
                                                </span>
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $incidencia->descripcion }}
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if ($incidencia->resolucion)
                                                    <span class="px-2 py-1 rounded-full text-xs leading-5 font-semibold 
                                                    @if ($incidencia->resolucion->estado == 'Resuelto')
                                                        bg-green-100 text-green-800
                                                    @elseif ($incidencia->resolucion->estado == 'En Proceso')
                                                        bg-yellow-100 text-yellow-800
                                                    @else
                                                        bg-gray-100 text-gray-800
                                                    @endif">
                                                        {{ $incidencia->resolucion->estado }}
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 rounded-full text-xs leading-5 font-semibold bg-red-100 text-red-800">
                                                        Sin Resolución
                                                    </span>
                                                @endif
                                            </td>

                                            @if(Auth::user()->id_usuario === $incidencia->coordinador || Auth::user()->rol === 'Administrador')
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <a href="{{ route('incidencias.show', $incidencia->id_incidencia) }}" class="text-blue-600 hover:text-blue-900 mb-2 mr-2">Ver</a>
                                                    @if(Auth::user()->id_usuario === $incidencia->coordinador)
                                                        <a href="{{ route('incidencias.edit', $incidencia->id_incidencia) }}" class="text-indigo-600 hover:text-indigo-900 mb-2 mr-2">Editar</a>
                                                    @endif
                                                    <form class="inline-block" action="{{ route('incidencias.destroy', $incidencia->id_incidencia) }}" method="POST" onsubmit="return confirm('¿Estás seguro?');">
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
                {{ $incidencias->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
