<nav x-data="{ open: false }" class="bg-red-600 border-b border-gray-100">
    <!-- Menú de Navegación Principal -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('images/upemor.png') }}" alt="Logo" class="block h-9 w-auto" />
                    </a>
                </div>

                <!-- Enlaces de Navegación para Escritorio (Tu código original sin cambios) -->
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="left" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center text-white text-sm font-medium hover:text-red-100 transition">
                                {{ __('Gestiones') }}
                                <svg class="ms-2 -me-0.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            @if(Auth::user()->rol === 'Administrador')<x-dropdown-link href="{{ route('usuarios.index') }}">{{ __('Usuarios') }}</x-dropdown-link>@endif
                            <x-dropdown-link href="{{ route('evaluaciones.index') }}">{{ __('Evaluaciones') }}</x-dropdown-link>
                            <x-dropdown-link href="{{ route('experiencias.index') }}">{{ __('Experiencias') }}</x-dropdown-link>
                            @if(Auth::user()->rol === 'Administrador' || Auth::user()->rol === 'Coordinador')<x-dropdown-link href="{{ route('incidencias.index') }}">{{ __('Incidencias') }}</x-dropdown-link>@endif
                            <x-dropdown-link href="{{ route('preguntas.index') }}">{{ __('Preguntas') }}</x-dropdown-link>
                            <x-dropdown-link href="{{ route('solicitudes.index') }}">{{ __('Solicitudes') }}</x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                </div>
                @if(Auth::user()->rol === 'Administrador' || Auth::user()->rol === 'Coordinador')
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                    <button class="flex items-center text-white text-sm font-medium hover:text-red-100 transition">
                                        {{ __('Señalamientos') }}
                                        <svg class="ms-2 -me-0.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                    </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link href="{{ route('senalamientos_entrenamientos.index') }}">{{ __('Entrenamientos') }}</x-dropdown-link>
                                <x-dropdown-link href="{{ route('senalamientos_pruebas.index') }}">{{ __('Pruebas') }}</x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endif

                @php
                    $canAccess = false;
                    $user = Auth::user();
                    $today = now()->format('Y-m-d');

                    if ($user->rol === 'Coordinador') {
                        $canAccess = \App\Models\Solicitud::where('estado', 'Aprobada')
                            ->where('coordinador', $user->id_usuario)
                            ->whereDate('fecha_solicitud', $today)
                            ->exists();
                    } elseif ($user->rol === 'Alumno') {
                        $canAccess = $user->solicitudes()
                            ->where('estado', 'Aprobada')
                            ->whereDate('fecha_solicitud', $today)
                            ->exists();
                    }
                @endphp

                @if(Auth::user()->rol === 'Administrador' || Auth::user()->rol === 'Coordinador' || $canAccess)
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center text-white text-sm font-medium hover:text-red-100 transition">
                                    {{ __('Módulos - Red') }}
                                    <svg class="ms-2 -me-0.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                @if(Auth::user()->rol === 'Administrador' || Auth::user()->rol === 'Coordinador')
                                    <x-dropdown-link href="{{ route('hyperparameters.index') }}">{{ __('Entrenamiento') }}</x-dropdown-link>
                                @endif
                                @if(Auth::user()->rol === 'Administrador' || $canAccess)
                                    <x-dropdown-link href="{{ route('modulo_prueba.index') }}">{{ __('Prueba') }}</x-dropdown-link>
                                @endif
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endif
                @if(Auth::user()->rol === 'Administrador' || Auth::user()->rol === 'Coordinador')
                    <div class="hidden space-x-8 sm:-my-px sm:ms-6 sm:flex">
                        <x-nav-link class="text-white" href="{{ route('incidencias.timeline') }}">{{ __('Seguimiento') }}</x-nav-link>
                    </div>
                @endif

                @if(Auth::user()->rol === 'Administrador' || Auth::user()->rol === 'Coordinador')
                    <div class="hidden space-x-8 sm:-my-px sm:ms-6 sm:flex">
                        <x-nav-link class="text-white" href="{{ route('calendario.index') }}">{{ __('Calendario') }}</x-nav-link>
                    </div>
                @endif

                @if(Auth::user()->rol === 'Administrador')
                    <div class="hidden space-x-8 sm:-my-px sm:ms-6 sm:flex">
                        <x-nav-link class="text-white" href="{{ route('base_datos.index') }}">{{ __('Base de datos') }}</x-nav-link>
                    </div>
                @endif
            </div>

            <!-- Configuración de usuario (Derecha) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center text-white font-medium hover:text-red-100 transition">
                            {{ Auth::user()->nombre . " " . Auth::user()->apellidos }}
                            <svg class="ms-2 -me-0.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
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

            <!-- Botón Hamburguesa (visible en pantallas pequeñas) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-red-200 hover:text-white hover:bg-red-700 focus:outline-none focus:bg-red-700 focus:text-white transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Menú Hamburguesa / Responsive -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <!-- Categoría (encabezado) -->
            <div class="text-white font-bold uppercase text-sm tracking-wide py-2">Gestiones</div>
            @if(Auth::user()->rol === 'Administrador')
                <x-responsive-nav-link class="text-white text-sm opacity-90" href="{{ route('usuarios.index') }}">{{ __('Usuarios') }}</x-responsive-nav-link>
            @endif
            <x-responsive-nav-link class="text-white text-sm opacity-90" href="{{ route('evaluaciones.index') }}">{{ __('Evaluaciones') }}</x-responsive-nav-link>
            <x-responsive-nav-link class="text-white text-sm opacity-90" href="{{ route('experiencias.index') }}">{{ __('Experiencias') }}</x-responsive-nav-link>
            @if(Auth::user()->rol === 'Administrador' || Auth::user()->rol === 'Coordinador')
                <x-responsive-nav-link class="text-white text-sm opacity-90" href="{{ route('incidencias.index') }}">{{ __('Incidencias') }}</x-responsive-nav-link>
            @endif
            <x-responsive-nav-link class="text-white text-sm opacity-90" href="{{ route('preguntas.index') }}">{{ __('Preguntas') }}</x-responsive-nav-link>
            <x-responsive-nav-link class="text-white text-sm opacity-90" href="{{ route('solicitudes.index') }}">{{ __('Solicitudes') }}</x-responsive-nav-link>

            @if(Auth::user()->rol === 'Administrador' || Auth::user()->rol === 'Coordinador')
                <div class="text-white font-bold uppercase text-sm tracking-wide py-2">Señalamientos</div>
                <x-responsive-nav-link class="text-white text-sm opacity-90" href="{{ route('senalamientos_entrenamientos.index') }}">{{ __('Entrenamientos') }}</x-responsive-nav-link>
                <x-responsive-nav-link class="text-white text-sm opacity-90" href="{{ route('senalamientos_pruebas.index') }}">{{ __('Pruebas') }}</x-responsive-nav-link>
            @endif

            @php
                $canAccess = false;
                $user = Auth::user();
                $today = now()->format('Y-m-d');

                if ($user->rol === 'Coordinador') {
                    $canAccess = \App\Models\Solicitud::where('estado', 'Aprobada')
                        ->where('coordinador', $user->id_usuario)
                        ->whereDate('fecha_solicitud', $today)
                        ->exists();
                } elseif ($user->rol === 'Alumno') {
                    $canAccess = $user->solicitudes()
                        ->where('estado', 'Aprobada')
                        ->whereDate('fecha_solicitud', $today)
                        ->exists();
                }
            @endphp
            
            @if(Auth::user()->rol === 'Administrador' || Auth::user()->rol === 'Coordinador' || $canAccess)
                <div class="text-white font-bold uppercase text-sm tracking-wide py-2">Módulos - Red</div>
                    @if(Auth::user()->rol === 'Administrador' || Auth::user()->rol === 'Coordinador')
                        <x-responsive-nav-link class="text-white text-sm opacity-90" href="{{ route('hyperparameters.index') }}">{{ __('Entrenamiento') }}</x-responsive-nav-link>
                    @endif

                    @if(Auth::user()->rol === 'Administrador' || $canAccess)
                        <x-responsive-nav-link class="text-white text-sm opacity-90" href="{{ route('modulo_prueba.index') }}">{{ __('Prueba') }}</x-responsive-nav-link>
                    @endif
            @endif

            @if(Auth::user()->rol === 'Administrador' || Auth::user()->rol === 'Coordinador')
                <div class="border-t border-red-500 pt-2 mt-2">
                    <div class="text-white font-bold uppercase text-sm tracking-wide py-2">Seguimiento</div>
                    <x-responsive-nav-link class="text-white text-sm opacity-90" href="{{ route('incidencias.timeline') }}">{{ __('Ver Seguimiento') }}</x-responsive-nav-link>
                </div>
            @endif

            @if(Auth::user()->rol === 'Administrador' || Auth::user()->rol === 'Coordinador')
                <div class="border-t border-red-500 pt-2 mt-2">
                    <div class="text-white font-bold uppercase text-sm tracking-wide py-2">Calendario</div>
                    <x-responsive-nav-link class="text-white text-sm opacity-90" href="{{ route('calendario.index') }}">{{ __('Ver Calendario') }}</x-responsive-nav-link>
                </div>
            @endif

            @if(Auth::user()->rol === 'Administrador')
                <div class="border-t border-red-500 pt-2 mt-2">
                    <div class="text-white font-bold uppercase text-sm tracking-wide py-2">Base de datos</div>
                    <x-responsive-nav-link class="text-white text-sm opacity-90" href="{{ route('base_datos.index') }}">{{ __('Administrar BD') }}</x-responsive-nav-link>
                </div>
            @endif

            <!-- Opciones de usuario (mobile) -->
            <div class="pt-4 pb-1 border-t border-red-500">
                <!-- Encabezado "Cuenta" arriba del bloque de usuario -->
                <div class="text-white font-bold uppercase text-sm tracking-wide py-2">Cuenta</div>

                <div class="flex items-center px-4">
                    <div>
                        <div class="font-medium text-base text-white">
                            {{ Auth::user()->nombre . " " . Auth::user()->apellidos }}
                        </div>
                        <div class="font-medium text-sm text-red-200">
                            {{ Auth::user()->correo }}
                        </div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf
                        <x-responsive-nav-link class="text-white text-sm opacity-90" href="{{ route('logout') }}" @click.prevent="$root.submit();">
                            {{ __('Cerrar Sesión') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>

        </div>
    </div>
</nav>