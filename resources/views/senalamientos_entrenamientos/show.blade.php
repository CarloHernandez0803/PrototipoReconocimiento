<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detalles del Lote de Señalamientos para Entrenamiento #{{ $lote->id_senalamiento_entrenamiento }}
            </h2>
            <a href="{{ route('senalamientos_entrenamientos.index') }}" class="px-4 py-2 bg-purple-900 text-white rounded hover:bg-purple-800 transition duration-300 text-sm font-medium">
                Volver a la Lista
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            
            <div class="bg-purple-900 px-4 py-5 sm:px-6">
                <h3 class="text-lg font-semibold leading-6 text-white">
                    Información del Lote
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-white">
                    Detalles completos del lote de señalamientos para entrenamiento.
                </p>
            </div>

            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">ID del Lote</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $lote->id_senalamiento_entrenamiento }}</dd>
                    </div>

                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Nombre del Lote</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $lote->nombre_lote }}</dd>
                    </div>

                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Categoría</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                             <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                {{ $lote->categoria }}
                            </span>
                        </dd>
                    </div>
                    
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Fecha de Creación</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $lote->fecha_creacion ? \Carbon\Carbon::parse($lote->fecha_creacion)->format('d/m/Y H:i') : 'No disponible' }}</dd>
                    </div>

                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Descripción</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 whitespace-normal break-words">
                            {{ $lote->descripcion }}
                        </dd>
                    </div>

                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Imágenes del Lote</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            @if(count($imagenes) > 0)
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                    @foreach($imagenes as $index => $ruta)
                                        <div class="relative group">
                                            <div class="h-32 w-full bg-gray-200 rounded-md shadow-md flex items-center justify-center overflow-hidden">
                                                <img src="{{ route('senalamientos.entrenamiento.imagen', ['id' => $lote->id_senalamiento_entrenamiento, 'index' => $index]) }}?v={{ time() }}" 
                                                     alt="Imagen {{ $index + 1 }}" 
                                                     class="max-h-full max-w-full object-contain"
                                                     onerror="this.onerror=null;this.src='{{ asset('images/placeholder.png') }}'">
                                            </div>
                                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity bg-black bg-opacity-50 rounded-md">
                                                <button onclick="mostrarImagenModal('{{ route('senalamientos.entrenamiento.imagen', ['id' => $lote->id_senalamiento_entrenamiento, 'index' => $index]) }}?v={{ time() }}')" 
                                                        class="bg-white text-black px-2 py-1 rounded text-xs font-bold">
                                                    Ver Completa
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500">Este lote no contiene imágenes.</p>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <div id="imagenModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-75" onclick="this.classList.add('hidden')">
        <div class="bg-white rounded-lg max-w-4xl max-h-screen overflow-auto p-4" onclick="event.stopPropagation()">
            <div class="flex justify-end items-center mb-2">
                <button onclick="document.getElementById('imagenModal').classList.add('hidden')" class="text-gray-600 hover:text-gray-900 text-2xl font-bold">
                    &times;
                </button>
            </div>
            <img id="modalImagen" src="" alt="Imagen completa" class="max-w-full max-h-[85vh] mx-auto">
        </div>
    </div>

    <script>
        function mostrarImagenModal(url) {
            document.getElementById('modalImagen').src = url;
            document.getElementById('imagenModal').classList.remove('hidden');
        }
    </script>
</x-app-layout>