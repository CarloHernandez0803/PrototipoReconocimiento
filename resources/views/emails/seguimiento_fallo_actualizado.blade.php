<x-mail::message>
# Actualización en el Seguimiento del Fallo Reportado

La incidencia **#{{ $resolucion->incidenciaRegistrada->id_incidencia }}** ha sido actualizada.

- **Nuevo Estado:** {{ $resolucion->estado }}
- **Fecha de Actualización:** {{ $resolucion->fecha_resolucion->format('d/m/Y H:i') }}

**Comentarios del Administrador:**
{{ $resolucion->comentario }}

<x-mail::button :url="route('incidencias.show', $resolucion->incidencia)">
Ver Incidencia
</x-mail::button>

Gracias,<br>
El equipo de {{ config('app.name') }}
</x-mail::message>