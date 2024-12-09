<?php

use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\PredioController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
 


Route::get('/', [HomeController::class, 'index'])->name('index');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/olvide_password', [AuthController::class, 'olvide_password'])->name('olvide_password');


Route::post('/password_email', [AuthController::class, 'password_email'])->name('password_email');

Route::middleware(['auth'])->group(function () {

    Route::get('/home', [HomeController::class, 'home'])->name('home');
    Route::get('/admin/pages/empresas', [EmpresaController::class, 'empresas'])->name('admin.empresas');
    Route::post('/admin/pages/empresas/store', [EmpresaController::class, 'store'])->name('admin.empresas.store');

    Route::get('/empresa/{id}', [EmpresaController::class, 'show'])->name('empresa.show');
    Route::get('/empresa/{id}/edit', [EmpresaController::class, 'edit'])->name('empresa.edit');
    Route::post('/empresa/{id}/destroy', [EmpresaController::class, 'destroy'])->name('empresa.destroy');


    Route::get('/empresa/predios/{nombre}/{id}', [PredioController::class, 'index'])->name('empresa.predios'); // muestra los predio de la empresa
    Route::post('/empresa/predios/store/{empresa_id}', [PredioController::class, 'store'])->name('empresa.predios.store');//guarda el predio de la empresa

    Route::get('empresa/predios/{empresa_id}/{id}/show', [PredioController::class, 'show'])->name('empresa.predio.show'); // Mostrar un predio
    Route::get('empresa/predios/{empresa_id}/{id}/edit', [PredioController::class, 'edit'])->name('empresa.predio.edit'); // Editar un predio
    Route::delete('empresa/predios/{empresa_id}/{id}/destoy', [PredioController::class, 'destroy'])->name('empresa.predio.destroy'); // Eliminar un predio

// RUTAS DE PREDIOS AGRICULTURA
    Route::get('admin/pages/cartografia/{empresa}/{predio}', [PredioController::class, 'cartografia'])->name('cartografia.index');
    Route::get('admin/pages/historicos/{empresa}/{predio}', [PredioController::class, 'historicos'])->name('historico.index');
    Route::get('admin/pages/analisis-predio/{empresa}/{predio}', [PredioController::class, 'analisisPredio'])->name('analisis.predio.index');
    Route::get('admin/pages/analisis-cultivo/{empresa}/{predio}', [PredioController::class, 'analisisCultivo'])->name('analisis.predio.cultivo.index');
    Route::get('admin/pages/monitoreo/{empresa}/{predio}', [PredioController::class, 'monitoreo'])->name('monitoreo.index');



    /// RUTAS DE PREDIOS GANADERIA
    Route::get('admin/pages/potrero/{empresa}/{predio}', [PredioController::class, 'potrero'])->name('potrero.index');
    Route::get('admin/pages/hatoGanadero/{empresa}/{predio}', [PredioController::class, 'hatoGanadero'])->name('hato-ganadero.index');

      







});

 

