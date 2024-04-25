<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


Route::get('/login', [AuthController::class, 'index']);

Route::get('/', function () {
    return view('welcome');
});