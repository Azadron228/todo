<?php

use App\Http\Controllers\ImageController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::group(['middleware' => ['auth']], function () {

    Route::resource('/task', TaskController::class);
    Route::post('/fgh', [TaskController::class, 'store']);

    Route::post('/upload_image', [ImageController::class, 'imageUpload'])->name('todo.upload_image');
    Route::get('/remove_image/{id?}', [ImageController::class, 'removeImage'])->name('todo.remove_image');
});

require __DIR__ . '/auth.php';
