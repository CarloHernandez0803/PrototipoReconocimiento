<x-mail::message>
# Nuevo Reporte de Incidencia Registrado

Se ha registrado una nueva incidencia con los siguientes detalles:

- **ID de Incidencia:** #{{ $incidencia->id_incidencia }}
- **Tipo de Incidencia:** {{ $incidencia->tipo_incidencia ?? 'No especificado' }}
- **Fecha de Reporte:** {{ $incidencia->fecha_reporte ? \Carbon\Carbon::parse($incidencia->fecha_reporte)->format('d/m/Y H:i') : 'Fecha no disponible' }}
- **Coordinador Reportante:** {{ $incidencia->usuarioCoordinador ? ($incidencia->usuarioCoordinador->nombre . ' ' . $incidencia->usuarioCoordinador->apellidos) : 'No asignado' }}

**Descripción:**
{{ $incidencia->descripcion ?? 'Sin descripción proporcionada' }}

<x-mail::button :url="route('incidencias.show', $incidencia->id_incidencia)">
Ver Detalles de la Incidencia
</x-mail::button>

Gracias,<br>
El equipo de {{ config('app.name') }}
</x-mail::message>