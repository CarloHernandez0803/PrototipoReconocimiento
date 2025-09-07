<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\EntrenamientoController;
use App\Http\Controllers\PruebaController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\ExperienciaController;
use App\Http\Controllers\IncidenciaController;
use App\Http\Controllers\PreguntaController;
use App\Http\Controllers\EvaluacionController;
use App\Http\Controllers\HistorialController;
use App\Http\Controllers\SenPruebaController;
use App\Http\Controllers\SenEntrenamientoController;
use App\Http\Controllers\ResolucionController;
use App\Http\Controllers\BaseDatosController;
use App\Http\Controllers\CalendarioController;
use App\Http\Controllers\ReporteEficaciaController;
use App\Http\Controllers\ReporteSolicitudesController;
use App\Http\Controllers\ReporteExperienciasController;
use App\Http\Controllers\ReporteRecursosController;
use App\Http\Controllers\ReporteIncidenciasController;
use App\Http\Controllers\ReporteUsuariosController;
use App\Http\Controllers\HyperparameterController;
use App\Http\Controllers\ModuloPrueba;
use App\Http\Controllers\ModuloEntrenamiento;

Route::middleware(['web'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    })->middleware('guest');


    Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
    Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
    //Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('forgot-password', [\Laravel\Fortify\Http\Controllers\PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [App\Http\Controllers\CustomPasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [\Laravel\Fortify\Http\Controllers\NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [App\Http\Controllers\CustomNewPasswordController::class, 'store'])->name('password.update');

    // Rutas protegidas por autenticación
    Route::middleware(['auth','verificar.sesion', 'verificar.inactividad'])->group(function () {
        Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

        Route::resource('usuarios', UsuarioController::class);
        Route::resource('solicitudes', SolicitudController::class);
        Route::resource('experiencias', ExperienciaController::class);
        Route::resource('incidencias', IncidenciaController::class);
        Route::get('/timeline', [IncidenciaController::class, 'timeline'])->name('incidencias.timeline');
        Route::resource('preguntas', PreguntaController::class);
        Route::resource('evaluaciones', EvaluacionController::class);

        Route::resource('senalamientos_entrenamientos', SenEntrenamientoController::class);
        Route::get('/senalamientos/entrenamiento/{id}/imagen/{index}', [SenEntrenamientoController::class, 'mostrarImagen'])->name('senalamientos.entrenamiento.imagen');
        Route::resource('senalamientos_pruebas', SenPruebaController::class);
        Route::get('/senalamientos/prueba/{id}/imagen/{index}', [SenPruebaController::class, 'mostrarImagen'])->name('senalamientos.prueba.imagen');

        Route::get('/resoluciones/create/{incidencia}', [ResolucionController::class, 'create'])->name('resoluciones.create');
        Route::post('/resoluciones/store/{incidencia}', [ResolucionController::class, 'store'])->name('resoluciones.store');

        Route::get('/base_datos', [BaseDatosController::class, 'index'])->name('base_datos.index');
        Route::get('/base_datos/backup', [BaseDatosController::class, 'backup'])->name('base_datos.backup');
        Route::post('/base_datos/restore', [BaseDatosController::class, 'restore'])->name('base_datos.restore');

        Route::get('/calendario', [CalendarioController::class, 'index'])->name('calendario.index');
        Route::get('/calendario/eventos', [CalendarioController::class, 'getEventos'])->name('calendario.eventos');

        Route::get('/reportes/eficacia', [ReporteEficaciaController::class, 'index'])->name('reportes.eficacia');
        Route::get('/reportes/solicitudes', [ReporteSolicitudesController::class, 'index'])->name('reportes.solicitudes');
        Route::get('/reportes/experiencias', [ReporteExperienciasController::class, 'index'])->name('reportes.experiencias');
        Route::get('/reportes/recursos', [ReporteRecursosController::class, 'index'])->name('reportes.recursos');
        Route::get('/reportes/incidencias', [ReporteIncidenciasController::class, 'index'])->name('reportes.incidencias');
        Route::get('/reportes/usuarios', [ReporteUsuariosController::class, 'index'])->name('reportes.usuarios');

        Route::get('/reportes/incidencias/pdf', [ReporteIncidenciasController::class, 'downloadPDF']);
        Route::get('/reportes/usuarios/pdf', [ReporteUsuariosController::class, 'downloadPDF']);
        Route::get('/reportes/eficacia/excel', [ReporteEficaciaController::class, 'downloadExcel']);
        Route::get('/reportes/experiencias/excel', [ReporteExperienciasController::class, 'downloadExcel']);
        Route::get('/reportes/recursos/excel', [ReporteRecursosController::class, 'downloadExcel']);
        Route::get('/reportes/solicitudes/excel', [ReporteSolicitudesController::class, 'downloadExcel']);
        Route::get('/hyperparameters/check-progress', [HyperparameterController::class, 'checkProgress'])->name('hyperparameters.checkProgress');
        Route::resource('hyperparameters', HyperparameterController::class)->only(['index', 'create', 'store', 'show']);
        Route::get('/modulo_prueba', [ModuloPrueba::class, 'index'])->name('modulo_prueba.index');
        Route::post('/modulo_prueba/classify', [ModuloPrueba::class, 'classify'])->name('modulo_prueba.classify');
        Route::get('/modulo_prueba/check-status/{id}', [ModuloPrueba::class, 'checkStatus'])->name('modulo_prueba.checkStatus');
    });
});