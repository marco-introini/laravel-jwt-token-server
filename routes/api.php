<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Middleware\RequiresJsonMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(RequiresJsonMiddleware::class)->group(function () {
    Route::get('/login', LoginController::class);
});

