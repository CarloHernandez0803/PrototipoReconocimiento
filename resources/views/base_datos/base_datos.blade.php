<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">
            {{ __('Respaldo y Restauración de la Base de Datos') }}
        </h2>
    </x-slot>

    <div class="max-w-6xl mx-auto py-16 px-6">
        <div class="bg-white rounded-lg shadow-lg p-10">
            <h3 class="text-xl font-semibold text-center">Opciones de Base de Datos</h3>

            <!-- Separación aumentada entre el título y los botones -->
            <div class="mt-20 flex justify-center gap-60">
                <!-- Botón de Respaldo -->
                <button 
                    onclick="backupDatabase()"
                    class="w-48 h-48 bg-blue-600 hover:bg-blue-700 text-white font-bold text-lg rounded-xl shadow-lg transition flex items-center justify-center"
                >
                    Generar <br> Respaldo
                </button>

                <!-- Botón de Restauración -->
                <button 
                    onclick="restoreDatabase()"
                    class="w-48 h-48 bg-green-600 hover:bg-green-700 text-white font-bold text-lg rounded-xl shadow-lg transition flex items-center justify-center"
                >
                    Restaurar <br> Base de Datos
                </button>
            </div>
        </div>
    </div>

    <!-- SweetAlert para Notificaciones -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function backupDatabase() {
            fetch("{{ route('base_datos.backup') }}")
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Error al generar el respaldo.");
                    }
                    return response.blob(); // Convertir la respuesta en un Blob
                })
                .then(blob => {
                    // Crear un enlace temporal para descargar el archivo
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement("a");
                    a.href = url;
                    a.download = "backup_" + new Date().toISOString().replace(/[:.-]/g, "_") + ".sql"; // Nombre del archivo
                    document.body.appendChild(a);
                    a.click(); // Simular clic en el enlace
                    document.body.removeChild(a); // Eliminar el enlace temporal
                    window.URL.revokeObjectURL(url); // Liberar memoria

                    // Mostrar notificación de éxito
                    Swal.fire("¡Éxito!", "Respaldo generado y descargado correctamente.", "success");
                })
                .catch(error => {
                    console.error("Error:", error);
                    Swal.fire("Error", "No se pudo generar el respaldo", "error");
                });
        }

        function restoreDatabase() {
            let input = document.createElement("input");
            input.type = "file";
            input.accept = ".sql";
            input.onchange = function (event) {
                let file = event.target.files[0];
                let formData = new FormData();
                formData.append("backup_file", file);

                fetch("{{ route('base_datos.restore') }}", {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    },
                })
                .then(response => response.json())
                .then(data => {
                    Swal.fire("¡Éxito!", data.message, "success");
                })
                .catch(error => {
                    Swal.fire("Error", "No se pudo restaurar la base de datos", "error");
                });
            };
            input.click();
        }
    </script>
</x-app-layout>