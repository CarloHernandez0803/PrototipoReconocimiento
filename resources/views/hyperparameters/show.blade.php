<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detalles del Entrenamiento #{{ $historial->id_historial }}
            </h2>
            <a href="{{ route('hyperparameters.index') }}" class="px-4 py-2 bg-purple-900 text-white rounded hover:bg-purple-800 transition duration-300 text-sm font-medium">
                &larr; Volver al Historial
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        @php
            $params = json_decode($historial->hiperparametros, true);
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 space-y-6">
                
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900">Resultados Principales</h3>
                    </div>
                    <div class="border-t border-gray-200">
                        <dl>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Estado</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    @if($historial->modelo !== 'pendiente')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Completado</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Fallido o Incompleto</span>
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
                        <h3 class="text-lg font-semibold leading-6 text-gray-900">Archivos Generados</h3>
                    </div>
                    <div class="border-t border-gray-200 px-4 py-5">
                        <ul role="list" class="border border-gray-200 rounded-md divide-y divide-gray-200">
                            <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                <div class="w-0 flex-1 flex items-center">
                                    <svg class="flex-shrink-0 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h2a2 2 0 002-2V4a2 2 0 00-2-2H9z" /><path d="M4 12a2 2 0 012-2h1.5a.5.5 0 000-1H6a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-1.5a.5.5 0 00-1 0V18a1 1 0 01-1 1H6a1 1 0 01-1-1v-6z" /></svg>
                                    <span class="ml-2 flex-1 w-0 truncate"><strong>Modelo:</strong> {{ $historial->modelo }}</span>
                                </div>
                            </li>
                            <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                <div class="w-0 flex-1 flex items-center">
                                    <svg class="flex-shrink-0 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h2a2 2 0 002-2V4a2 2 0 00-2-2H9z" /><path d="M4 12a2 2 0 012-2h1.5a.5.5 0 000-1H6a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-1.5a.5.5 0 00-1 0V18a1 1 0 01-1 1H6a1 1 0 01-1-1v-6z" /></svg>
                                    <span class="ml-2 flex-1 w-0 truncate"><strong>Pesos:</strong> {{ $historial->pesos }}</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                @endif
            </div>

            <div class="md:col-span-1">
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6"><h3 class="text-lg font-semibold">Hiperparámetros Utilizados</h3></div>
                    <div class="border-t border-gray-200">
                        <dl>
                            @foreach ($params as $key => $value)
                            <div class="{{ $loop->odd ? 'bg-gray-50' : 'bg-white' }} px-4 py-4 sm:grid sm:grid-cols-2 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500 capitalize">{{ str_replace('_', ' ', $key) }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0">
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