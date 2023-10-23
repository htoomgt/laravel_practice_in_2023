<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BlogPostController;
use App\Http\Controllers\AdminAuthController;

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
Route::group(['middleware' => ['auth:api']], function () {

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
    'auth:api',
    'ability:'.TokenAbility::ISSUE_ACCESS_TOKEN->value,
])
->name('auth.refreshToken');



/**Blog Post Routes start*/

Route::get('/blog-posts', [BlogPostController::class, 'index'])->name('blogPost.showAll');
Route::get('/blog-posts/{id}', [BlogPostController::class, 'show'])->name('blogPost.show');
Route::post('/blog-posts', [BlogPostController::class, 'store'])->name('blogPost.store');
Route::put('/blog-posts/{id}', [BlogPostController::class, 'update'])->name('blogPost.update');
Route::delete('/blog-posts/{id}', [BlogPostController::class, 'destroy'])->name('blogPost.delete');

/**Blog Post Routes end */

/** Admin Auth Routes start */
Route::post('register-admin', [AdminAuthController::class, 'register'])->name('adminAuth.register');
Route::post('admin-login', [AdminAuthController::class, 'login'])->name('adminAuth.login');
Route::post('admin-logout', [AdminAuthController::class, 'logout'])->name('adminAuth.logout')->middleware(['auth:admin-api']);
/** Admin Auth Routes end */



