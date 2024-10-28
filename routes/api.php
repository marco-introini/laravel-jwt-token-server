<?php

use App\Http\Controllers\Api\JwtCheckController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\SimpleJWT\SimpleJwtCheckController;
use App\Http\Controllers\Api\SimpleJWT\SimpleJwtLoginController;
use App\Http\Middleware\RequiresJsonMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(RequiresJsonMiddleware::class)->group(function () {
    Route::get('/login', LoginController::class);
    Route::get('/checkHs256', [JwtCheckController::class, 'checkHS256']);
    Route::get('/checkRs256', [JwtCheckController::class, 'checkRS256']);

    Route::prefix('simplejwt')->group(function () {
       Route::get('login', SimpleJwtLoginController::class);
        Route::get('/checkHs256', [SimpleJwtCheckController::class, 'checkHS256']);
        Route::get('/checkRs256', [SimpleJwtCheckController::class, 'checkRS256']);
    });
});

