<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;

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

Route::get('/test', function () {
    return [
        'message' => 'Hello World!'
    ];
});


Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('/login', [AuthenticationController::class, 'login']);



Route::middleware('auth:api')->group(function () {
    Route::get('/logout', [AuthenticationController::class, 'logout']);


    Route::get('/api/products', [AuthenticationController::class, 'index']);
    Route::post('/api/products', [AuthenticationController::class, 'store']);
    Route::get('/api/products/{id}', [AuthenticationController::class, 'show']);
    Route::put('/api/products/{id}', [AuthenticationController::class, 'update']);
    Route::delete('/api/products/{id}', [AuthenticationController::class, 'destroy']);
});
