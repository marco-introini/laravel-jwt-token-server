<?php

use App\Http\Controllers\Api\JwtCheckController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Middleware\RequiresJsonMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(RequiresJsonMiddleware::class)->group(function () {
    Route::get('/login', LoginController::class);
    Route::get('/checkHs256', [JwtCheckController::class, 'checkHS256']);
});

