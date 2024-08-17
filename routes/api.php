<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Middleware\AuthMobileCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::prefix('/')->middleware([
    // AuthMobileCheck::class
])->group(function() {
    Route::prefix('/order')->group(function() {
        Route::get("/", [OrderController::class, 'order_active']);
        Route::get("/new-order", [OrderController::class, 'add_new_order']);
        Route::post("/post_new_order", [OrderController::class, 'post_new_order']);
        Route::get("/history", [OrderController::class, 'order_history']);
        Route::get("/{id}/edit", [OrderController::class, 'order_history_edit']);
        Route::put("/{id}", [OrderController::class, 'order_history_update']);
        Route::get("/{id}/detail", [OrderController::class, 'order_detail']);
        Route::delete("/{id}", [OrderController::class, 'delete_order']);
        Route::get("/update/{id}", [OrderController::class, 'edit_order']);
        Route::put("/update/{id}", [OrderController::class, 'update_order']);
        Route::get("/report", [OrderController::class, 'report']);
    });
});