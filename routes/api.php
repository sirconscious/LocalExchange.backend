<?php

use App\Http\Middleware\RoleMiddleware;
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
    Route::get('/user', [App\Http\Controllers\Api\UserController::class, 'getUser'])->name('user')->middleware(RoleMiddleware::class . ':user'); 
    Route::get('/logout', [App\Http\Controllers\Api\UserController::class, 'logout'])->name('logout');
});