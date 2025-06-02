<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalles de la Evaluación al Sistema de Reconocimiento') }}
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
                                        {{ $evaluacion->id_evaluacion }}
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <th scope="col" class="px-6 py-3 bg-purple-900 text-left text-xs font-medium text-white uppercase tracking-wider">
                                        {{ __('Categoría de Señal') }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">
                                        {{ $evaluacion->categoria_senal }}
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <th scope="col" class="px-6 py-3 bg-purple-900 text-left text-xs font-medium text-white uppercase tracking-wider">
                                        {{ __('Señales correctas') }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">
                                        {{ $evaluacion->senales_correctas }}
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <th scope="col" class="px-6 py-3 bg-purple-900 text-left text-xs font-medium text-white uppercase tracking-wider">
                                        {{ __('Señales totales') }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">
                                        {{ $evaluacion->senales_totales }}
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <th scope="col" class="px-6 py-3 bg-purple-900 text-left text-xs font-medium text-white uppercase tracking-wider">
                                        {{ __('Calificación media') }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">
                                        {{ $evaluacion->calificacion_media }}
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <th scope="col" class="px-6 py-3 bg-purple-900 text-left text-xs font-medium text-white uppercase tracking-wider">
                                        {{ __('Comentarios') }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">
                                        {{ $evaluacion->comentarios }}
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <th scope="col" class="px-6 py-3 bg-purple-900 text-left text-xs font-medium text-white uppercase tracking-wider">
                                        {{ __('Fecha de evaluación') }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">
                                        {{ $evaluacion->fecha_evaluacion ? $evaluacion->fecha_evaluacion->format('d/m/Y H:i') : __('No disponible') }}
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <th scope="col" class="px-6 py-3 bg-purple-900 text-left text-xs font-medium text-white uppercase tracking-wider">
                                        {{ __('Usuario') }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">
                                        {{ $evaluacion->usuarioAlumno->nombre }} {{ $evaluacion->usuarioAlumno->apellidos }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block mt-8">
                <a href="{{ route('evaluaciones.index') }}" class="bg-purple-900 hover:bg-gray-300 text-white font-bold py-2 px-4 rounded">{{ __('Volver a la lista') }}</a>
            </div>
        </div>
    </div>
</x-app-layout>
