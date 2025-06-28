<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="relative">
                <!-- Botones de navegación -->
                <button id="prevButton" class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-gray-200 p-2 rounded-full shadow-lg hover:bg-gray-300">
                    &#10094; 
                </button>
                <button id="nextButton" class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-gray-200 p-2 rounded-full shadow-lg hover:bg-gray-300">
                    &#10095; 
                </button>

                <!-- Sección de Gráficos -->
                <div id="graficosSection" class="space-y-6">
                    <div class="flex space-x-4 mb-6">
                        <input type="date" id="chartStartDate" class="border rounded p-2" placeholder="Fecha de inicio" />
                        <input type="date" id="chartEndDate" class="border rounded p-2" placeholder="Fecha de fin" />
                        <button onclick="updateCharts()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Filtrar Gráficos
                        </button>
                    </div>

                    <!-- Gráfico de Eficacia -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-bold">Eficacia del Modelo de Reconocimiento</h2>
                            <div class="flex space-x-2">
                                <button onclick="downloadExcelReport('eficacia')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Descargar Excel
                                </button>
                            </div>
                        </div>
                        <div style="height: 400px;">
                            <canvas id="eficaciaChart"></canvas>
                        </div>
                    </div>

                    <!-- Gráfico de Solicitudes -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-bold">Gestión de Solicitudes de Pruebas</h2>
                            <div class="flex space-x-2">
                                <a href="/reportes/solicitudes/excel" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Descargar Excel
                                </a>
                            </div>
                        </div>
                        <div style="height: 400px;">
                            <canvas id="solicitudesChart"></canvas>
                        </div>
                    </div>

                    <!-- Gráfico de Experiencias -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-bold">Análisis de Experiencias de Usuarios</h2>
                            <div class="flex space-x-2">
                                <a href="/reportes/experiencias/excel" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Descargar Excel
                                </a>
                            </div>
                        </div>
                        <div style="height: 400px;">
                            <canvas id="experienciasChart"></canvas>
                        </div>
                    </div>

                    <!-- Gráfico de Recursos -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-bold">Uso de Recursos de Entrenamiento</h2>
                            <div class="flex space-x-2">
                                <a href="/reportes/recursos/excel" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Descargar Excel
                                </a>
                            </div>
                        </div>
                        <div style="height: 400px;">
                            <canvas id="recursosChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Sección de Tablas (oculta inicialmente) -->
                <div id="tablasSection" class="space-y-6 hidden">
                    <!-- Filtros para tablas -->
                    <div class="flex space-x-4 mb-6">
                        <input type="date" id="tablasStartDate" name="start_date" class="border rounded p-2" placeholder="Fecha de inicio" />
                        <input type="date" id="tablasEndDate" name="end_date" class="border rounded p-2" placeholder="Fecha de fin" />
                        <button id="filterTablasButton" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Filtrar Tablas
                        </button>
                    </div>

                    <!-- Reporte de Incidencias -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-bold">Resumen de Incidencias</h2>
                            <div class="flex space-x-2">
                                <button onclick="downloadReport('incidencias', 'pdf')" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    Descargar PDF
                                </button>
                            </div>
                        </div>

                        <!-- Indicador de carga -->
                        <div id="loadingIndicator" class="hidden mb-4 p-3 bg-blue-50 rounded-lg text-sm">
                            <div class="flex items-center gap-2 text-blue-700">
                                <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Cargando datos...</span>
                            </div>
                        </div>

                        <!-- Contenedor de errores -->
                        <div id="errorContainer" class="hidden mb-4"></div>

                        <!-- Resumen de métricas -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">Total de Incidencias</p>
                                <p class="text-2xl font-bold" id="totalIncidencias">0</p>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">Incidencias Resueltas</p>
                                <p class="text-2xl font-bold" id="incidenciasResueltas">0</p>
                            </div>
                            <div class="bg-yellow-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">Porcentaje Resueltas</p>
                                <p class="text-2xl font-bold" id="porcentajeResueltas">0%</p>
                            </div>
                            <div class="bg-red-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">Tiempo Promedio</p>
                                <p class="text-2xl font-bold" id="tiempoPromedioIncidencias">N/A</p>
                            </div>
                        </div>

                        <!-- Tabla de incidencias -->
                        <h2 class="text-xl font-bold mb-4">Tendencias de Incidencias</h2>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="py-3 px-4 border-b text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                                        <th class="py-3 px-4 border-b text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                        <th class="py-3 px-4 border-b text-left text-xs font-medium text-gray-500 uppercase">Tiempo Promedio</th>
                                        <th class="py-3 px-4 border-b text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                        <th class="py-3 px-4 border-b text-left text-xs font-medium text-gray-500 uppercase">Reportado por</th>
                                    </tr>
                                </thead>
                                <tbody id="incidenciasTableBody" class="divide-y divide-gray-200">
                                    <tr>
                                        <td colspan="5" class="py-4 text-center text-gray-500">Seleccione un rango de fechas y haga clic en Filtrar</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Reporte de Usuarios -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-bold">Resumen de Actividad de Usuarios</h2>
                            <div class="flex space-x-2">
                                <button onclick="downloadReport('usuarios', 'pdf')" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    Descargar PDF
                                </button>
                            </div>
                        </div>
                        
                        <!-- Resumen de usuarios -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">Total de Actividades</p>
                                <p class="text-2xl font-bold" id="totalActividades">0</p>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">Usuarios Activos</p>
                                <p class="text-2xl font-bold" id="usuariosActivos">0</p>
                            </div>
                            <div class="bg-yellow-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">Tiempo Promedio</p>
                                <p class="text-2xl font-bold" id="tiempoPromedioUsuarios">N/A</p>
                            </div>
                        </div>

                        <!-- Tabla de usuarios -->
                        <h2 class="text-xl font-bold mb-4">Actividad de Usuarios</h2>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="py-3 px-4 border-b text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                                        <th class="py-3 px-4 border-b text-left text-xs font-medium text-gray-500 uppercase">Rol</th>
                                        <th class="py-3 px-4 border-b text-left text-xs font-medium text-gray-500 uppercase">Actividades</th>
                                        <th class="py-3 px-4 border-b text-left text-xs font-medium text-gray-500 uppercase">Tiempo Promedio</th>
                                    </tr>
                                </thead>
                                <tbody id="usuariosTableBody" class="divide-y divide-gray-200">
                                    <tr>
                                        <td colspan="4" class="py-4 text-center text-gray-500">Seleccione un rango de fechas y haga clic en Filtrar</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Objeto para almacenar las instancias de gráficos
        const charts = {};

        // Inicialización al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            // Configurar navegación entre secciones
            document.getElementById("nextButton").addEventListener("click", () => {
                document.getElementById("graficosSection").classList.add("hidden");
                document.getElementById("tablasSection").classList.remove("hidden");
                updateTables();
            });

            document.getElementById("prevButton").addEventListener("click", () => {
                document.getElementById("tablasSection").classList.add("hidden");
                document.getElementById("graficosSection").classList.remove("hidden");
            });

            // Configurar botón de filtro
            document.getElementById("filterTablasButton").addEventListener("click", function(e) {
                e.preventDefault();
                updateTables();
            });

            // Cargar datos iniciales
            updateTables();
            initializeCharts();
        });

        // Función para inicializar gráficos
        function initializeCharts() {
            fetchData("{{ route('reportes.eficacia') }}", 'eficaciaChart', 'bar', {
                scales: { y: { beginAtZero: true } }
            });

            fetchData("{{ route('reportes.solicitudes') }}", 'solicitudesChart', 'doughnut');

            fetchData("{{ route('reportes.experiencias') }}", 'experienciasChart', 'bar', {
                scales: { x: { stacked: true }, y: { stacked: true } }
            });

            fetchData("{{ route('reportes.recursos') }}", 'recursosChart', 'bar', {
                scales: { y: { beginAtZero: true } }
            });
        }

        // Función para cargar datos de gráficos
        async function fetchData(url, chartId, type, options = {}) {
            try {
                const response = await fetch(url);
                const data = await response.json();
                
                if (charts[chartId]) {
                    charts[chartId].destroy();
                }
                
                const ctx = document.getElementById(chartId).getContext('2d');
                charts[chartId] = new Chart(ctx, {
                    type: type,
                    data: data,
                    options: {
                        ...options,
                        responsive: true,
                        maintainAspectRatio: false,
                    }
                });
            } catch (error) {
                console.error(`Error loading chart ${chartId}:`, error);
            }
        }

        // Función para actualizar gráficos con filtros
        function updateCharts() {
            const startDate = document.getElementById('chartStartDate').value;
            const endDate = document.getElementById('chartEndDate').value;

            if (!startDate || !endDate) {
                showError('Por favor seleccione ambas fechas para filtrar gráficos');
                return;
            }

            fetchData(`/reportes/eficacia?start_date=${startDate}&end_date=${endDate}`, 'eficaciaChart', 'bar', {
                scales: { y: { beginAtZero: true } }
            });

            fetchData(`/reportes/solicitudes?start_date=${startDate}&end_date=${endDate}`, 'solicitudesChart', 'doughnut');

            fetchData(`/reportes/experiencias?start_date=${startDate}&end_date=${endDate}`, 'experienciasChart', 'bar', {
                scales: { x: { stacked: true }, y: { stacked: true } }
            });

            fetchData(`/reportes/recursos?start_date=${startDate}&end_date=${endDate}`, 'recursosChart', 'bar', {
                scales: { y: { beginAtZero: true } }
            });
        }

        // Función principal para actualizar tablas
        async function updateTables() {
            const startDate = document.getElementById('tablasStartDate').value;
            const endDate = document.getElementById('tablasEndDate').value;
            
            try {
                // Mostrar loader y ocultar error
                document.getElementById('loadingIndicator').classList.remove('hidden');
                document.getElementById('errorContainer').classList.add('hidden');
                
                // Actualizar ambas tablas en paralelo
                await Promise.all([
                    updateIncidencias(startDate, endDate),
                    updateUsuarios(startDate, endDate)
                ]);

            } catch (error) {
                console.error('Error:', error);
                showError(error.message || 'Error al cargar los datos. Por favor, intente nuevamente.');
            } finally {
                document.getElementById('loadingIndicator').classList.add('hidden');
            }
        }

        // Función para actualizar datos de incidencias
        async function updateIncidencias(startDate, endDate) {
            try {
                // Construir URL con parámetros opcionales
                let url = '/reportes/incidencias';
                if (startDate && endDate) {
                    url += `?start_date=${startDate}&end_date=${endDate}`;
                }

                // Obtener datos de incidencias
                const response = await fetch(url);
                
                if (!response.ok) {
                    throw new Error(`Error HTTP: ${response.status}`);
                }
                
                const data = await response.json();

                if (!data.success) {
                    throw new Error(data.message || 'Error en los datos recibidos');
                }

                // Verificar estructura de datos
                if (!data.data || !data.data.detalle) {
                    throw new Error('Formato de datos incorrecto');
                }

                // Actualizar resumen y tabla de incidencias
                updateIncidenciasResumen(data.data.resumen);
                updateIncidenciasTabla(data.data.detalle);

            } catch (error) {
                console.error('Error al cargar datos de incidencias:', error);
                throw error;
            }
        }

        // Función para actualizar datos de usuarios
        async function updateUsuarios(startDate, endDate) {
            try {
                let url = '/reportes/usuarios';
                if (startDate && endDate) {
                    url += `?start_date=${startDate}&end_date=${endDate}`;
                }

                const response = await fetch(url);
                
                if (!response.ok) {
                    throw new Error(`Error HTTP: ${response.status}`);
                }
                
                const data = await response.json();

                if (!data.success) {
                    throw new Error(data.message || 'Error en los datos recibidos');
                }

                // Verificar estructura de datos
                if (!data.data || !data.data.detalle_usuarios) {
                    throw new Error('Formato de datos incorrecto');
                }

                // Actualizar resumen
                document.getElementById("totalActividades").textContent = data.data.resumen.total_actividades || 0;
                document.getElementById("usuariosActivos").textContent = data.data.resumen.usuarios_activos || 0;
                
                const tiempoPromedio = data.data.resumen.tiempo_promedio_general ? 
                    `${data.data.resumen.tiempo_promedio_general}h` : 'N/A';
                document.getElementById("tiempoPromedioUsuarios").textContent = tiempoPromedio;

                // Actualizar tabla de usuarios
                updateUsuariosTabla(data.data.detalle_usuarios);

            } catch (error) {
                console.error('Error al cargar datos de usuarios:', error);
                throw error;
            }
        }

        // Función para actualizar el resumen de incidencias
        function updateIncidenciasResumen(resumen) {
            document.getElementById("totalIncidencias").textContent = resumen.total_incidencias || 0;
            document.getElementById("incidenciasResueltas").textContent = resumen.incidencias_resueltas || 0;
            
            const porcentaje = resumen.porcentaje_resueltas ? 
                Math.round(resumen.porcentaje_resueltas) : 0;
            document.getElementById("porcentajeResueltas").textContent = `${porcentaje}%`;
            
            const tiempoPromedio = resumen.tiempo_promedio_resolucion ? 
                `${Math.abs(resumen.tiempo_promedio_resolucion).toFixed(2)}h` : 'N/A';
            document.getElementById("tiempoPromedioIncidencias").textContent = tiempoPromedio;
        }

        // Función para actualizar la tabla de incidencias
        function updateIncidenciasTabla(incidencias) {
            const tbody = document.getElementById("incidenciasTableBody");
            
            if (!incidencias || incidencias.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="py-4 text-center text-gray-500">No se encontraron incidencias</td></tr>';
                return;
            }

            tbody.innerHTML = incidencias.map(item => {
                let tiempoResolucion = 'N/A';
                if (item.tiempo_promedio !== null && item.tiempo_promedio !== undefined) {
                    tiempoResolucion = `${Math.abs(item.tiempo_promedio).toFixed(2)} horas`;
                }
                
                return `
                    <tr>
                        <td class="py-3 px-4 border-b">${item.tipo_experiencia || 'Error de Sistema'}</td>
                        <td class="py-3 px-4 border-b">${item.total || 0}</td>
                        <td class="py-3 px-4 border-b">${tiempoResolucion}</td>
                        <td class="py-3 px-4 border-b">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full ${
                                item.estado === 'Resuelto' ? 
                                'bg-green-100 text-green-800' : 
                                'bg-yellow-100 text-yellow-800'
                            }">
                                ${item.estado || 'Pendiente'}
                            </span>
                        </td>
                        <td class="py-3 px-4 border-b">${item.coordinador_nombre || 'Usuario Desconocido'}</td>
                    </tr>
                `;
            }).join('');
        }

        // Función para actualizar la tabla de usuarios
        function updateUsuariosTabla(usuarios) {
            const tbody = document.getElementById("usuariosTableBody");
            
            if (!usuarios || usuarios.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="py-4 text-center text-gray-500">No se encontraron actividades</td></tr>';
                return;
            }

            tbody.innerHTML = usuarios.map(usuario => {
                const tiempo = usuario.tiempo_promedio !== null ? 
                    `${usuario.tiempo_promedio} horas` : 'N/A';
                
                return `
                    <tr>
                        <td class="py-3 px-4 border-b">${usuario.nombre_completo || 'Usuario Desconocido'}</td>
                        <td class="py-3 px-4 border-b">${usuario.rol || 'Sin rol'}</td>
                        <td class="py-3 px-4 border-b">${usuario.total_actividades || 0}</td>
                        <td class="py-3 px-4 border-b">${tiempo}</td>
                    </tr>
                `;
            }).join('');
        }

        // Función para mostrar errores
        function showError(message) {
            const errorContainer = document.getElementById('errorContainer');
            errorContainer.innerHTML = `
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">${message}</span>
                </div>
            `;
            errorContainer.classList.remove('hidden');
            
            // Ocultar después de 5 segundos
            setTimeout(() => {
                errorContainer.classList.add('hidden');
            }, 5000);
        }

        // Función para descargar reportes
        function downloadReport(type, format) {
            const startDate = document.getElementById('tablasStartDate').value;
            const endDate = document.getElementById('tablasEndDate').value;
            
            // Construir URL base
            let url = `/reportes/${type}/${format}`;
            
            // Agregar parámetros si existen
            const params = new URLSearchParams();
            if (startDate) params.append('start_date', startDate);
            if (endDate) params.append('end_date', endDate);
            
            if (params.toString()) {
                url += `?${params.toString()}`;
            }
            
            // Manejar visualmente la descarga
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = `
                <span class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Generando ${format.toUpperCase()}...
                </span>
            `;
            button.disabled = true;
            
            // Redirección para descarga
            window.location.href = url;
            
            // Restaurar botón después de 3 segundos
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            }, 3000);
        }

        function downloadExcelReport(type) {
            const startDate = document.getElementById('chartStartDate').value;
            const endDate = document.getElementById('chartEndDate').value;
            
            // Construir URL base
            let url = `/reportes/${type}/excel`;
            
            // Agregar parámetros si existen
            const params = new URLSearchParams();
            if (startDate) params.append('start_date', startDate);
            if (endDate) params.append('end_date', endDate);
            
            if (params.toString()) {
                url += `?${params.toString()}`;
            }
            
            // Manejar visualmente la descarga
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = `
                <span class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Generando Excel...
                </span>
            `;
            button.disabled = true;
            
            // Redirección para descarga
            window.location.href = url;
            
            // Restaurar botón después de 3 segundos
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            }, 3000);
        }
    </script>
</x-app-layout>