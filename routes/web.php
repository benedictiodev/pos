<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryProductController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'index']);

Route::prefix("/dashboard")->group(function () {
    Route::prefix("/master-data")->group(function () {
        Route::prefix("/products")->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('dashboard.master-data.products');
        });
        Route::prefix("/category-products")->group(function () {
            Route::get('/', [CategoryProductController::class, 'index'])->name('dashboard.master-data.category-products');
        });
    });
});
