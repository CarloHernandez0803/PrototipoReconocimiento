<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <img src="{{ asset('images/upemor.png') }}" alt="Logo" style="width: 120px; height: auto; display: block; margin: 0 auto;" />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="block">
                <x-label for="correo" value="{{ __('Correo electrónico') }}" />
                <x-input id="correo" class="block mt-1 w-full" type="email" name="correo" :value="old('correo', $request->correo)" required autofocus autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="contraseña" value="{{ __('Contraseña') }}" />
                <x-input id="contraseña" class="block mt-1 w-full" type="password" name="contraseña" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-label for="contraseña_confirmation" value="{{ __('Confirmar Contraseña') }}" />
                <x-input id="contraseña_confirmation" class="block mt-1 w-full" type="password" name="contraseña_confirmation" required autocomplete="new-password" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button class="bg-purple-900">
                    {{ __('Restablecer Contraseña') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>