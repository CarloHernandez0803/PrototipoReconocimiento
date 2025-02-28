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

Route::middleware(['web'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    })->middleware('guest');


    Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
    Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
    //Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Rutas protegidas por autenticación
    Route::middleware(['auth','verificar.sesion', 'verificar.inactividad'])->group(function () {
        Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

        Route::resource('usuarios', UsuarioController::class);
        Route::resource('senalamientos_entrenamientos', SenEntrenamientoController::class);
        Route::resource('senalamientos_pruebas', SenPruebaController::class);
        Route::resource('solicitudes', SolicitudController::class);
        Route::resource('experiencias', ExperienciaController::class);
        Route::resource('incidencias', IncidenciaController::class);
        Route::get('/timeline', [IncidenciaController::class, 'timeline'])->name('incidencias.timeline');
        Route::resource('preguntas', PreguntaController::class);
        Route::resource('evaluaciones', EvaluacionController::class);

        Route::get('resoluciones/create/{id}', [ResolucionController::class, 'create'])->name('resoluciones.create');
        Route::post('resoluciones/store/{id}', [ResolucionController::class, 'store'])->name('resoluciones.store');
        Route::get('resoluciones/edit/{id}', [ResolucionController::class, 'edit'])->name('resoluciones.edit');
        Route::put('resoluciones/update/{id}', [ResolucionController::class, 'update'])->name('resoluciones.update');

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
    });
});