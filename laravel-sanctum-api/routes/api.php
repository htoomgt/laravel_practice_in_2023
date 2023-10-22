<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;

enum TokenAbility: string
{
    case ISSUE_ACCESS_TOKEN = 'issue-access-token';
    case ACCESS_API = 'access-api';
}
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


// Public Route
Route::get('/products', [ProductController::class, 'index'])->name('product.showAll');

Route::get('/products/{product}', [ProductController::class, 'show'])->name('product.show');





Route::post("/products/searchByName", [ProductController::class, 'searchByName'])->name('product.searchByName');

// Protected Route
Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::post('/products', [ProductController::class, 'store'])->name('product.store');

    Route::put("/products/{product}", [ProductController::class, 'update'])->name('product.update');

    Route::delete("/products/{product}", [ProductController::class, 'destroy'])->name('product.delete');

    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});

// Auth Routes
Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

Route::post('/refresh', [AuthController::class, 'refreshToken'])
->middleware([
    'auth:sanctum',
    'ability:'.TokenAbility::ISSUE_ACCESS_TOKEN->value,    
])
->name('auth.refreshToken');
