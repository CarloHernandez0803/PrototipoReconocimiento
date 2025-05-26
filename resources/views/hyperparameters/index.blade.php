<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editor de Hiperparámetros CNN
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900">
                            Configuración de Hiperparámetros
                        </h3>
                    </div>
                    <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                        <form method="POST" action="{{ route('hyperparameters.store') }}" class="p-6">
                        @csrf

                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">Hiperparámetros Básicos</h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <div class="space-y-2">
                                    <label class="text-sm font-medium">Épocas</label>
                                    <input type="number" name="epocas" value="50" class="w-full p-2 border rounded">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium">Altura</label>
                                    <input type="number" name="altura" value="100" class="w-full p-2 border rounded">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium">Anchura</label>
                                    <input type="number" name="anchura" value="100" class="w-full p-2 border rounded">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium">Batch Size</label>
                                    <input type="number" name="batch_size" value="2" class="w-full p-2 border rounded">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium">Pasos</label>
                                    <input type="number" name="pasos" value="100" class="w-full p-2 border rounded">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium">Clases</label>
                                    <input type="number" name="clases" value="5" class="w-full p-2 border rounded">
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">Aumento de Datos</h3>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="space-y-2">
                                    <label class="text-sm font-medium">Rescale</label>
                                    <input type="number" step="0.01" name="rescale" value="0.005" class="w-full p-2 border rounded">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium">Zoom Range</label>
                                    <input type="number" step="0.01" name="zoom_range" value="0.20" class="w-full p-2 border rounded">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium">Horizontal Flip</label>
                                    <select name="horizontal_flip" class="w-full p-2 border rounded">
                                        <option value="1">Sí</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium">Vertical Flip</label>
                                    <select name="vertical_flip" class="w-full p-2 border rounded">
                                        <option value="1">Sí</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">Arquitectura CNN</h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <div class="space-y-2">
                                    <label class="text-sm font-medium">Kernels Capa 1</label>
                                    <input type="number" name="kernels1" value="32" class="w-full p-2 border rounded">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium">Kernels Capa 2</label>
                                    <input type="number" name="kernels2" value="64" class="w-full p-2 border rounded">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium">Kernels Capa 3</label>
                                    <input type="number" name="kernels3" value="128" class="w-full p-2 border rounded">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium">Dropout Rate</label>
                                    <input type="number" step="0.1" name="dropout_rate" value="0.5" class="w-full p-2 border rounded">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-4 mt-6">
                            <button type="submit" class="px-4 py-2 bg-purple-900 text-white rounded">
                                Iniciar Entrenamiento
                            </button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900">
                            Historial de Entrenamientos
                        </h3>
                    </div>
                    <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Fecha
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Acierto (%)
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Pérdida
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Hiperparámetros
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($historial as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $item->fecha_creacion }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $item->acierto }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $item->perdida }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-500">
                                                <a href="{{ route('hyperparameters.details', $item->id_historial) }}" class="hover:underline">
                                                    Ver Detalles
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>