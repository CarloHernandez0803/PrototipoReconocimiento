<x-mail::message>
# Nuevo Reporte de Fallo Registrado

Se ha registrado un nuevo reporte de fallo con los siguientes detalles:

- **ID de Incidencia:** {{ $incidencia->id_incidencia }}
- **Tipo de Fallo:** {{ $incidencia->tipo_experiencia }}
- **Fecha de Reporte:** {{ $incidencia->fecha_reporte }}

<x-mail::button :url="url('/incidencias')">
Ver Incidencia
</x-mail::button>

Gracias,<br>
El equipo de {{ config('app.name') }}
</x-mail::message>