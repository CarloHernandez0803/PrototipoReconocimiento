<x-mail::message>
# Actualización en el Seguimiento del Fallo Reportado

El estado del fallo reportado ha sido actualizado con los siguientes detalles:

- **ID de Resolución:** {{ $resolucion->id_resolucion }}
- **Estado Actual:** {{ $resolucion->estado }}
- **Fecha de Resolución:** {{ $resolucion->fecha_resolucion }}

<x-mail::button :url="url('/incidencias')">
Ver Seguimiento
</x-mail::button>

Gracias,<br>
El equipo de {{ config('app.name') }}
</x-mail::message>