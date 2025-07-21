<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <!-- Contenido principal -->
        <div class="flex flex-col min-h-screen bg-gray-100">
            @include('navigation-menu')

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mx-auto mt-4 max-w-7xl" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                        <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <title>Cerrar</title>
                            <path d="M14.348 5.652a.5.5 0 0 1 0 .707L10.707 10l3.641 3.641a.5.5 0 1 1-.707.707L10 10.707l-3.641 3.641a.5.5 0 1 1-.707-.707L9.293 10 5.652 6.359a.5.5 0 1 1 .707-.707L10 9.293l3.641-3.641a.5.5 0 0 1 .707 0z"/>
                        </svg>
                    </span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mx-auto mt-4 max-w-7xl" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                        <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <title>Cerrar</title>
                            <path d="M14.348 5.652a.5.5 0 0 1 0 .707L10.707 10l3.641 3.641a.5.5 0 1 1-.707.707L10 10.707l-3.641 3.641a.5.5 0 1 1-.707-.707L9.293 10 5.652 6.359a.5.5 0 1 1 .707-.707L10 9.293l3.641-3.641a.5.5 0 0 1 .707 0z"/>
                        </svg>
                    </span>
                </div>
            @endif

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="flex-1">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="bg-purple-900 text-white py-8 h-24">
                <div class="max-w-7xl mx-auto text-center">
                    <p>&copy; {{ date('Y') }}. Carlo Hernandez Fernandez. Javier Salazar Campos. Derechos reservados. ©</p>
                </div>
            </footer>
        </div>

        @stack('modals')
        @livewireScripts

        <!-- Alpine.js debe cargarse solo una vez, después de Livewire -->
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js" defer></script>

        <script>
            // Eliminar el listener de livewire:load ya que Alpine se carga con defer
            document.addEventListener('DOMContentLoaded', function () {
                // Eliminar notificaciones después de 5 segundos
                setTimeout(function () {
                    const notifications = document.querySelectorAll('[role="alert"]');
                    notifications.forEach(notification => {
                        notification.remove();
                    });
                }, 5000);
            });
        </script>
    </body>
</html>