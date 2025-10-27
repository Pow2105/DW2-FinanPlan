<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CuentaController;
use App\Http\Controllers\TransaccionController;
use App\Http\Controllers\PresupuestoController;
use App\Http\Controllers\MetaAhorroController;
use App\Http\Controllers\InformeController;
use App\Http\Controllers\RecordatorioController;

// Ruta principal
Route::get('/', function () {
    return view('welcome');
});

// Rutas de autenticación
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Rutas protegidas (requieren autenticación)
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Cuentas
    Route::resource('cuentas', CuentaController::class);
    
    // Transacciones
    Route::resource('transacciones', TransaccionController::class);
    
    // Presupuestos
    Route::resource('presupuestos', PresupuestoController::class);
    
    // Metas de Ahorro
    Route::resource('metas', MetaAhorroController::class);
    Route::post('metas/{meta}/add-funds', [MetaAhorroController::class, 'addFunds'])->name('metas.addFunds');
    
    // Recordatorios
    Route::resource('recordatorios', RecordatorioController::class);
    
    // Informes
    Route::get('/informes', [InformeController::class, 'index'])->name('informes.index');
    Route::get('/informes/export', [InformeController::class, 'export'])->name('informes.export');
});