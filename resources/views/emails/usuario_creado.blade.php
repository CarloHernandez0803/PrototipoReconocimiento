<x-mail::message>
# ¡Hola, {{ $usuario->nombre }}!

Bienvenido a {{ config('app.name') }}. Tu cuenta ha sido creada exitosamente.

Aquí tienes algunos detalles de tu cuenta:
- **Nombre:** {{ $usuario->nombre }} . {{ $usuario->apellidos }}
- **Correo electrónico:** {{ $usuario->correo }}
- **Rol:** {{ $usuario->rol }}

<x-mail::button :url="url('/login')">
Iniciar Sesión
</x-mail::button>

Si tienes alguna pregunta, no dudes en contactarnos.

Gracias,<br>
El equipo de {{ config('app.name') }}
</x-mail::message>