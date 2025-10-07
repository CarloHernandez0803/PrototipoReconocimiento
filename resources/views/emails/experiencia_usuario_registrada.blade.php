<x-mail::message>
# Nueva Experiencia de Usuario Registrada

Se ha registrado una nueva experiencia de usuario con los siguientes detalles:

- **ID de Experiencia:** {{ $experiencia->id_experiencia }}
- **Tipo de Experiencia:** {{ $experiencia->tipo_experiencia }}
- **Impacto:** {{ $experiencia->impacto }}
- **Fecha de Registro:** {{ $experiencia->fecha_experiencia ? \Carbon\Carbon::parse($experiencia->fecha_experiencia)->format('d/m/Y H:i') : 'Fecha no disponible' }}

<x-mail::button :url="route('experiencias.show', $experiencia->id_experiencia)">
Ver Experiencia
</x-mail::button>

Gracias,<br>
El equipo de {{ config('app.name') }}
</x-mail::message>