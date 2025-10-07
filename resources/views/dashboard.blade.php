<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @if(Auth::user()->rol === 'Administrador')
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg p-4 sm:p-6 mb-8">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="flex border-b border-gray-200">
                            <nav class="-mb-px flex space-x-6" aria-label="Tabs">
                                <button data-tab="graficos" class="tab-button active-tab">Resumen Gráfico</button>
                                <button data-tab="tablas" class="tab-button">Reportes Detallados</button>
                            </nav>
                        </div>

                        <div class="flex items-center space-x-2 w-full sm:w-auto">
                            <input type="date" id="startDate" class="form-input rounded-md shadow-sm text-sm w-full">
                            <span class="text-gray-500">a</span>
                            <input type="date" id="endDate" class="form-input rounded-md shadow-sm text-sm w-full">
                            <button id="filterButton" class="px-4 py-2 bg-purple-900 text-white rounded-md hover:bg-purple-800 text-sm font-medium w-full sm:w-auto">Filtrar</button>
                        </div>
                    </div>
                </div>

                <div id="graficosSection" class="tab-content">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <div class="flex justify-between items-center mb-4"><h3 class="text-lg font-semibold text-gray-900">Carga de Lotes para Entrenamiento</h3><button onclick="downloadExcelReport('recursos')" class="download-button bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Descargar Excel</button></div>
                            <div class="relative h-96"><canvas id="recursosChart"></canvas></div>
                        </div>
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <div class="flex justify-between items-center mb-4"><h3 class="text-lg font-semibold text-gray-900">Rendimiento Histórico de Entrenamientos</h3><button onclick="downloadExcelReport('eficacia')" class="download-button bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Descargar Excel</button></div>
                            <div class="relative h-96"><canvas id="eficaciaChart"></canvas></div>
                        </div>
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <div class="flex justify-between items-center mb-4"><h3 class="text-lg font-semibold text-gray-900">Estado Actual de Solicitudes</h3><button onclick="downloadExcelReport('solicitudes')" class="download-button bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Descargar Excel</button></div>
                            <div class="relative h-96"><canvas id="solicitudesChart"></canvas></div>
                        </div>
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <div class="flex justify-between items-center mb-4"><h3 class="text-lg font-semibold text-gray-900">Tipos de Experiencias Reportadas</h3><button onclick="downloadExcelReport('experiencias')" class="download-button bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Descargar Excel</button></div>
                            <div class="relative h-96"><canvas id="experienciasChart"></canvas></div>
                        </div>
                    </div>
                </div>

                <div id="tablasSection" class="tab-content hidden space-y-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="flex justify-between items-center mb-4"><h2 class="text-xl font-bold">Resumen de Incidencias</h2><button onclick="downloadTableReport('incidencias', 'pdf')" class="download-button bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Descargar PDF</button></div>
                        <div id="loadingIndicatorIncidencias" class="hidden text-center py-4 text-gray-500"><p>Cargando datos...</p></div>
                        <div id="errorContainerIncidencias" class="hidden mb-4"></div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6"><div class="bg-blue-50 p-4 rounded-lg"><p class="text-sm text-gray-600">Total</p><p class="text-2xl font-bold" id="totalIncidencias">0</p></div><div class="bg-green-50 p-4 rounded-lg"><p class="text-sm text-gray-600">Resueltas</p><p class="text-2xl font-bold" id="incidenciasResueltas">0</p></div><div class="bg-yellow-50 p-4 rounded-lg"><p class="text-sm text-gray-600">% Resueltas</p><p class="text-2xl font-bold" id="porcentajeResueltas">0%</p></div><div class="bg-red-50 p-4 rounded-lg"><p class="text-sm text-gray-600">Tiempo Promedio</p><p class="text-2xl font-bold" id="tiempoPromedioIncidencias">N/A</p></div></div>
                        <div class="overflow-x-auto"><table class="min-w-full bg-white"><thead class="bg-gray-50"><tr><th class="py-2 px-4 border-b text-left text-xs font-medium text-gray-500 uppercase">Tipo</th><th class="py-2 px-4 border-b text-left text-xs font-medium text-gray-500 uppercase">Total</th><th class="py-2 px-4 border-b text-left text-xs font-medium text-gray-500 uppercase">Tiempo Promedio</th><th class="py-2 px-4 border-b text-left text-xs font-medium text-gray-500 uppercase">Estado</th><th class="py-2 px-4 border-b text-left text-xs font-medium text-gray-500 uppercase">Reportado por</th></tr></thead><tbody id="incidenciasTableBody" class="divide-y divide-gray-200"></tbody></table></div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="flex justify-between items-center mb-4"><h2 class="text-xl font-bold">Actividad de Usuarios</h2><button onclick="downloadTableReport('usuarios', 'pdf')" class="download-button bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Descargar PDF</button></div>
                        <div id="loadingIndicatorUsuarios" class="hidden text-center py-4 text-gray-500"><p>Cargando datos...</p></div>
                        <div id="errorContainerUsuarios" class="hidden mb-4"></div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6"><div class="bg-blue-50 p-4 rounded-lg"><p class="text-sm text-gray-600">Total Actividades</p><p class="text-2xl font-bold" id="totalActividades">0</p></div><div class="bg-green-50 p-4 rounded-lg"><p class="text-sm text-gray-600">Usuarios Activos</p><p class="text-2xl font-bold" id="usuariosActivos">0</p></div><div class="bg-yellow-50 p-4 rounded-lg"><p class="text-sm text-gray-600">Tiempo Promedio</p><p class="text-2xl font-bold" id="tiempoPromedioUsuarios">N/A</p></div></div>
                        <div class="overflow-x-auto"><table class="min-w-full bg-white"><thead class="bg-gray-50"><tr><th class="py-2 px-4 border-b text-left text-xs font-medium text-gray-500 uppercase">Nombre</th><th class="py-2 px-4 border-b text-left text-xs font-medium text-gray-500 uppercase">Rol</th><th class="py-2 px-4 border-b text-left text-xs font-medium text-gray-500 uppercase">Actividades</th><th class="py-2 px-4 border-b text-left text-xs font-medium text-gray-500 uppercase">Tiempo Promedio</th></tr></thead><tbody id="usuariosTableBody" class="divide-y divide-gray-200"></tbody></table></div>
                    </div>
                </div>
    @else
                <div class="py-12 bg-cover bg-center min-h-screen" style="background-image: url('images/background.jpg'); background-size: cover">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white bg-opacity-75 overflow-hidden shadow-xl sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-3xl font-bold text-gray-800 mb-2 text-center">Bienvenido, {{ Auth::user()->nombre . ' ' . Auth::user()->apellidos }}</h3>
                                <p class="text-gray-600 mb-6 text-lg text-center">
                                    {{ Auth::user()->rol === 'Coordinador' ? 'Panel de Control - Coordinador' : 'Panel de Estudiante' }}
                                </p>
                                
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                    <div class="bg-white p-6 rounded-lg shadow-md">
                                        <h4 class="text-xl font-semibold text-purple-900 mb-4 border-b pb-2">
                                            {{ Auth::user()->rol === 'Coordinador' ? 'Funciones Principales' : 'Mis Funciones' }}
                                        </h4>
                                        
                                        @if(Auth::user()->rol === 'Coordinador')
                                        <div class="space-y-3">
                                            <div class="flex items-start">
                                                <span class="text-green-500 mr-2">✓</span>
                                                <span><strong>Gestión de Solicitudes:</strong> Administrar solicitudes de prueba propias</span>
                                            </div>
                                            <div class="flex items-start">
                                                <span class="text-green-500 mr-2">✓</span>
                                                <span><strong>Gestión de Incidencias:</strong> Reportar y seguir incidencias del sistema</span>
                                            </div>
                                            <div class="flex items-start">
                                                <span class="text-green-500 mr-2">✓</span>
                                                <span><strong>Administración de Lotes:</strong> Gestionar imágenes para entrenamiento y pruebas</span>
                                            </div>
                                            <div class="flex items-start">
                                                <span class="text-blue-500 mr-2">✓</span>
                                                <span><strong>Gestión de Evaluaciones:</strong> Administrar evaluaciones/calificación de modelos de reconocimiento y redes neuronales</span>
                                            </div>
                                            <div class="flex items-start">
                                                <span class="text-blue-500 mr-2">✓</span>
                                                <span><strong>Gestión de Experiencias:</strong> Gestionar mis experiencias con el sistema</span>
                                            </div>
                                            <div class="flex items-start">
                                                <span class="text-blue-500 mr-2">✓</span>
                                                <span><strong>Gestión de Preguntas:</strong> Administrar preguntas y ver las de otros usuarios</span>
                                            </div>
                                            <div class="flex items-start">
                                                <span class="text-green-500 mr-2">✓</span>
                                                <span><strong>Entrenamiento de Modelos:</strong> Crear y entrenar modelos de reconocimiento</span>
                                            </div>
                                            <div class="flex items-start">
                                                <span class="text-blue-500 mr-2">✓</span>
                                                <span><strong>Pruebas:</strong> Realizar pruebas con modelos entrenados</span>
                                            </div>
                                            <div class="flex items-start">
                                                <span class="text-green-500 mr-2">✓</span>
                                                <span><strong>Calendario:</strong> Visualizar todas las solicitudes programadas</span>
                                            </div>
                                        </div>
                                        @else
                                        <div class="space-y-3">
                                            <div class="flex items-start">
                                                <span class="text-blue-500 mr-2">✓</span>
                                                <span><strong>Evaluaciones:</strong> Administrar evaluaciones/calificación de modelos de reconocimiento y redes neuronales</span>
                                            </div>
                                            <div class="flex items-start">
                                                <span class="text-blue-500 mr-2">✓</span>
                                                <span><strong>Experiencias:</strong> Gestionar mis experiencias con el sistema</span>
                                            </div>
                                            <div class="flex items-start">
                                                <span class="text-blue-500 mr-2">✓</span>
                                                <span><strong>Preguntas:</strong> Administrar preguntas y ver las de otros usuarios</span>
                                            </div>
                                            <div class="flex items-start">
                                                <span class="text-blue-500 mr-2">✓</span>
                                                <span><strong>Solicitudes:</strong> Ver el estado de mis solicitudes de prueba</span>
                                            </div>
                                            <div class="flex items-start">
                                                <span class="text-blue-500 mr-2">✓</span>
                                                <span><strong>Pruebas:</strong> Realizar pruebas con modelos entrenados</span>
                                            </div>
                                        </div>
                                        @endif
                                    </div>

                                    <div class="bg-white p-6 rounded-lg shadow-md">
                                        <h4 class="text-xl font-semibold text-purple-900 mb-4 border-b pb-2">
                                            {{ Auth::user()->rol === 'Coordinador' ? 'Vistas del Sistema' : 'Mi Actividad' }}
                                        </h4>
                                        
                                        @if(Auth::user()->rol === 'Coordinador')
                                        <div class="space-y-4">
                                            <p class="text-gray-700">
                                                Como coordinador, tienes acceso completo al sistema de reconocimiento de señales, incluyendo:
                                            </p>
                                            <ul class="list-disc list-inside text-gray-600 space-y-2">
                                                <li><strong>Línea de Tiempo:</strong> Seguimiento de cambios en incidencias</li>
                                                <li><strong>Entrenamientos Avanzados:</strong> Configuración de parámetros de modelos</li>
                                                <li><strong>Pruebas Programadas:</strong> Acceso en fechas autorizadas</li>
                                                <li><strong>Gestión Completa:</strong> Control sobre solicitudes, incidencias y recursos</li>
                                                <li><strong>Solicitudes:</strong> Visualización de calendario con todas las actividades</li>
                                                <li><strong>Retroalimentación:</strong> Compartir experiencias y preguntas</li>
                                            </ul>
                                        </div>
                                        @else
                                        <div class="space-y-4">
                                            <p class="text-gray-700">
                                                Como alumno, puedes interactuar con el sistema de reconocimiento mediante:
                                            </p>
                                            <ul class="list-disc list-inside text-gray-600 space-y-2">
                                                <li><strong>Pruebas Programadas:</strong> Acceso en fechas autorizadas</li>
                                                <li><strong>Modelos Pre-entrenados:</strong> Utilización de redes neuronales configuradas</li>
                                                <li><strong>Retroalimentación:</strong> Compartir experiencias y preguntas</li>
                                                <li><strong>Seguimiento:</strong> Monitoreo del estado de tus solicitudes</li>
                                            </ul>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-8 text-center">
                                    <p class="text-sm text-gray-500 mb-2">
                                        Utiliza el menú de navegación para acceder a todas las funciones disponibles.
                                    </p>
                                    <p class="text-xs text-gray-400">
                                        Sistema de Reconocimiento de Señales - {{ date('Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    @endif
        </div>
    </div>
    @if(Auth::user()->rol === 'Administrador')
        <style>
            .tab-button { transition: all 0.2s ease-in-out; border-bottom-width: 2px; padding-top: 1rem; padding-bottom: 1rem; padding-left: 0.25rem; padding-right: 0.25rem; font-size: 0.875rem; font-weight: 500; }
            .active-tab { border-color: #5b21b6; color: #6d28d9; }
            .tab-button:not(.active-tab) { border-color: transparent; color: #6b7280; }
            .tab-button:not(.active-tab):hover { color: #4b5563; border-color: #d1d5db; }
            .download-button { @apply px-3 py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded-md hover:bg-gray-200 transition; }
        </style>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const charts = {};
                // Lógica de pestañas
                const tabs = document.querySelectorAll('.tab-button');
                const tabContents = document.querySelectorAll('.tab-content');
                const filterButton = document.getElementById('filterButton');
                let activeTab = 'graficos';

                tabs.forEach(tab => {
                    tab.addEventListener('click', () => {
                        activeTab = tab.dataset.tab;
                        tabs.forEach(t => t.classList.toggle('active-tab', t === tab));
                        tabContents.forEach(c => c.classList.toggle('hidden', c.id !== `${activeTab}Section`));
                        updateDashboard();
                    });
                });

                // Lógica de filtros y carga
                filterButton.addEventListener('click', updateDashboard);
                updateDashboard(); // Carga inicial

                function updateDashboard() {
                    const startDate = document.getElementById('startDate').value;
                    const endDate = document.getElementById('endDate').value;
                    const dateQuery = (startDate && endDate) ? `?start_date=${startDate}&end_date=${endDate}` : '';

                    // Actualizar gráficos
                    updateChart('recursos', 'recursosChart', 'bar', { scales: { x: { stacked: true }, y: { stacked: true, beginAtZero: true } } }, dateQuery);
                    updateChart('eficacia', 'eficaciaChart', 'bar', { scales: { y: { beginAtZero: true } } }, dateQuery);
                    updateChart('solicitudes', 'solicitudesChart', 'doughnut', {}, dateQuery);
                    updateChart('experiencias', 'experienciasChart', 'bar', { scales: { x: { stacked: true }, y: { stacked: true } } }, dateQuery);
                    
                    // Actualizar tablas
                    if (activeTab === 'tablas') {
                        updateTables(dateQuery);
                    }
                }

                async function updateChart(type, chartId, chartType, options, query) {
                    const url = `/reportes/${type}${query}`;
                    const downloadLink = document.querySelector(`#${chartId}`).closest('.bg-white').querySelector('.download-button');
                    if (downloadLink) downloadLink.href = `/reportes/${type}/excel${query}`;
                    
                    const canvasContainer = document.getElementById(chartId).parentElement;
                    canvasContainer.innerHTML = `<canvas id="${chartId}"></canvas>`;
                    const ctx = document.getElementById(chartId).getContext('2d');

                    try {
                        const response = await fetch(url);
                        if (!response.ok) throw new Error('Network response was not ok');
                        const data = await response.json();
                        if (charts[chartId]) charts[chartId].destroy();
                        charts[chartId] = new Chart(ctx, { type: chartType, data: data, options: { responsive: true, maintainAspectRatio: false, ...options } });
                    } catch (error) {
                        console.error(`Error loading chart ${chartId}:`, error);
                        canvasContainer.innerHTML = `<div class="flex items-center justify-center h-full text-red-500 text-sm"><p>No se pudo cargar el gráfico.</p></div>`;
                    }
                }

                async function updateTables(dateQuery) {
                    const loadingIncidencias = document.getElementById('loadingIndicatorIncidencias');
                    const loadingUsuarios = document.getElementById('loadingIndicatorUsuarios');
                    loadingIncidencias.classList.remove('hidden');
                    loadingUsuarios.classList.remove('hidden');

                    try {
                        await Promise.all([ updateIncidencias(dateQuery), updateUsuarios(dateQuery) ]);
                    } catch (error) {
                        console.error('Error updating tables:', error);
                    } finally {
                        loadingIncidencias.classList.add('hidden');
                        loadingUsuarios.classList.add('hidden');
                    }
                }

                async function updateIncidencias(dateQuery) {
                    const url = `/reportes/incidencias${dateQuery}`;
                    document.querySelector('#tablasSection button[onclick*="incidencias"]').href = `/reportes/incidencias/pdf${dateQuery}`;
                    try {
                        const response = await fetch(url);
                        if (!response.ok) throw new Error(`Error HTTP: ${response.status}`);
                        const data = await response.json();
                        if (!data.success) throw new Error(data.message || 'Error en los datos recibidos');

                        updateIncidenciasResumen(data.data.resumen);
                        updateIncidenciasTabla(data.data.detalle);
                    } catch (error) {
                        console.error('Error al cargar datos de incidencias:', error);
                        document.getElementById("incidenciasTableBody").innerHTML = '<tr><td colspan="5" class="py-4 text-center text-red-500">Error al cargar los datos.</td></tr>';
                    }
                }

                async function updateUsuarios(dateQuery) {
                    const url = `/reportes/usuarios${dateQuery}`;
                    document.querySelector('#tablasSection button[onclick*="usuarios"]').href = `/reportes/usuarios/pdf${dateQuery}`;
                    try {
                        const response = await fetch(url);
                        if (!response.ok) throw new Error(`Error HTTP: ${response.status}`);
                        const data = await response.json();
                        if (!data.success) throw new Error(data.message || 'Error en los datos recibidos');

                        updateUsuariosResumen(data.data.resumen);
                        updateUsuariosTabla(data.data.detalle_usuarios);
                    } catch (error) {
                        console.error('Error al cargar datos de usuarios:', error);
                        document.getElementById("usuariosTableBody").innerHTML = '<tr><td colspan="4" class="py-4 text-center text-red-500">Error al cargar los datos.</td></tr>';
                    }
                }
                
                function updateIncidenciasResumen(resumen) {
                    document.getElementById("totalIncidencias").textContent = resumen.total_incidencias || 0;
                    document.getElementById("incidenciasResueltas").textContent = resumen.incidencias_resueltas || 0;
                    document.getElementById("porcentajeResueltas").textContent = `${Math.round(resumen.porcentaje_resueltas || 0)}%`;
                    document.getElementById("tiempoPromedioIncidencias").textContent = resumen.tiempo_promedio_resolucion ? `${Math.abs(resumen.tiempo_promedio_resolucion).toFixed(2)}h` : 'N/A';
                }

                function updateIncidenciasTabla(detalle) {
                    const tbody = document.getElementById("incidenciasTableBody");
                    if (!detalle || detalle.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="5" class="py-4 text-center text-gray-500">No hay datos en este período.</td></tr>';
                        return;
                    }
                    tbody.innerHTML = detalle.map(item => {
                        const tiempo = item.tiempo_promedio !== null ? `${Math.abs(item.tiempo_promedio).toFixed(2)} horas` : 'N/A';
                        return `
                            <tr>
                                <td class="py-2 px-4 border-b">${item.tipo_experiencia || 'N/A'}</td>
                                <td class="py-2 px-4 border-b">${item.total || 0}</td>
                                <td class="py-2 px-4 border-b">${tiempo}</td>
                                <td class="py-2 px-4 border-b"><span class="px-2 py-1 text-xs font-semibold rounded-full ${item.estado === 'Resuelto' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">${item.estado || 'Pendiente'}</span></td>
                                <td class="py-2 px-4 border-b">${item.coordinador_nombre || 'N/A'}</td>
                            </tr>`;
                    }).join('');
                }
                
                function updateUsuariosResumen(resumen) {
                    document.getElementById("totalActividades").textContent = resumen.total_actividades || 0;
                    document.getElementById("usuariosActivos").textContent = resumen.usuarios_activos || 0;
                    document.getElementById("tiempoPromedioUsuarios").textContent = resumen.tiempo_promedio_general ? `${resumen.tiempo_promedio_general.toFixed(2)}h` : 'N/A';
                }

                function updateUsuariosTabla(usuarios) {
                    const tbody = document.getElementById("usuariosTableBody");
                    if (!usuarios || usuarios.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="4" class="py-4 text-center text-gray-500">No se encontraron actividades.</td></tr>';
                        return;
                    }
                    tbody.innerHTML = usuarios.map(usuario => {
                        const tiempo = usuario.tiempo_promedio !== null ? `${usuario.tiempo_promedio.toFixed(2)} horas` : 'N/A';
                        return `
                            <tr>
                                <td class="py-2 px-4 border-b">${usuario.nombre_completo || 'N/A'}</td>
                                <td class="py-2 px-4 border-b">${usuario.rol || 'N/A'}</td>
                                <td class="py-2 px-4 border-b">${usuario.total_actividades || 0}</td>
                                <td class="py-2 px-4 border-b">${tiempo}</td>
                            </tr>`;
                    }).join('');
                }

                function capitalize(s) { return s.charAt(0).toUpperCase() + s.slice(1); }

                window.downloadExcelReport = function(type) {
                    const startDate = document.getElementById('startDate').value;
                    const endDate = document.getElementById('endDate').value;
                    const dateQuery = (startDate && endDate) ? `?start_date=${startDate}&end_date=${endDate}` : '';
                    window.location.href = `/reportes/${type}/excel${dateQuery}`;
                }

                window.downloadTableReport = function(type, format) {
                    const startDate = document.getElementById('startDate').value;
                    const endDate = document.getElementById('endDate').value;
                    const dateQuery = (startDate && endDate) ? `?start_date=${startDate}&end_date=${endDate}` : '';
                    window.location.href = `/reportes/${type}/${format}${dateQuery}`;
                }
            });
        </script>
    @endif
</x-app-layout>