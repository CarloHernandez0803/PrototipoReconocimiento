<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Clasificación de Imágenes
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg font-semibold leading-6 text-gray-900">
                    Red Neuronal Convolucional para la Detección de Señales de Tránsito
                </h3>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                <div class="p-6">
                    <h4 class="text-lg font-semibold mb-4">Seleccionar Imagen</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach ($images as $image)
                            <div class="text-center">
                                <a href="#" class="block">
                                    <img src="{{ asset('storage/images/' . $image) }}" alt="{{ $image }}" class="w-full h-32 object-cover rounded-lg">
                                    <p class="mt-2 text-sm text-gray-700">{{ $image }}</p>
                                </a>
                            </div>
                        @endforeach
                    </div>

                    @if (isset($result))
                        <div class="mt-6">
                            <h4 class="text-lg font-semibold">Resultado de la Clasificación</h4>
                            <p class="mt-2 text-gray-700">{{ $result }}</p>
                            <div class="mt-4">
                                <img src="{{ asset('storage/images/' . $selectedImage) }}" alt="Imagen Clasificada" class="max-w-full h-auto">
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>