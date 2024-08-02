<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//RUTAS WEB
Route::get('/', [App\Http\Controllers\Web\HomeController::class, 'index']);
Route::group([
    'middleware' =>['cors']
], function () {
    Route::post('/cargarfiltros', [App\Http\Controllers\Web\HomeController::class, 'cargarFiltros']);
    Route::post('/cargarparroquias', [App\Http\Controllers\Web\HomeController::class, 'cargarParroquias']);
    Route::post('/cargarrecintos', [App\Http\Controllers\Web\HomeController::class, 'cargarRecintos']);
    Route::post('/cargarobras', [App\Http\Controllers\Web\HomeController::class, 'cargarObras']);
    Route::post('/cargarproyectos', [App\Http\Controllers\Web\HomeController::class, 'cargarProyectos']);
    Route::get('/cargarestados', [App\Http\Controllers\Dashboard\ProyectoController::class, 'cargarEstados']);
});

//RUTAS DASHBOARD
Route::group([
    'middleware' => 'auth'
], function () {
    Route::group([
        'prefix' => 'dashboard'
    ], function () {
        Route::get('/', [App\Http\Controllers\Dashboard\DashboardController::class, 'index']);
        Route::get('/proyectos', [App\Http\Controllers\Dashboard\ProyectoController::class, 'index']);
        Route::get('/proyectos/cargar', [App\Http\Controllers\Dashboard\ProyectoController::class, 'cargar']);
        Route::post('/proyecto/guardar', [App\Http\Controllers\Dashboard\ProyectoController::class, 'guardar']);
        Route::post('/proyecto/buscar', [App\Http\Controllers\Dashboard\ProyectoController::class, 'buscar']);
        Route::delete('/proyecto/eliminar', [App\Http\Controllers\Dashboard\ProyectoController::class, 'eliminar']);
        Route::post('/proyecto/editar', [App\Http\Controllers\Dashboard\ProyectoController::class, 'editar']);

        Route::get('/cantones/cargar', [App\Http\Controllers\Dashboard\ProyectoController::class, 'cargarCantones']);
        Route::post('/parroquias/cargar', [App\Http\Controllers\Dashboard\ProyectoController::class, 'cargarParroquias']);
        Route::post('/recintos/cargar', [App\Http\Controllers\Dashboard\ProyectoController::class, 'cargarRecintos']);
        Route::get('/estados/cargar', [App\Http\Controllers\Dashboard\ProyectoController::class, 'cargarEstados']);
        Route::get('/comunidades/cargar', [App\Http\Controllers\Dashboard\ProyectoController::class, 'cargarComunidades']);
        Route::get('/obras/cargar', [App\Http\Controllers\Dashboard\ProyectoController::class, 'cargarObras']);
    });
    Route::get('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
});
//LOGIN
Route::get('/login', [App\Http\Controllers\AuthController::class, 'loginIndex'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);

