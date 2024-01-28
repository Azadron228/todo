<?php

use App\Http\Controllers\Auth\RegisterUserController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');


Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'destroy'])
        ->name('logout');
});
