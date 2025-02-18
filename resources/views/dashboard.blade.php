<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div class="flex space-x-4 mb-6">
            <input
                type="date"
                id="startDate"
                class="border rounded p-2"
                placeholder="Fecha de inicio"
            />
            <input
                type="date"
                id="endDate"
                class="border rounded p-2"
                placeholder="Fecha de fin"
            />
            <button
                onclick="updateCharts()"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
            >
                Filtrar
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const charts = {};

        async function fetchData(url, chartId, type, options) {
            const response = await fetch(url);
            const data = await response.json();
            const ctx = document.getElementById(chartId).getContext('2d');

            if (charts[chartId]) {
                charts[chartId].destroy();
            }

            charts[chartId] = new Chart(ctx, {
                type: type,
                data: data,
                options: options,
            });
        }

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
                    beginAtZero: true,
                },
            },
            plugins: {
                tooltip: {
                    mode: 'index',
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

        function updateCharts() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;

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
                plugins: {
                    tooltip: {
                        mode: 'index',
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
    </script>
</x-app-layout>