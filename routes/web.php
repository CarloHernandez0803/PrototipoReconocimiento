<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
<<<<<<< HEAD

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas por autenticación y verificaciones
Route::middleware(['verificar.sesion'])->group(function () {
    // Dashboard para Administradores
    Route::get('/Administrador/dashboard', function () {
        return view('Administrador.dashboard');
    })->name('Administrador.dashboard');

    // Dashboard para Coordinadores
    Route::get('/Coordinador/dashboard', function () {
        return view('Coordinador.dashboard');
    })->name('Coordinador.dashboard');

    // Dashboard para Alumnos
    Route::get('/Alumno/dashboard', function () {
        return view('Alumno.dashboard');
    })->name('Alumno.dashboard');
=======
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
        Route::resource('preguntas', PreguntaController::class);
        Route::resource('evaluaciones', EvaluacionController::class);
    });
>>>>>>> 202c96f (Quinta version proyecto)
});
