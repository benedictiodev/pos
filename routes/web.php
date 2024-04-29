<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'post_login'])->name('post_login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix("/dashboard")->middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::prefix("/master-data")->group(function () {
        Route::prefix("/products")->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('dashboard.master-data.products');
        });
        Route::prefix("/category-products")->group(function () {
            Route::get('/', [CategoryProductController::class, 'index'])->name('dashboard.master-data.category-products');
        });
    });
});
