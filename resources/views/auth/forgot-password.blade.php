<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <img src="{{ asset('images/upemor.png') }}" alt="Logo" style="width: 120px; height: auto; display: block; margin: 0 auto;" />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('¿Olvidaste tu contraseña? No hay problema. Solo dinos tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.') }}
        </div>

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endsession

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="block">
                <x-label for="correo" value="{{ __('Correo electrónico') }}" />
                <x-input id="correo" class="block mt-1 w-full" type="email" name="correo" :value="old('correo')" required autofocus />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button class="bg-purple-900">
                    {{ __('Enviar enlace de restablecimiento') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>