<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="relative">
                <button id="prevButton" class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-gray-200 p-2 rounded-full shadow-lg hover:bg-gray-300">
                    &#10094; 
                </button>

                <button id="nextButton" class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-gray-200 p-2 rounded-full shadow-lg hover:bg-gray-300">
                    &#10095; 
                </button>

                <div id="graficosSection" class="space-y-6">
                    <div class="flex space-x-4 mb-6">
                        <input
                            type="date"
                            id="chartStartDate"
                            class="border rounded p-2"
                            placeholder="Fecha de inicio"
                        />
                        <input
                            type="date"
                            id="chartEndDate"
                            class="border rounded p-2"
                            placeholder="Fecha de fin"
                        />
                        <button
                            onclick="updateCharts()"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                        >
                            Filtrar Gráficos
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <h2 class="text-xl font-bold mb-4">Eficacia del Modelo de Reconocimiento</h2>
                            <canvas id="eficaciaChart"></canvas>
                        </div>

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <h2 class="text-xl font-bold mb-4">Gestión de Solicitudes de Pruebas</h2>
                            <canvas id="solicitudesChart"></canvas>
                        </div>

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <h2 class="text-xl font-bold mb-4">Análisis de Experiencias de Usuarios</h2>
                            <canvas id="experienciasChart"></canvas>
                        </div>

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <h2 class="text-xl font-bold mb-4">Uso de Recursos de Entrenamiento</h2>
                            <canvas id="recursosChart"></canvas>
                        </div>
                    </div>
                </div>

                <div id="tablasSection" class="space-y-6 hidden">
                    <div class="flex space-x-4 mb-6">
                        <input
                            type="date"
                            id="tablasStartDate"
                            class="border rounded p-2"
                            placeholder="Fecha de inicio"
                        />
                        <input
                            type="date"
                            id="tablasEndDate"
                            class="border rounded p-2"
                            placeholder="Fecha de fin"
                        />
                        <button
                            id="filterTablasButton"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                        >
                            Filtrar Tablas
                        </button>
                    </div>
                    
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h2 class="text-xl font-bold mb-4">Tendencias de Incidencias</h2>
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b">Tipo</th>
                                    <th class="py-2 px-4 border-b">Total</th>
                                    <th class="py-2 px-4 border-b">Tiempo Promedio de Resolución</th>
                                    <th class="py-2 px-4 border-b">Estado</th>
                                    <th class="py-2 px-4 border-b">Reportado por</th>
                                </tr>
                            </thead>
                            <tbody id="incidenciasTableBody">
                                <!-- Datos dinámicos -->
                            </tbody>
                        </table>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h2 class="text-xl font-bold mb-4">Actividad de Usuarios</h2>
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b">Nombre</th>
                                    <th class="py-2 px-4 border-b">Rol</th>
                                    <th class="py-2 px-4 border-b">Actividades Realizadas</th>
                                    <th class="py-2 px-4 border-b">Tiempo Promedio de Aprobación</th>
                                </tr>
                            </thead>
                            <tbody id="usuariosTableBody">
                                <!-- Datos dinámicos -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const charts = {};

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
                    options: options
                });
            } catch (error) {
                console.error(`Error loading chart ${chartId}:`, error);
            }
        }

        async function updateTables() {
            const startDate = document.getElementById('tablasStartDate').value;
            const endDate = document.getElementById('tablasEndDate').value;
            
            try {
                const [incidenciasResponse, usuariosResponse] = await Promise.all([
                    fetch(`/reportes/incidencias?start_date=${startDate}&end_date=${endDate}`),
                    fetch(`/reportes/usuarios?start_date=${startDate}&end_date=${endDate}`)
                ]);

                const incidenciasData = await incidenciasResponse.json();
                const usuariosData = await usuariosResponse.json();

                const incidenciasTableBody = document.getElementById("incidenciasTableBody");
                if (incidenciasData && incidenciasData.incidencias) {
                    incidenciasTableBody.innerHTML = incidenciasData.incidencias.map(item => {
                        const tiempoPromedio = typeof item.tiempo_promedio === 'number' 
                            ? item.tiempo_promedio.toFixed(2) 
                            : 'N/A';
                        
                        return `
                            <tr>
                                <td class="py-2 px-4 border-b">${item.tipo_experiencia}</td>
                                <td class="py-2 px-4 border-b">${item.total}</td>
                                <td class="py-2 px-4 border-b">${tiempoPromedio} horas</td>
                                <td class="py-2 px-4 border-b">${item.estado_resolucion || 'PENDIENTE'}</td>
                                <td class="py-2 px-4 border-b">${item.reportado_por}</td>
                            </tr>
                        `;
                    }).join("");
                } else {
                    incidenciasTableBody.innerHTML = '<tr><td colspan="5" class="py-2 px-4 border-b">No hay datos disponibles</td></tr>';
                }

                const usuariosTableBody = document.getElementById("usuariosTableBody");
                if (usuariosData) {
                    usuariosTableBody.innerHTML = usuariosData.map(item => {
                        const tiempoPromedio = typeof item.tiempo_promedio === 'number' 
                            ? item.tiempo_promedio.toFixed(2) 
                            : 'N/A';
                        
                        return `
                            <tr>
                                <td class="py-2 px-4 border-b">${item.nombre}</td>
                                <td class="py-2 px-4 border-b">${item.rol}</td>
                                <td class="py-2 px-4 border-b">${item.actividades}</td>
                                <td class="py-2 px-4 border-b">${tiempoPromedio} horas</td>
                            </tr>
                        `;
                    }).join("");
                } else {
                    usuariosTableBody.innerHTML = '<tr><td colspan="4" class="py-2 px-4 border-b">No hay datos disponibles</td></tr>';
                }
            } catch (error) {
                console.error('Error updating tables:', error);
            }
        }

        document.getElementById("nextButton").addEventListener("click", () => {
            document.getElementById("graficosSection").classList.add("hidden");
            document.getElementById("tablasSection").classList.remove("hidden");
            updateTables();
        });

        document.getElementById("prevButton").addEventListener("click", () => {
            document.getElementById("tablasSection").classList.add("hidden");
            document.getElementById("graficosSection").classList.remove("hidden");
        });

        document.addEventListener('DOMContentLoaded', () => {
            fetchData("{{ route('reportes.eficacia') }}", 'eficaciaChart', 'bar', {
                scales: {
                    y: {
                        beginAtZero: true,
                    },
                },
            });

            fetchData("{{ route('reportes.solicitudes') }}", 'solicitudesChart', 'doughnut');

            fetchData("{{ route('reportes.experiencias') }}", 'experienciasChart', 'bar', {
                scales: {
                    x: {
                        stacked: true,
                    },
                    y: {
                        stacked: true,
                    },
                },
            });

            fetchData("{{ route('reportes.recursos') }}", 'recursosChart', 'bar', {
                scales: {
                    y: {
                        beginAtZero: true,
                    },
                },
            });
        });

        function updateCharts() {
            const startDate = document.getElementById('chartStartDate').value;
            const endDate = document.getElementById('chartEndDate').value;

            fetchData(`/reportes/eficacia?start_date=${startDate}&end_date=${endDate}`, 'eficaciaChart', 'bar', {
                scales: {
                    y: {
                        beginAtZero: true,
                    },
                },
            });

            fetchData(`/reportes/solicitudes?start_date=${startDate}&end_date=${endDate}`, 'solicitudesChart', 'doughnut');

            fetchData(`/reportes/experiencias?start_date=${startDate}&end_date=${endDate}`, 'experienciasChart', 'bar', {
                scales: {
                    x: {
                        stacked: true,
                    },
                    y: {
                        stacked: true,
                    },
                },
            });

            fetchData(`/reportes/recursos?start_date=${startDate}&end_date=${endDate}`, 'recursosChart', 'bar', {
                scales: {
                    y: {
                        beginAtZero: true,
                    },
                },
            });
        }

        document.getElementById("filterTablasButton").addEventListener("click", updateTables);
    </script>
</x-app-layout>