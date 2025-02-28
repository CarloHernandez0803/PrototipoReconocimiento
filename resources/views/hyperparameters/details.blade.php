<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detalles del Entrenamiento
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg font-semibold leading-6 text-gray-900">
                    Hiperparámetros Utilizados
                </h3>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                <div class="p-6">
                    <pre class="bg-gray-100 p-4 rounded">{{ json_encode(json_decode($historial->hiperparametros), JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>