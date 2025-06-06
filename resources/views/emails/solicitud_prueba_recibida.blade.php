<x-mail::message>
# Nueva Solicitud de Prueba Recibida

Se ha recibido una nueva solicitud de prueba con los siguientes detalles:

- **ID de Solicitud:** {{ $solicitud->id_solicitud }}
- **Alumno:** {{ $solicitud->usuarioAlumno->nombre }} {{ $solicitud->usuarioAlumno->apellidos }}
- **Fecha de Solicitud:** {{ $solicitud->fecha_solicitud }}

<x-mail::button :url="url('/solicitudes')">
Ver Solicitud
</x-mail::button>

Gracias,<br>
El equipo de {{ config('app.name') }}
</x-mail::message>