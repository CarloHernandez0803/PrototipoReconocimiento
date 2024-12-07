<<<<<<< HEAD
<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
=======
<nav x-data="{ open: false }" class="bg-red-600 border-b border-gray-10">
>>>>>>> 202c96f (Quinta version proyecto)
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
<<<<<<< HEAD
                        <img src="{{ asset('images/upemor.png') }}" alt="Logo" class="block h-9 w-auto" />
=======
                        <img src="{{ asset('images/upemor.png') }}" alt="Logo" class="block h-9 w-auto" />  
>>>>>>> 202c96f (Quinta version proyecto)
                    </a>
                </div>

                <!-- Navigation Links -->
<<<<<<< HEAD
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" class="text-black">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link href="{{ route('usuarios.index') }}" :active="request()->routeIs('usuarios.index')" class="text-black">
                        {{ __('Usuarios') }}
                    </x-nav-link>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link href="{{ route('entrenamientos.index') }}" :active="request()->routeIs('entrenamientos.index')" class="text-black">
                        {{ __('Entrenamiento') }}
                    </x-nav-link>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link href="{{ route('pruebas.index') }}" :active="request()->routeIs('pruebas.index')" class="text-black">
                        {{ __('Prueba') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Teams Dropdown -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="ms-3 relative">
                        <x-dropdown align="right" width="60">
                            <x-slot name="trigger">
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                        {{ Auth::user()->currentTeam->name }}

                                        <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    </button>
                                </span>
                            </x-slot>

                            <x-slot name="content">
                                <div class="w-60">
                                    <!-- Team Management -->
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        {{ __('Manage Team') }}
                                    </div>

                                    <!-- Team Settings -->
                                    <x-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                                        {{ __('Team Settings') }}
                                    </x-dropdown-link>

                                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                        <x-dropdown-link href="{{ route('teams.create') }}">
                                            {{ __('Create New Team') }}
                                        </x-dropdown-link>
                                    @endcan

                                    <!-- Team Switcher -->
                                    @if (Auth::user()->allTeams()->count() > 1)
                                        <div class="border-t border-gray-200"></div>

                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            {{ __('Switch Teams') }}
                                        </div>

                                        @foreach (Auth::user()->allTeams() as $team)
                                            <x-switchable-team :team="$team" />
                                        @endforeach
                                    @endif
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endif

                <!-- Settings Dropdown -->
                <div class="ms-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                    <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                        {{ Auth::user()->name }}

                                        <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manage Account') }}
                            </div>

                            <x-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                    {{ __('API Tokens') }}
                                </x-dropdown-link>
                            @endif

                            <div class="border-t border-gray-200"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf

                                <x-dropdown-link href="{{ route('logout') }}"
                                         @click.prevent="$root.submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" class="text-white">
                {{ __('Dashboard') }}
                @if (request()->routeIs('dashboard'))
                    <span class="block w-full h-1 bg-red-500"></span>
                @endif
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="flex items-center px-4">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 me-3">
                        <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                    </div>
                @endif

                <div>
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Account Management -->
                <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                        {{ __('API Tokens') }}
                    </x-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf

                    <x-responsive-nav-link href="{{ route('logout') }}"
                                   @click.prevent="$root.submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>

                <!-- Team Management -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="border-t border-gray-200"></div>

                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Manage Team') }}
                    </div>

                    <!-- Team Settings -->
                    <x-responsive-nav-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}" :active="request()->routeIs('teams.show')">
                        {{ __('Team Settings') }}
                    </x-responsive-nav-link>

                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                        <x-responsive-nav-link href="{{ route('teams.create') }}" :active="request()->routeIs('teams.create')">
                            {{ __('Create New Team') }}
                        </x-responsive-nav-link>
                    @endcan

                    <!-- Team Switcher -->
                    @if (Auth::user()->allTeams()->count() > 1)
                        <div class="border-t border-gray-200"></div>

                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('Switch Teams') }}
                        </div>

                        @foreach (Auth::user()->allTeams() as $team)
                            <x-switchable-team :team="$team" component="responsive-nav-link" />
                        @endforeach
                    @endif
                @endif
            </div>
        </div>
    </div>
</nav>
=======
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
>>>>>>> 202c96f (Quinta version proyecto)
