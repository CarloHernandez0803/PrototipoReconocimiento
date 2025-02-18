<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Calendario de Eventos de Incidencias y Pruebas') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-semibold text-center mb-6">Calendario de Eventos</h3>

            <div id="calendario" class="w-full" style="height: 800px;"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/locales/es.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendario');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                events: "{{ route('calendario.eventos') }}",
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: ''
                },
                eventClick: function(info) {
                    alert(
                        `Tipo: ${info.event.extendedProps.tipo}\n` +
                        `Estado: ${info.event.extendedProps.estado}\n` +
                        `Descripción: ${info.event.extendedProps.descripcion || 'N/A'}`
                    );
                }
            });
            calendar.render();
        });
    </script>
</x-app-layout>