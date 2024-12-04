<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
 

Route::get('/', [HomeController::class, 'index'])->name('index');
Route::post('/login', [AuthController::class, 'login'])->name('login'); 
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/olvide_password', [AuthController::class, 'olvide_password'])->name('olvide_password');

Route::get('/home', [HomeController::class, 'home'])->name('home');
Route::post('/password_email', [AuthController::class, 'password_email'])->name('password_email');
