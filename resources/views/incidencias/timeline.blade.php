<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Seguimiento de Reportes de Fallos') }}
        </h2>
    </x-slot>

    <div class="max-w-6xl mx-auto py-6 px-4 space-y-4">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-4 border-b">
                <h3 class="text-xl font-semibold">Lista de Reportes</h3>
            </div>
            <div class="p-4 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Usuario
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Descripción
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fecha de Reporte
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acción
                            </th>
                        </tr>
                    </thead>
                    <tbody x-data="{ selectedRow: null }">
                        @foreach ($incidencias as $incidencia)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $incidencia->coordinador->nombre ?? 'Desconocido' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $incidencia->descripcion }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $incidencia->fecha_reporte->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-sm rounded-full 
                                        @if ($incidencia->resolucion?->estado === 'Resuelto') bg-green-100 text-green-800
                                        @elseif ($incidencia->resolucion?->estado === 'En Proceso') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ $incidencia->resolucion?->estado ?? 'Pendiente' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <button 
                                        @click="selectedRow = selectedRow === '{{ $incidencia->id_incidencia }}' ? null : '{{ $incidencia->id_incidencia }}'"
                                        class="text-blue-500 hover:text-blue-700"
                                    >
                                        <span x-text="selectedRow === '{{ $incidencia->id_incidencia }}' ? 'Ocultar línea de tiempo' : 'Ver línea de tiempo'">
                                            Ver línea de tiempo
                                        </span>
                                    </button>
                                </td>
                            </tr>
                            <tr 
                                x-show="selectedRow === '{{ $incidencia->id_incidencia }}'" 
                                x-cloak 
                                x-transition
                            >
                                <td colspan="5" class="px-6 py-4">
                                    <div class="bg-gray-50 rounded-lg p-4 space-y-4">
                                        <h4 class="font-semibold">Línea de Tiempo</h4>
                                        <ul class="space-y-4">
                                            <li class="flex items-start space-x-4">
                                                <div class="min-w-[100px] text-sm text-gray-500">
                                                    {{ $incidencia->fecha_reporte->format('d/m/Y H:i') }}
                                                </div>
                                                <div class="flex-grow">
                                                    <span class="px-2 py-1 rounded-full text-sm bg-gray-100 text-gray-800">
                                                        Reportado
                                                    </span>
                                                    <p class="mt-1 text-sm">Incidencia reportada</p>
                                                </div>
                                            </li>
                                            @if ($incidencia->resolucion)
                                                <li class="flex items-start space-x-4">
                                                    <div class="min-w-[100px] text-sm text-gray-500">
                                                        {{ $incidencia->resolucion->fecha_resolucion->format('d/m/Y H:i') ?? 'Desconocido' }}
                                                    </div>
                                                    <div class="flex-grow">
                                                        <span class="px-2 py-1 rounded-full text-sm
                                                            @if ($incidencia->resolucion->estado === 'Resuelto') bg-green-100 text-green-800
                                                            @elseif ($incidencia->resolucion->estado === 'En Proceso') bg-yellow-100 text-yellow-800
                                                            @else bg-red-100 text-red-800 @endif">
                                                            {{ $incidencia->resolucion->estado }}
                                                        </span>
                                                        <p class="mt-1 text-sm">Resolución: {{ $incidencia->resolucion->estado }}</p>
                                                    </div>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</x-app-layout>