<?php

use App\Http\Controllers\Api\ClientsController;
use App\Http\Controllers\Api\ProjectsController;
use App\Http\Controllers\Api\TasksController;
use App\Http\Controllers\Api\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::resource('clients', ClientsController::class, ['only' => ['index', 'show']]);
Route::resource('projects', ProjectsController::class, ['only' => ['index', 'show']]);
Route::resource('tasks', TasksController::class, ['only' => ['index', 'show']]);
Route::resource('users', UsersController::class, ['only' => ['index', 'show']]);
