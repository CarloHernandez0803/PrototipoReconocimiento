<x-mail::message>
# Actualización en el Seguimiento del Fallo Reportado

La incidencia **#{{ $resolucion->incidenciaRegistrada->id_incidencia }}** ha sido actualizada.

- **Nuevo Estado:** {{ $resolucion->estado }}
- **Fecha de Actualización:** {{ $resolucion->fecha_resolucion ? \Carbon\Carbon::parse($resolucion->fecha_resolucion)->format('d/m/Y H:i') : 'Fecha no disponible' }}

**Comentarios del Administrador:**
{{ $resolucion->comentario }}

<x-mail::button :url="route('incidencias.timeline')">
Ver Historial de Incidencias
</x-mail::button>

Gracias,<br>
El equipo de {{ config('app.name') }}
</x-mail::message>