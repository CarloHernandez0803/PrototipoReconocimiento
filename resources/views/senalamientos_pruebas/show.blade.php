<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalles del Lote de Señalamientos para Prueba') }}
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
                                        {{ $lote->id_senalamiento_prueba }}
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <th scope="col" class="px-6 py-3 bg-purple-900 text-left text-xs font-medium text-white uppercase tracking-wider">
                                        {{ __('Descripción') }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">
                                        {{ $lote->descripcion }}
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <th scope="col" class="px-6 py-3 bg-purple-900 text-left text-xs font-medium text-white uppercase tracking-wider">
                                        {{ __('Categoría') }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">
                                        {{ $lote->categoria }}
                                    </td>
                                </tr>

                                <tr class="border-b">
                                    <th scope="col" class="px-6 py-3 bg-purple-900 text-left text-xs font-medium text-white uppercase tracking-wider">
                                        {{ __('Imágenes') }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                            @foreach($imagenes as $index => $ruta)
                                                <div class="relative group">
                                                    <div class="h-32 w-full bg-gray-100 rounded-md shadow-md flex items-center justify-center overflow-hidden">
                                                        <img src="{{ route('senalamientos.prueba.imagen', ['id' => $lote->id_senalamiento_prueba, 'index' => $index]) }}?v={{ time() }}" 
                                                            alt="Imagen {{ $index + 1 }}" 
                                                            class="max-h-full max-w-full object-contain"
                                                            onerror="this.onerror=null;this.src='{{ asset('images/placeholder.png') }}'">
                                                    </div>
                                                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity bg-black bg-opacity-30">
                                                        <button onclick="mostrarImagenModal('{{ route('senalamientos.prueba.imagen', ['id' => $lote->id_senalamiento_prueba, 'index' => $index]) }}')" 
                                                                class="bg-white text-black px-2 py-1 rounded text-xs font-bold">
                                                            Ver Completa
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                                
                                <tr class="border-b">
                                    <th scope="col" class="px-6 py-3 bg-purple-900 text-left text-xs font-medium text-white uppercase tracking-wider">
                                        {{ __('Fecha de creación') }}
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 bg-white divide-y divide-gray-200">
                                        {{ $lote->fecha_creacion ? $lote->fecha_creacion->format('d/m/Y H:i') : __('No disponible') }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block mt-8 flex space-x-4">
                <a href="{{ route('senalamientos_pruebas.index') }}" class="bg-purple-900 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Volver a la lista') }}
                </a>
            </div>
        </div>
    </div>

    <div id="imagenModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-75">
        <div class="bg-white rounded-lg max-w-4xl max-h-screen overflow-auto p-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">Imagen Completa</h3>
                <button onclick="document.getElementById('imagenModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
                    &times;
                </button>
            </div>
            <img id="modalImagen" src="" alt="Imagen completa" class="max-w-full max-h-[80vh] mx-auto">
        </div>
    </div>

    <script>
        function mostrarImagenModal(url) {
            document.getElementById('modalImagen').src = url;
            document.getElementById('imagenModal').classList.remove('hidden');
        }
    </script>
</x-app-layout>