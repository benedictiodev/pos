<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryProductController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'post_login'])->name('post_login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix("/dashboard")->group(function () {
    Route::prefix("/master-data")->group(function () {
        Route::prefix("/products")->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('dashboard.master-data.products');
            Route::get('/create', [ProductController::class, 'create'])->name('dashboard.master-data.products.create');
            Route::post('/', [ProductController::class, 'store'])->name('dashboard.master-data.products.post');
            Route::get('/{id}/edit', [ProductController::class, 'show'])->name('dashboard.master-data.products.show');
            Route::put('/{id}', [ProductController::class, 'update'])->name('dashboard.master-data.products.update');
            Route::delete('/{id}', [ProductController::class, 'destroy'])->name('dashboard.master-data.products.delete');
        });
        Route::prefix("/category-products")->group(function () {
            Route::get('/', [CategoryProductController::class, 'index'])->name('dashboard.master-data.category-products');
            Route::get('/create', [CategoryProductController::class, 'create'])->name('dashboard.master-data.category-products.create');
            Route::post('/', [CategoryProductController::class, 'store'])->name('dashboard.master-data.category-products.post');
            Route::get('/{id}/edit', [CategoryProductController::class, 'show'])->name('dashboard.master-data.category-products.show');
            Route::put('/{id}', [CategoryProductController::class, 'update'])->name('dashboard.master-data.category-products.update');
            Route::delete('/{id}', [CategoryProductController::class, 'destroy'])->name('dashboard.master-data.category-products.delete');
        });
    });
});
