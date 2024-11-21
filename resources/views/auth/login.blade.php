<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <img src="{{ asset('images/upemor.png') }}" alt="Logo" style="width: 120px; height: auto; display: block; margin: 0 auto;" />
        </x-slot>

        <x-validation-errors class="mb-4" />

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endsession

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-label for="correo" value="{{ __('Correo electrónico') }}" />
                <x-input id="correo" class="block mt-1 w-full" type="email" name="correo" :value="old('correo')" required autofocus autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Contraseña') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button class="ms-4">
                    {{ __('Iniciar sesión') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>