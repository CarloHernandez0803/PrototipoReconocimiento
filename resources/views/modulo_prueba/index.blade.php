<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Prueba de Modelo CNN
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        @if ($errors->any())
            <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                <ul> @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6"><h3 class="text-lg font-semibold leading-6 text-gray-900">Clasificar una Nueva Imagen</h3></div>
                <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                    <form id="classify-form" action="{{ route('modulo_prueba.classify') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-6">
                            <div>
                                <label for="historial_id" class="block text-sm font-medium text-gray-700">1. Seleccione un Modelo Entrenado</label>
                                <select id="historial_id" name="historial_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm rounded-md">
                                    @forelse ($modelos as $modelo)
                                        <option value="{{ $modelo->id_historial }}">Modelo #{{ $modelo->id_historial }} (Precisión: {{ number_format($modelo->acierto, 2) }}%)</option>
                                    @empty
                                        <option disabled>No hay modelos entrenados disponibles</option>
                                    @endforelse
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">2. Suba una Imagen para Clasificar</label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md" id="image-dropzone">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 4v.01M28 8l-4.586-4.586a2 2 0 00-2.828 0L16 8m12 12h.01M28 8H12a4 4 0 00-4 4v20m32-12v8m0 4v.01M28 8l-4.586-4.586a2 2 0 00-2.828 0L16 8m12 12h.01" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="imagen" class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500"><span>Seleccione un archivo</span><input id="imagen" name="imagen" type="file" class="sr-only" accept="image/jpeg,image/png,image/jpg"></label>
                                            <p class="pl-1">o arrástrelo aquí</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, JPEG hasta 2MB</p>
                                    </div>
                                </div>
                                <div id="image-preview-container" class="mt-4 hidden"><p class="text-sm font-medium text-gray-700 mb-2">Vista previa:</p><img id="image-preview" src="#" alt="Vista previa" class="max-h-48 rounded-md mx-auto"/></div>
                            </div>
                        </div>
                        <div class="pt-5"><div class="flex justify-end"><button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-900 hover:bg-purple-800 disabled:opacity-50" id="classify-btn" disabled>Clasificar</button></div></div>
                    </form>
                </div>
            </div>
            <div>
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                     <div class="px-4 py-5 sm:px-6"><h3 class="text-lg font-semibold leading-6 text-gray-900">Resultado de la Clasificación</h3></div>
                    <div id="result-container" class="border-t border-gray-200 p-6 text-center min-h-[300px] flex items-center justify-center">
                         <div id="placeholder-result" class="text-gray-500"><svg class="h-16 w-16 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg><p>Esperando una imagen para clasificar...</p></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const imageInput = document.getElementById('imagen');
            const dropzone = document.getElementById('image-dropzone');
            const previewContainer = document.getElementById('image-preview-container');
            const previewImage = document.getElementById('image-preview');
            const classifyBtn = document.getElementById('classify-btn');
            const classifyForm = document.getElementById('classify-form');
            const resultContainer = document.getElementById('result-container');
            let activeInterval = null;

            imageInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        previewImage.src = e.target.result;
                        previewContainer.classList.remove('hidden');
                        classifyBtn.disabled = false;
                    };
                    reader.readAsDataURL(file);
                }
            });

            classifyForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                if (activeInterval) clearInterval(activeInterval);

                classifyBtn.disabled = true;
                classifyBtn.innerHTML = `<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Procesando...`;
                
                showLoadingState();

                try {
                    const response = await fetch(this.action, {
                        method: 'POST', body: new FormData(this), headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                    });

                    if (!response.ok) {
                        // Captura errores de validación del servidor u otros errores HTTP
                        const errorData = await response.json().catch(() => ({ message: 'Error desconocido del servidor.' }));
                        throw new Error(errorData.message);
                    }

                    const data = await response.json();
                    pollForResult(data.classification_id);

                } catch (error) {
                    showErrorState(error.message);
                }
            });

            function pollForResult(classificationId) {
                activeInterval = setInterval(async () => {
                    try {
                        const response = await fetch(`/modulo_prueba/check-status/${classificationId}`);
                        
                        if (!response.ok) {
                            clearInterval(activeInterval);
                            const errorText = await response.text();
                            console.error('El servidor respondió con un error:', errorText);
                            showErrorState(`Error del servidor (${response.status}). Revisa la consola del navegador.`);
                            return;
                        }

                        const data = await response.json();
                        
                        if (data.estado === 'completado') {
                            clearInterval(activeInterval);
                            showResultState(data);
                        } else if (data.estado === 'error' || data.estado === 'expirado') {
                            clearInterval(activeInterval);
                            showErrorState(`La clasificación falló (${data.estado}). Revisa los logs de Laravel.`);
                        }
                    } catch (error) {
                        clearInterval(activeInterval);
                        console.error('Error en la función de polling:', error);
                        showErrorState('Error de red al consultar el estado.');
                    }
                }, 2500);
            }

            function showLoadingState() {
                resultContainer.innerHTML = `
                    <div class="flex flex-col items-center justify-center animate-pulse">
                        <svg class="h-12 w-12 text-purple-900" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <p class="text-lg text-gray-600 mt-4">Clasificando, por favor espere...</p>
                        <p class="text-sm text-gray-400">(El worker está procesando la imagen)</p>
                    </div>`;
            }

            function showResultState(data) {
                const descriptions = {
                    'Advertencia': 'Precaución: presencia de un peligro potencial. Reduzca la velocidad y esté atento a la situación.',
                    'Informativa': 'Esta señal proporciona información útil sobre direcciones, servicios o puntos de interés.',
                    'Restrictiva': 'Indica una prohibición o limitación que debe ser obedecida. El incumplimiento puede acarrear sanciones.',
                    'Semáforo': 'Controla el flujo de tráfico. Obedezca las indicaciones para evitar accidentes.',
                    'Tráfico': 'Esta categoría agrupa señales relacionadas con la circulación. Manténgase atento.'
                };

                if (!data.resultado) {
                    showErrorState('El proceso terminó, pero no se pudo obtener un resultado válido.');
                    return;
                }
                const description = descriptions[data.resultado.clase] || '';

                resultContainer.innerHTML = `
                    <div class="space-y-4 text-center">
                        <div>
                            <p class="text-sm text-gray-500">Predicción:</p>
                            <p class="text-3xl font-bold text-purple-900">${data.resultado.clase}</p>
                        </div>
                        <div><p class="text-gray-600 text-sm italic max-w-xs mx-auto">${description}</p></div>
                        <div class="w-full max-w-xs mx-auto pt-2">
                            <p class="text-sm text-gray-500">Confianza: ${(data.resultado.confianza * 100).toFixed(2)}%</p>
                            <div class="w-full bg-gray-200 rounded-full h-2.5 mt-1">
                                <div class="bg-purple-600 h-2.5 rounded-full" style="width: ${data.resultado.confianza * 100}%"></div>
                            </div>
                            <p class="text-xs text-gray-400 mt-2">Tiempo: ${data.resultado.tiempo} segundos</p>
                        </div>
                    </div>`;
                classifyBtn.disabled = false;
                classifyBtn.innerHTML = 'Clasificar Otra Imagen';
            }

            function showErrorState(message) {
                resultContainer.innerHTML = `<div class="text-red-500 text-center"><p class="font-semibold">Error</p><p>${message}</p></div>`;
                classifyBtn.disabled = false;
                classifyBtn.innerHTML = 'Intentar de Nuevo';
            }

            dropzone.addEventListener('dragover', (e) => e.preventDefault());
            dropzone.addEventListener('drop', (e) => {
                e.preventDefault();
                if (e.dataTransfer.files.length) {
                    imageInput.files = e.dataTransfer.files;
                    imageInput.dispatchEvent(new Event('change'));
                }
            });
        });
    </script>
</x-app-layout>