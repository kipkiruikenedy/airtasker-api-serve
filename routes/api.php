<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Tasker\TaskerController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TaskController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ADMIN-CLIENT
Route::get('/admin-clients', [AdminController::class, 'clients']);
Route::get('/clients/{id}', [UserController::class, 'show']);
Route::get('/clients/latest', [UserController::class, 'latest']);

// ADMIN-TASKER
Route::get('/admin-taskers', [AdminController::class, 'taskers']);
Route::get('/taskers/{id}', [UserController::class, 'show']);
Route::get('/taskers/latest', [UserController::class, 'latest']);
// ADMIN-TASK
Route::get('/tasks', [UserController::class, 'index']);
Route::get('/tasks/{id}', [UserController::class, 'show']);
Route::get('/tasks/latest', [UserController::class, 'latest']);



// TASKER
Route::post('/register/tasker', [TaskerController::class, 'register']);


// CLIENT
Route::post('/register/client', [ClientController::class, 'register']);



// LOGIN
Route::post('login', [LoginController::class, 'login']);

Route::post('/user', [TaskerController::class, 'userDetails']);

// TASK
Route::post('/tasks', [TaskController::class, 'store']);
Route::get('/tasks/{id}', [TaskController::class, 'show']);








