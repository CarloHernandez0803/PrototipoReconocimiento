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
});
