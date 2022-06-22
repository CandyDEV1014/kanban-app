<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ColumnController;
use App\Http\Controllers\TaskController;

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

Route::get('/', [HomeController::class, 'index']);

// column routes
Route::post('columns', [ColumnController::class, 'store']);
Route::delete('columns/{id}', [ColumnController::class, 'destroy']);

// task routes
Route::get('tasks', [TaskController::class, 'index']);
Route::post('tasks', [TaskController::class, 'store']);
Route::post('task', [TaskController::class, 'ajaxGetTask']);
Route::put('tasks/sync', [TaskController::class, 'sync']);
Route::put('tasks/{task}', [TaskController::class, 'update']);
