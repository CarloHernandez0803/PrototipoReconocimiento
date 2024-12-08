<nav x-data="{ open: false }" class="bg-red-600 border-b border-gray-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('images/upemor.png') }}" alt="Logo" class="block h-9 w-auto" />  
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden sm:flex sm:items-center sm:ms-6 sm:ms-6">
                    <x-dropdown align="left" width="48">
                        <x-slot name="trigger">
                            <button class="flex text-white text-sm border-2 border-transparent rounded-full focus:outline-none transition">
                                {{ __('Gestiones') }}
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <!-- Usuarios -->
                            @if(Auth::user()->rol === 'Administrador')
                                <x-dropdown-link href="{{ route('usuarios.index') }}">
                                    {{ __('Usuarios') }}
                                </x-dropdown-link>
                            @endif

                            @if(Auth::user()->rol === 'Administrador' || Auth::user()->rol === 'Alumno')
                                <x-dropdown-link href="{{ route('evaluaciones.index') }}">
                                    {{ __('Evaluaciones') }}
                                </x-dropdown-link>
                            @endif

                            <x-dropdown-link href="{{ route('experiencias.index') }}">
                                {{ __('Experiencias') }}
                            </x-dropdown-link>

                            @if(Auth::user()->rol === 'Administrador' || Auth::user()->rol === 'Coordinador')
                                <x-dropdown-link href="{{ route('incidencias.index') }}">
                                    {{ __('Incidencias') }}
                                </x-dropdown-link>
                            @endif

                            <x-dropdown-link href="{{ route('preguntas.index') }}">
                                {{ __('Preguntas') }}
                            </x-dropdown-link>

                            @if(Auth::user()->rol === 'Administrador' || Auth::user()->rol === 'Coordinador')
                                <x-dropdown-link href="{{ route('solicitudes.index') }}">
                                    {{ __('Solicitudes') }}
                                </x-dropdown-link>
                            @endif
                        </x-slot>
                    </x-dropdown>
                </div>

                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="left" width="48">
                        <x-slot name="trigger">
                            <button class="flex text-white text-sm border-2 border-transparent rounded-full focus:outline-none transition">
                                {{ __('Señalamientos') }}
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <!-- Entrenamientos -->
                            <x-dropdown-link href="{{ route('senalamientos_entrenamientos.index') }}">
                                {{ __('Entrenamientos') }}
                            </x-dropdown-link>

                            <!-- Pruebas -->
                            <x-dropdown-link href="{{ route('senalamientos_pruebas.index') }}">
                                {{ __('Pruebas') }}
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                </div>

                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="left" width="48">
                        <x-slot name="trigger">
                            <button class="flex text-white text-sm border-2 border-transparent rounded-full focus:outline-none transition">
                                {{ __('Módulos - Red') }}
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <!-- Red neuronal - Entrenamiento -->
                            <x-dropdown-link>
                                {{ __('Entrenamiento') }}
                            </x-dropdown-link>

                            <!-- Red neuronal - Prueba -->
                            <x-dropdown-link>
                                {{ __('Prueba') }}
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                </div>

                @if(Auth::user()->rol === 'Administrador')
                    <div class="hidden space-x-8 sm:-my-px sm:ms-6 sm:flex">
                        <x-nav-link class="text-white">
                            {{ __('Seguimiento') }}
                        </x-nav-link>
                    </div>
                @endif

                <div class="hidden space-x-8 sm:-my-px sm:ms-6 sm:flex">
                    <x-nav-link class="text-white">
                        {{ __('Calendario') }}
                    </x-nav-link>
                </div>

                @if(Auth::user()->rol === 'Administrador')
                    <div class="hidden space-x-8 sm:-my-px sm:ms-6 sm:flex">
                        <x-nav-link class="text-white">
                            {{ __('Base de datos') }}
                        </x-nav-link>
                    </div>
                @endif
            </div>

            <!-- Configuración de usuario -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex text-white border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                            {{ Auth::user()->nombre . " " . Auth::user()->apellidos }}
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <form method="POST" action="{{ route('logout') }}" x-data>
                            @csrf
                            <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                {{ __('Cerrar sesión') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>
