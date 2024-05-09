<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CashFlowController;
use App\Http\Controllers\CashInController;
use App\Http\Controllers\CashOutController;
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
        Route::prefix("/product")->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('dashboard.master-data.product');
            Route::get('/create', [ProductController::class, 'create'])->name('dashboard.master-data.product.create');
            Route::post('/', [ProductController::class, 'store'])->name('dashboard.master-data.product.post');
            Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('dashboard.master-data.product.edit');
            Route::put('/{id}', [ProductController::class, 'update'])->name('dashboard.master-data.product.update');
            Route::delete('/{id}', [ProductController::class, 'destroy'])->name('dashboard.master-data.product.delete');
        });
        Route::prefix("/category-product")->group(function () {
            Route::get('/', [CategoryProductController::class, 'index'])->name('dashboard.master-data.category-product');
            Route::get('/create', [CategoryProductController::class, 'create'])->name('dashboard.master-data.category-product.create');
            Route::post('/', [CategoryProductController::class, 'store'])->name('dashboard.master-data.category-product.post');
            Route::get('/{id}/edit', [CategoryProductController::class, 'edit'])->name('dashboard.master-data.category-product.edit');
            Route::put('/{id}', [CategoryProductController::class, 'update'])->name('dashboard.master-data.category-product.update');
            Route::delete('/{id}', [CategoryProductController::class, 'destroy'])->name('dashboard.master-data.category-product.delete');
        });
    });
    Route::prefix("/finance")->group(function () {
        Route::prefix("/cash-in")->group(function () {
            Route::get('/', [CashInController::class, 'index'])->name('dashboard.finance.cash-in');
            Route::get('/create', [CashInController::class, 'create'])->name('dashboard.finance.cash-in.create');
            Route::post('/', [CashInController::class, 'store'])->name('dashboard.finance.cash-in.post');
            Route::get('/{id}/edit', [CashInController::class, 'edit'])->name('dashboard.finance.cash-in.edit');
            Route::put('/{id}', [CashInController::class, 'update'])->name('dashboard.finance.cash-in.update');
            Route::delete('/{id}', [CashInController::class, 'destroy'])->name('dashboard.finance.cash-in.delete');
        });
        Route::prefix("/cash-out")->group(function () {
            Route::get('/', [CashOutController::class, 'index'])->name('dashboard.finance.cash-out');
            Route::get('/create', [CashOutController::class, 'create'])->name('dashboard.finance.cash-out.create');
            Route::post('/', [CashOutController::class, 'store'])->name('dashboard.finance.cash-out.post');
            Route::get('/{id}/edit', [CashOutController::class, 'edit'])->name('dashboard.finance.cash-out.edit');
            Route::put('/{id}', [CashOutController::class, 'update'])->name('dashboard.finance.cash-out.update');
            Route::delete('/{id}', [CashOutController::class, 'destroy'])->name('dashboard.finance.cash-out.delete');
        });
        Route::get("/cash-flow-daily", [CashFlowController::class, 'list_daily'])->name('dashboard.finance.cash-flow-daily');
        Route::get("/cash-flow-monthly", [CashFlowController::class, 'list_monthly'])->name('dashboard.finance.cash-flow-monthly');
    });
    Route::prefix("/profile")->group(function () {
        Route::get("/", [AuthController::class, 'profile'])->name('dashboard.profile');
        Route::post("/", [AuthController::class, 'post_profile'])->name('dashboard.profile.post');
    });
    Route::prefix("/change_password")->group(function () {
        Route::get("/", [AuthController::class, 'change_password'])->name('dashboard.change_password');
        // Route::post("/", [AuthController::class, 'post_change_password'])->name('dashboard.change_password.post');
    });
});
