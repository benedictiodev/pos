<?php

use App\Http\Controllers\Api\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

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