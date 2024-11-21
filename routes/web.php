<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas por autenticación
Route::middleware(['verificar.sesion'])->group(function () {
    
    // Rutas para administradores
    Route::middleware(['verificar.rol:Administrador'])->group(function () {
        Route::get('/admin/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');
        Route::resource('usuarios', UsuarioController::class);
    });

    // Rutas para coordinadores
    Route::middleware(['verificar.rol:Coordinador'])->group(function () {
        Route::get('/coordinador/dashboard', function () {
            return view('coordinador.dashboard');
        })->name('coordinador.dashboard');
    });

    // Rutas para alumnos
    Route::middleware(['verificar.rol:Alumno'])->group(function () {
        Route::get('/alumno/dashboard', function () {
            return view('alumno.dashboard');
        })->name('alumno.dashboard');
    });
});
