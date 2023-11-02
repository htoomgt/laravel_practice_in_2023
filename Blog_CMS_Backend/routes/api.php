<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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
Route::get("/test", function(){
    return "Hello World";
});

Route::post('/subscriber-register', [AuthController::class, 'subscriberRegister'])->name('auth.subscriberRegister');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');


Route::group(['middleware' => [
    'auth:api',
    'ability:'.config('sanctum.token_ability.access_api'),
    ]], function(){
    
    
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');



    //user api
    Route::get('/users', [UserController::class, 'index'])->name('user.getAll');
    Route::post('/users', [UserController::class, 'store'])->name('user.create');
    Route::get('/users/{id}', [UserController::class, 'showUserById'])->name('user.showById');
    Route::put('/users/{id}', [UserController::class, 'updateUserById'])->name('user.updateById');
    Route::delete('/users/{id}', [UserController::class, 'deleteById'])->name('user.deleteByid');
    Route::post('/users/customSearch', [UserController::class, 'searchByFields'])->name('user.customSearch');



});


Route::post('/refresh', [AuthController::class, 'refreshTokens'])
->middleware([
    'auth:api',
    'ability:'.config('sanctum.token_ability.issue_access_token'),
])
->name('auth.refreshToken');


