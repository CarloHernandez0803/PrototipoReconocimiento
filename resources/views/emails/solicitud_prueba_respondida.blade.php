<x-mail::message>
# Respuesta a Solicitud de Prueba

La solicitud de prueba ha sido respondida con los siguientes detalles:

- **ID de Solicitud:** {{ $solicitud->id_solicitud }}
- **Estado:** {{ $solicitud->estado }}
- **Fecha de Respuesta:** {{ $solicitud->fecha_respuesta ? \Carbon\Carbon::parse($solicitud->fecha_respuesta)->format('d/m/Y H:i') : 'Sin respuesta' }}

<x-mail::button :url="url('/solicitudes')">
Ver Solicitud
</x-mail::button>

Gracias,<br>
El equipo de {{ config('app.name') }}
</x-mail::message>