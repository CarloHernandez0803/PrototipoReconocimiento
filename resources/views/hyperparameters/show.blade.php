<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detalles del Entrenamiento #{{ $historial->id_historial }}
            </h2>
            <a href="{{ route('hyperparameters.index') }}" class="bg-purple-900 hover:bg-gray-300 text-white font-bold py-2 px-4 rounded">
                Volver al Historial
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        @php
            // Decodificamos los hiperparámetros una vez para usarlos en la vista
            $params = json_decode($historial->hiperparametros, true);
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="md:col-span-2 space-y-6">
                
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900">
                            Resultados Principales
                        </h3>
                    </div>
                    <div class="border-t border-gray-200">
                        <dl>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Estado</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    @if($historial->modelo !== 'pendiente')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Completado
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Fallido o Incompleto
                                        </span>
                                    @endif
                                </dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Precisión (Acierto)</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 font-semibold">{{ number_format($historial->acierto, 2) }} %</dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Pérdida (Loss)</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ number_format($historial->perdida, 4) }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Tiempo de Entrenamiento</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    @php
                                        // Formatear segundos a un formato más legible
                                        $seconds = $historial->tiempo_entrenamiento;
                                        echo sprintf('%02d min, %02d seg', ($seconds/60), $seconds%60);
                                    @endphp
                                </dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Fecha de Creación</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ \Carbon\Carbon::parse($historial->fecha_creacion)->format('d/m/Y H:i:s') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                @if($historial->modelo !== 'pendiente')
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900">
                            Archivos Generados
                        </h3>
                    </div>
                    <div class="border-t border-gray-200 px-4 py-5">
                        <ul role="list" class="border border-gray-200 rounded-md divide-y divide-gray-200">
                            <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                <div class="w-0 flex-1 flex items-center">
                                    <svg class="flex-shrink-0 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" /></svg>
                                    <span class="ml-2 flex-1 w-0 truncate">{{ $historial->modelo }}</span>
                                </div>
                                <div class="ml-4 flex-shrink-0">
                                    <a href="{{ asset('storage/models/' . $historial->id_historial . '/' . $historial->modelo) }}" download class="font-medium text-purple-800 hover:text-purple-600">Descargar</a>
                                </div>
                            </li>
                            <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                <div class="w-0 flex-1 flex items-center">
                                    <svg class="flex-shrink-0 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" /></svg>
                                    <span class="ml-2 flex-1 w-0 truncate">{{ $historial->pesos }}</span>
                                </div>
                                <div class="ml-4 flex-shrink-0">
                                    <a href="{{ asset('storage/models/' . $historial->id_historial . '/' . $historial->pesos) }}" download class="font-medium text-purple-800 hover:text-purple-600">Descargar</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                @endif
            </div>

            <div class="md:col-span-1">
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900">
                            Hiperparámetros Utilizados
                        </h3>
                    </div>
                    <div class="border-t border-gray-200">
                        <dl>
                            @foreach ($params as $key => $value)
                            <div class="{{ $loop->odd ? 'bg-gray-50' : 'bg-white' }} px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500 capitalize">{{ str_replace('_', ' ', $key) }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    @if(is_bool($value))
                                        {{ $value ? 'Sí' : 'No' }}
                                    @else
                                        {{ $value }}
                                    @endif
                                </dd>
                            </div>
                            @endforeach
                        </dl>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>