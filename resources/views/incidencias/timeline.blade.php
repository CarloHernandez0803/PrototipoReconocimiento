<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Seguimiento de Reportes de Fallos
            </h2>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="space-y-8">

            @forelse ($incidencias as $incidencia)
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold leading-6 text-gray-900">
                                    Incidencia #{{ $incidencia->id_incidencia }}: {{ $incidencia->tipo_incidencia }}
                                </h3>
                                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                                    Reportado por: {{ $incidencia->usuarioCoordinador->nombre . ' ' . $incidencia->usuarioCoordinador->apellidos ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if ($incidencia->estado_actual == 'Resuelto') bg-green-100 text-green-800
                                @elseif ($incidencia->estado_actual == 'En Proceso') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                    {{ $incidencia->estado_actual }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <div class="mb-6">
                            <h4 class="font-medium text-gray-700">Descripción del Problema:</h4>
                            <p class="mt-1 text-sm text-gray-900 whitespace-normal break-words">
                                {{ $incidencia->descripcion }}
                            </p>
                        </div>

                        <div>
                            <h4 class="font-medium text-gray-700 mb-4">Historial de Eventos:</h4>
                            <ol class="relative border-l border-gray-200 ml-3">                  
                                <li class="mb-10 ml-6">            
                                    <span class="absolute flex items-center justify-center w-6 h-6 bg-gray-200 rounded-full -left-3 ring-8 ring-white">
                                        <svg class="w-3 h-3 text-gray-600" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12zm-1-8a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1zm0 4a1 1 0 112 0 1 1 0 01-2 0z"/></svg>
                                    </span>
                                    <h3 class="flex items-center mb-1 text-base font-semibold text-gray-900">Incidencia Reportada</h3>
                                    <time class="block mb-2 text-sm font-normal leading-none text-gray-400">{{ $incidencia->fecha_reporte->format('d/m/Y H:i') }}</time>
                                </li>
                                
                                @foreach ($incidencia->resoluciones->reverse() as $resolucion)
                                <li class="mb-10 ml-6">            
                                    <span class="absolute flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full -left-3 ring-8 ring-white">
                                        <svg class="w-3 h-3 text-blue-800" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                                    </span>
                                    <h3 class="font-semibold text-gray-900">Actualización de Estado: {{ $resolucion->estado }}</h3>
                                    <time class="block mb-2 text-sm font-normal leading-none text-gray-400">{{ \Carbon\Carbon::parse($resolucion->fecha_resolucion)->format('d/m/Y H:i') }} por {{ $resolucion->administrador ? $resolucion->administrador->nombre . ' ' . $resolucion->administrador->apellidos : 'Admin' }}</time>
                                    <p class="mt-2 text-sm font-normal text-gray-600 bg-gray-50 border border-gray-200 rounded-md p-3">{{ $resolucion->comentario }}</p>
                                </li>
                                @endforeach
                            </ol>
                            @if(Auth::user()->rol === 'Administrador')
                                <div class="mt-4">
                                    <a href="{{ route('resoluciones.create', $incidencia->id_incidencia) }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-3 rounded shadow-sm text-xs">
                                        ➕ Añadir Actualización
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white text-center py-12 px-6 rounded-lg shadow">
                    <p class="text-gray-500">No hay incidencias para mostrar.</p>
                </div>
            @endforelse

            <div class="mt-4">
                {{ $incidencias->links() }}
            </div>
        </div>
    </div>
</x-app-layout>