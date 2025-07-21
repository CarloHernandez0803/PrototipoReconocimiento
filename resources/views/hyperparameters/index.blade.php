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
                    <div class="border-t border-gray-200">
                        <form id="training-form" method="POST" action="{{ route('hyperparameters.store') }}" class="p-6">
                            @csrf
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold mb-4">Hiperparámetros Básicos</h3>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                    <div class="space-y-2"><label class="text-sm font-medium">Épocas</label><input type="number" name="epocas" value="50" class="w-full p-2 border rounded"></div>
                                    <div class="space-y-2"><label class="text-sm font-medium">Altura</label><input type="number" name="altura" value="100" class="w-full p-2 border rounded"></div>
                                    <div class="space-y-2"><label class="text-sm font-medium">Anchura</label><input type="number" name="anchura" value="100" class="w-full p-2 border rounded"></div>
                                    <div class="space-y-2"><label class="text-sm font-medium">Batch Size</label><input type="number" name="batch_size" value="2" class="w-full p-2 border rounded"></div>
                                    <div class="space-y-2"><label class="text-sm font-medium">Clases</label><input type="number" name="clases" value="5" class="w-full p-2 border rounded"></div>
                                </div>
                            </div>
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold mb-4">Aumento de Datos</h3>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div class="space-y-2"><label class="text-sm font-medium">Rescale</label><input type="number" step="0.001" name="rescale" value="0.005" class="w-full p-2 border rounded"></div>
                                    <div class="space-y-2"><label class="text-sm font-medium">Zoom Range</label><input type="number" step="0.01" name="zoom_range" value="0.20" class="w-full p-2 border rounded"></div>
                                    <div class="space-y-2"><label class="text-sm font-medium">Horizontal Flip</label><select name="horizontal_flip" class="w-full p-2 border rounded"><option value="1" selected>Sí</option><option value="0">No</option></select></div>
                                    <div class="space-y-2"><label class="text-sm font-medium">Vertical Flip</label><select name="vertical_flip" class="w-full p-2 border rounded"><option value="1" selected>Sí</option><option value="0">No</option></select></div>
                                </div>
                            </div>
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold mb-4">Arquitectura CNN</h3>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                    <div class="space-y-2"><label class="text-sm font-medium">Kernels Capa 1</label><input type="number" name="kernels1" value="32" class="w-full p-2 border rounded"></div>
                                    <div class="space-y-2"><label class="text-sm font-medium">Kernels Capa 2</label><input type="number" name="kernels2" value="64" class="w-full p-2 border rounded"></div>
                                    <div class="space-y-2"><label class="text-sm font-medium">Kernels Capa 3</label><input type="number" name="kernels3" value="128" class="w-full p-2 border rounded"></div>
                                    <div class="space-y-2"><label class="text-sm font-medium">Dropout Rate</label><input type="number" step="0.1" name="dropout_rate" value="0.5" class="w-full p-2 border rounded"></div>
                                    <div class="space-y-2"><label class="text-sm font-medium">Tasa de aprendizaje</label><input type="number" step="0.0001" name="learning_rate" value="0.001" class="w-full p-2 border rounded"></div>
                                </div>
                            </div>
                            <div class="flex justify-end space-x-4 mt-6">
                                <button type="submit" class="px-4 py-2 bg-purple-900 text-white rounded hover:bg-purple-800 transition duration-300 disabled:opacity-50">
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
                            Historial Reciente
                        </h3>
                    </div>
                    <div class="border-t border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acierto (%)</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pérdida</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($historial as $item)
                                    <tr class="clickable-row" data-href="{{ route('hyperparameters.show', $item->id_historial) }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($item->modelo !== 'pendiente')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Completado</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pendiente</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->acierto > 0 ? number_format($item->acierto, 2) : 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->perdida > 0 ? number_format($item->perdida, 4) : 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">No hay registros</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div id="notification-container"></div>
    
    <style>
        .clickable-row { cursor: pointer; transition: background-color 0.2s; }
        .clickable-row:hover { background-color: #f9fafb; }
    </style>

    <script>
        // --- INICIO DE LÓGICA MEJORADA ---

        function dismissProgress(trainingId) {
            const widget = document.getElementById('progress-widget-' + trainingId);
            if (widget) widget.remove();
            let dismissed = JSON.parse(localStorage.getItem('dismissed_trainings')) || [];
            if (!dismissed.includes(trainingId)) {
                dismissed.push(trainingId);
                localStorage.setItem('dismissed_trainings', JSON.stringify(dismissed));
            }
        }

        async function monitorTrainingProgress(trainingId) {
            if (document.getElementById('progress-widget-' + trainingId)) return;
            const progressContainer = document.createElement('div');
            progressContainer.id = 'progress-widget-' + trainingId;
            progressContainer.className = 'fixed bottom-5 right-5 bg-white p-4 rounded-lg shadow-xl border border-gray-200 z-50 w-80';
            progressContainer.innerHTML = `
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0"><svg class="animate-spin h-5 w-5 text-purple-900" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></div>
                    <div class="flex-1">
                        <div class="flex justify-between items-center"><h4 class="font-medium text-sm">Entrenamiento #${trainingId}</h4><button onclick="dismissProgress('${trainingId}')" class="text-gray-400 hover:text-gray-600">&times;</button></div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2"><div id="progress-bar-${trainingId}" class="bg-purple-700 h-2.5 rounded-full transition-all duration-300" style="width: 0%"></div></div>
                        <div id="progress-message-${trainingId}" class="text-xs text-gray-600 mt-1">Encolado...</div>
                        <div id="progress-details-${trainingId}" class="text-xs text-gray-500 mt-1 border-t pt-1 mt-2"></div>
                    </div>
                </div>`;
            document.body.appendChild(progressContainer);

            const interval = setInterval(async () => {
                try {
                    const response = await fetch(`/hyperparameters/check-progress?training_id=${trainingId}`);
                    if (!response.ok) return;
                    const data = await response.json();
                    
                    const widget = document.getElementById(`progress-widget-${trainingId}`);
                    if (!widget) { clearInterval(interval); return; }

                    const bar = widget.querySelector(`#progress-bar-${trainingId}`);
                    const msg = widget.querySelector(`#progress-message-${trainingId}`);
                    const details = widget.querySelector(`#progress-details-${trainingId}`);

                    if (data.status === 'completed' || data.status === 'error') {
                        clearInterval(interval);
                        dismissProgress(trainingId);
                        window.location.reload();
                    } else {
                        bar.style.width = `${data.percent || 0}%`;
                        msg.textContent = data.message || 'Procesando...';
                        
                        let detailsText = '';
                        if(data.current_epoch && data.total_epochs) {
                           detailsText += `Época: ${data.current_epoch}/${data.total_epochs}`;
                        }
                        if(data.val_accuracy) {
                           detailsText += ` | Precisión: ${(data.val_accuracy * 100).toFixed(2)}%`;
                        }
                        details.textContent = detailsText;
                    }
                } catch (error) { clearInterval(interval); }
            }, 3000);
        }
        
        document.getElementById('training-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const form = this;
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = 'Iniciar Entrenamiento';
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = `<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Procesando...`;
            
            try {
                const response = await fetch(form.action, { method: 'POST', body: new FormData(form), headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' } });
                const data = await response.json();

                if (response.ok && data.status === 'success') {
                    monitorTrainingProgress(data.training_id);
                    form.reset();
                } else {
                    alert(data.message || 'Ocurrió un error en el servidor.');
                }
            } catch (error) {
                alert('Error de red al enviar el formulario.');
            } finally {
                // Se asegura que el botón se reactive sin importar el resultado
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const trainingId = urlParams.get('training_id');
            const dismissed = JSON.parse(localStorage.getItem('dismissed_trainings')) || [];
            if (trainingId && !dismissed.includes(trainingId)) {
                if (!document.getElementById('progress-widget-' + trainingId)) {
                    monitorTrainingProgress(trainingId);
                }
            }

            document.querySelectorAll('.clickable-row').forEach(row => {
                row.addEventListener('click', () => {
                    window.location.href = row.dataset.href;
                });
            });
        });
    </script>
</x-app-layout>