<?php

use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Request;
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


Route::get('/', function () {
    return "Hello";
});

Route::middleware('auth')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth']], function () {

    Route::resource('/task', TaskController::class);

    Route::post('/task/{task}/attachments', [AttachmentController::class, 'upload'])->name('attachments.upload');
    Route::get('/attachments/{attachment}', [AttachmentController::class, 'download'])->name('attachments.download');
});

require __DIR__ . '/auth.php';
