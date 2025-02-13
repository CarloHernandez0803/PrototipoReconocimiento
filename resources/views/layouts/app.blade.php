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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <!-- Contenido principal -->
        <div class="flex flex-col min-h-screen bg-gray-100">
            @include('navigation-menu')

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

        <script>
            document.addEventListener('livewire:load', function () {
                console.log('Alpine.js inicializado correctamente');
                Alpine.start();
            });
        </script>
    </body>
</html>