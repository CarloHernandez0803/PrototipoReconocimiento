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
                        <input type="date" id="chartStartDate" class="border rounded p-2" placeholder="Fecha de inicio" />
                        <input type="date" id="chartEndDate" class="border rounded p-2" placeholder="Fecha de fin" />
                        <button onclick="updateCharts()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Filtrar Gráficos
                        </button>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-bold">Eficacia del Modelo de Reconocimiento</h2>
                            <div class="flex space-x-2">
                                <a href="/reportes/eficacia/excel" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Descargar archivo Excel
                                </a>
                            </div>
                        </div>
                        <div style="height: 400px;">
                            <canvas id="eficaciaChart"></canvas>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-bold">Gestión de Solicitudes de Pruebas</h2>
                            <div class="flex space-x-2">
                                <a href="/reportes/solicitudes/excel" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Descargar archivo Excel
                                </a>
                            </div>
                        </div>
                        <div style="height: 400px;">
                            <canvas id="solicitudesChart"></canvas>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-bold">Análisis de Experiencias de Usuarios</h2>
                            <div class="flex space-x-2">
                                <a href="/reportes/experiencias/excel" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Descargar archivo Excel
                                </a>
                            </div>
                        </div>
                        <div style="height: 400px;">
                            <canvas id="experienciasChart"></canvas>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-bold">Uso de Recursos de Entrenamiento</h2>
                            <div class="flex space-x-2">
                                <a href="/reportes/recursos/excel" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Descargar archivo Excel
                                </a>
                            </div>
                        </div>
                        <div style="height: 400px;">
                            <canvas id="recursosChart"></canvas>
                        </div>
                    </div>
                </div>

                <div id="tablasSection" class="space-y-6 hidden">
                    <div class="flex space-x-4 mb-6">
                        <input type="date" id="tablasStartDate" class="border rounded p-2" placeholder="Fecha de inicio" />
                        <input type="date" id="tablasEndDate" class="border rounded p-2" placeholder="Fecha de fin" />
                        <button id="filterTablasButton" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Filtrar Tablas
                        </button>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-bold">Resumen de Incidencias</h2>
                            <div class="flex space-x-2">
                                <a href="/reportes/incidencias/pdf" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    Descargar archivo PDF
                                </a>
                            </div>
                        </div>
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
                                <p class="text-sm text-gray-600">Tiempo Promedio de Resolución</p>
                                <p class="text-2xl font-bold" id="tiempoPromedioIncidencias">0h</p>
                            </div>
                        </div>

                        <h2 class="text-xl font-bold mb-4">Tendencias de Incidencias</h2>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="py-3 px-4 border-b text-left">Tipo</th>
                                        <th class="py-3 px-4 border-b text-left">Total</th>
                                        <th class="py-3 px-4 border-b text-left">Tiempo Promedio</th>
                                        <th class="py-3 px-4 border-b text-left">Estado</th>
                                        <th class="py-3 px-4 border-b text-left">Reportado por</th>
                                    </tr>
                                </thead>
                                <tbody id="incidenciasTableBody" class="divide-y divide-gray-200">
                                    <!-- Datos dinámicos -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-bold">Resumen de Actividad de Usuarios</h2>
                            <div class="flex space-x-2">
                                <a href="/reportes/usuarios/pdf" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    Descargar archivo PDF
                                </a>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">Total de Actividades</p>
                                <p class="text-2xl font-bold" id="totalActividades">0</p>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">Tiempo Promedio de Aprobación de Solicitudes</p>
                                <p class="text-2xl font-bold" id="tiempoPromedioUsuarios">0h</p>
                            </div>
                        </div>

                        <h2 class="text-xl font-bold mb-4">Actividad de Usuarios</h2>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="py-3 px-4 border-b text-left">Nombre</th>
                                        <th class="py-3 px-4 border-b text-left">Rol</th>
                                        <th class="py-3 px-4 border-b text-left">Actividades</th>
                                        <th class="py-3 px-4 border-b text-left">Tiempo Promedio</th>
                                    </tr>
                                </thead>
                                <tbody id="usuariosTableBody" class="divide-y divide-gray-200">
                                    <!-- Datos dinámicos -->
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

                const totalIncidencias = document.getElementById("totalIncidencias");
                const incidenciasResueltas = document.getElementById("incidenciasResueltas");
                const porcentajeResueltas = document.getElementById("porcentajeResueltas");
                const tiempoPromedioIncidencias = document.getElementById("tiempoPromedioIncidencias");

                if (incidenciasData.resumen) {
                    totalIncidencias.textContent = incidenciasData.resumen.total_incidencias || 0;
                    incidenciasResueltas.textContent = incidenciasData.resumen.incidencias_resueltas || 0;
                    porcentajeResueltas.textContent = `${incidenciasData.resumen.porcentaje_resueltas || 0}%`;
                    tiempoPromedioIncidencias.textContent = `${incidenciasData.resumen.tiempo_promedio_resolucion || 0}h`;
                }

                const incidenciasTableBody = document.getElementById("incidenciasTableBody");
                if (incidenciasTableBody && incidenciasData.detalle_incidencias) {
                    incidenciasTableBody.innerHTML = incidenciasData.detalle_incidencias.map(item => {
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
                } else if (incidenciasTableBody) {
                    incidenciasTableBody.innerHTML = '<tr><td colspan="5" class="py-2 px-4 border-b">No hay datos disponibles</td></tr>';
                }

                const totalActividades = document.getElementById("totalActividades");
                const tiempoPromedioUsuarios = document.getElementById("tiempoPromedioUsuarios");

                if (usuariosData.resumen) {
                    totalActividades.textContent = usuariosData.resumen.total_actividades || 0;
                    tiempoPromedioUsuarios.textContent = `${usuariosData.resumen.tiempo_promedio_aprobacion || 0}h`;
                }

                const usuariosTableBody = document.getElementById("usuariosTableBody");
                if (usuariosTableBody && usuariosData.detalle_usuarios) {
                    usuariosTableBody.innerHTML = usuariosData.detalle_usuarios.map(item => {
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
                } else if (usuariosTableBody) {
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

            fetchData("{{ route('reportes.solicitudes') }}", 'solicitudesChart', 'doughnut', {
                // Opciones específicas para la gráfica de solicitudes
            });

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

            fetchData(`/reportes/solicitudes?start_date=${startDate}&end_date=${endDate}`, 'solicitudesChart', 'doughnut', {
                // Opciones específicas para la gráfica de solicitudes
            });

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