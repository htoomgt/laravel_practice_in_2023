<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

Route::controller(UserController::class)->group(function () {
    Route::get('users', 'getAllUsers');
    Route::get('users/{id}', 'getUserById');
});



Route::controller(TodoController::class)->group(function () {
    Route::get('todos', 'index');
    Route::get('todos/{id}', 'show');
    Route::post('todos', 'store');
    Route::put('todos/{id}', 'update');
    Route::delete('todos/{id}', 'destroy');
});
