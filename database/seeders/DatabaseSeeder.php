<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\{
    Usuario,
    Sesion,
    Evaluacion,
    Experiencia,
    Incidencia,
    Resolucion,
    Solicitud,
    Pregunta,
    Notificacion
};

class DatabaseSeeder extends Seeder
{
    // Configuración de la base de datos 
    private const CONFIG = [
        'usuarios' => [
            'total' => 10,
            'distribucion' => [ // Distribución de usuarios por rol
                'Administrador' => 2,
                'Coordinador' => 3,
                'Alumno' => 5
            ]
        ],
        'notificaciones_por_usuario' => 4
    ];

    // Ejecutar los seeders
    public function run(): void
    {
        DB::beginTransaction(); // Iniciar la transacción
        
        try { // Intenta ejecutar el código
            $this->crearUsuarios();
            $this->crearSesiones();
            $this->crearEvaluacionesYExperiencias();
            $this->crearIncidenciasYResoluciones();
            $this->crearSolicitudes();
            $this->crearPreguntasYNotificaciones();

            DB::commit(); // Confirmar la transacción
        } catch (\Exception $e) { // Captura cualquier error
            DB::rollBack(); // Revertir la transacción
            throw new \Exception("Error en el seeding: " . $e->getMessage()); // Lanzar el error
        }
    }

    private function crearUsuarios(): void // Crea los usuarios
    {
        foreach (self::CONFIG['usuarios']['distribucion'] as $rol => $cantidad) { // Itera sobre la distribución
            Usuario::factory()->count($cantidad)->create(['rol' => $rol]); // Crea usuarios con el rol correspondiente
        }
    }

    private function crearSesiones(): void
    {
        Usuario::all()->each(function ($usuario) { // Itera sobre todos los usuarios
            Sesion::factory()->create([ // Crea una sesión
                'usuario' => $usuario->id_usuario, // Asigna el ID del usuario
                'token_sesion' => Str::random(60) // Genera un token de sesión
            ]);
        });
    }

    private function crearEvaluacionesYExperiencias(): void
    {
        Usuario::where('rol', 'Alumno')->each(function ($alumno) { // Itera sobre los alumnos
            Evaluacion::factory()->create(['alumno' => $alumno->id_usuario]); // Crea una evaluación
            Experiencia::factory()->create(['usuario' => $alumno->id_usuario]); // Crea una experiencia
        });
    }

    private function crearIncidenciasYResoluciones(): void
    {
        Usuario::where('rol', 'Coordinador')->each(function ($coordinador) { // Itera sobre los coordinadores
            $incidencia = Incidencia::factory()->create(['coordinador' => $coordinador->id_usuario]); // Crea una incidencia

            Resolucion::factory()->create([ // Crea una resolución
                'incidencia' => $incidencia->id_incidencia, // Asigna el ID de la incidencia
                'administrador' => $this->obtenerAdministradorAleatorio() // Asigna un administrador aleatorio
            ]);
        });
    }

    private function crearSolicitudes(): void
    {
        Usuario::where('rol', 'Coordinador')->each(function ($coordinador) { // Itera sobre los coordinadores
            Solicitud::factory()->create([ // Crea una solicitud
                'coordinador' => $coordinador->id_usuario, // Asigna el ID del coordinador
                'administrador' => $this->obtenerAdministradorAleatorio() // Asigna un administrador aleatorio
            ]);
        });
    }

    private function crearPreguntasYNotificaciones(): void
    {
        Usuario::all()->each(function ($usuario) { // Itera sobre todos los usuarios
            Pregunta::factory()->create(['usuario' => $usuario->id_usuario]); // Crea una pregunta
            
            Notificacion::factory()->count(self::CONFIG['notificaciones_por_usuario'])->create(['usuario' => $usuario->id_usuario]); // Crea notificaciones
        });
    }

    private function obtenerAdministradorAleatorio(): int
    {
        return Usuario::where('rol', 'Administrador')->inRandomOrder()->first()->id_usuario; // Obtiene un administrador aleatorio
    }
}