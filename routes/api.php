<?php

use App\Http\Controllers\CaraquteristiqueController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\Api\StatisticsController;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::post('/register', [App\Http\Controllers\Api\UserController::class, 'register'])->name('register');
Route::post('/login', [App\Http\Controllers\Api\UserController::class, 'login'])->name('login'); 
Route::get('/users', [App\Http\Controllers\Api\UserController::class, 'index'])->name('users');
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [App\Http\Controllers\Api\UserController::class, 'getUser'])->name('user');
    Route::get('/logout', [App\Http\Controllers\Api\UserController::class, 'logout'])->name('logout');
    Route::post("/product" , [ProductController::class , "store"]);
    // New user profile routes
    Route::put('/user/update', [App\Http\Controllers\Api\UserController::class, 'updateUser'])->name('user.update');
    Route::put('/user/update-password', [App\Http\Controllers\Api\UserController::class, 'updatePassword'])->name('user.update-password');
    Route::post('/user/update-image', [App\Http\Controllers\Api\UserController::class, 'updateProfileImage'])->name('user.update-image');
    Route::delete('/user/delete', [App\Http\Controllers\Api\UserController::class, 'deleteUser'])->name('user.delete');
    // User status update route
    Route::put('/users/{id}/status', [App\Http\Controllers\Api\UserController::class, 'updateStatus'])->name('users.status.update'); 


    //cart routes 
    Route::post('/cart/add', [App\Http\Controllers\CartController::class, 'addToCart'])->name('cart.add');
    Route::delete('/cart/remove/{id}', [App\Http\Controllers\CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::get('/cart', [App\Http\Controllers\CartController::class, 'getCart'])->name('cart.get');
});
Route::get('/admin/statistics/dashboard', [StatisticsController::class, 'getDashboardStats']);
Route::get('/admin/statistics/users', [StatisticsController::class, 'getUserStats']);
Route::get('/admin/statistics/products', [StatisticsController::class, 'getProductStats']);
Route::get('products' , [ProductController::class , "index"] ) ;  
Route::get('product/{product}' , [ProductController::class , "show"]); 
Route::get("/filterdproducts" , [ProductController::class , "filterd"]); 
Route::get('caracteristique/{id}' , [CaraquteristiqueController::class , "show"]); 
Route::post('caracteristique' , [CaraquteristiqueController::class , "store"]);
Route::get('categorys' , [CategoryController::class , "index"]) ; 
