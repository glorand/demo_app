<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\TasksController;
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

Route::get('/', [DashboardController::class, 'index'])->name('home');
Route::get('task',[TasksController::class, 'create'])->name('tasks.create');
Route::post('task',[TasksController::class, 'store'])->name('tasks.store');
Route::post('file-upload', [FileUploadController::class, 'fileUploadPost'])->name('file.upload.post');
