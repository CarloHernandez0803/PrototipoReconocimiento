<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Prueba del Modelo CNN') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <!-- Mensajes de estado -->
        @if (session('success'))
            <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Panel principal -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <!-- Encabezado -->
            <div class="px-4 py-5 sm:px-6 bg-purple-900">
                <h3 class="text-lg font-medium text-white">
                    Clasificación de Señales de Tránsito
                </h3>
            </div>

            <!-- Contenido -->
            <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                <!-- Selector de categoría -->
                <div class="mb-8">
                    <h4 class="text-lg font-medium mb-4">Seleccione una categoría:</h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['Semáforo', 'Restrictiva', 'Advertencia', 'Tráfico', 'Informativa'] as $categoria)
                            <a href="{{ route('modulo_prueba.index', ['categoria' => $categoria]) }}" 
                               class="px-4 py-2 rounded {{ request('categoria') == $categoria ? 'bg-purple-600 text-white' : 'bg-gray-200 hover:bg-gray-300' }}">
                               {{ $categoria }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Imágenes disponibles -->
                @if(request('categoria'))
                    <div class="mb-8">
                        <h4 class="text-lg font-medium mb-4">Imágenes de {{ request('categoria') }}:</h4>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @forelse($imagenes as $imagen)
                                <a href="{{ route('modulo_prueba.classify', ['image' => $imagen]) }}" 
                                   class="block border rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                                    <div class="h-40 bg-gray-100 flex items-center justify-center">
                                        <img src="{{ Storage::disk('ftp')->url($imagen) }}" 
                                             alt="{{ basename($imagen) }}"
                                             class="max-h-full max-w-full object-contain"
                                             onerror="this.onerror=null;this.src='{{ asset('images/placeholder.png') }}'">
                                    </div>
                                    <div class="p-2 bg-white">
                                        <p class="text-sm text-gray-600 truncate">{{ basename($imagen) }}</p>
                                    </div>
                                </a>
                            @empty
                                <div class="col-span-full text-center py-8 text-gray-500">
                                    No se encontraron imágenes en esta categoría
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endif

                <!-- Resultados de clasificación -->
                @if(isset($resultado))
                    <div class="mt-8 border-t pt-8">
                        <h4 class="text-lg font-medium mb-4">Resultado de Clasificación:</h4>
                        <div class="flex flex-col md:flex-row gap-6">
                            <!-- Imagen seleccionada -->
                            <div class="md:w-1/3">
                                <div class="border rounded-lg overflow-hidden">
                                    <img src="{{ Storage::disk('ftp')->url($imagenSeleccionada) }}" 
                                         alt="Imagen clasificada"
                                         class="w-full h-auto"
                                         onerror="this.onerror=null;this.src='{{ asset('images/placeholder.png') }}'">
                                </div>
                                <p class="mt-2 text-sm text-gray-600 text-center">
                                    {{ basename($imagenSeleccionada) }}
                                </p>
                            </div>

                            <!-- Detalles del resultado -->
                            <div class="md:w-2/3">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="mb-4">
                                        <h5 class="font-medium text-gray-700">Categoría predicha:</h5>
                                        <p class="text-xl font-semibold text-purple-800 mt-1">
                                            {{ $resultado['clase'] ?? 'Desconocido' }}
                                        </p>
                                    </div>

                                    <div class="mb-4">
                                        <h5 class="font-medium text-gray-700">Nivel de confianza:</h5>
                                        <div class="w-full bg-gray-200 rounded-full h-4 mt-1">
                                            <div class="bg-purple-600 h-4 rounded-full" 
                                                 style="width: {{ ($resultado['confianza'] ?? 0) * 100 }}%">
                                            </div>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1">
                                            {{ round(($resultado['confianza'] ?? 0) * 100, 2) }}%
                                        </p>
                                    </div>

                                    <div>
                                        <h5 class="font-medium text-gray-700">Tiempo de procesamiento:</h5>
                                        <p class="text-gray-600">{{ $resultado['tiempo'] ?? 'N/A' }} segundos</p>
                                    </div>
                                </div>

                                <!-- Botón para probar otra imagen -->
                                <div class="mt-4">
                                    <a href="{{ route('modulo_prueba.index', ['categoria' => request('categoria')]) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded">
                                        Probar otra imagen
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Información del modelo actual -->
        <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 bg-gray-50">
                <h3 class="text-lg font-medium text-gray-900">
                    Información del Modelo Actual
                </h3>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                <p class="text-gray-600">
                    Este sistema utiliza un modelo convolucional entrenado para clasificar señales de tránsito.
                    Para actualizar el modelo, realice un nuevo entrenamiento desde el módulo correspondiente.
                </p>
                <div class="mt-4">
                    <span class="inline-block bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-medium">
                        Última actualización: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>