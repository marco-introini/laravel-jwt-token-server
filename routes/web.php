<?php

use Illuminate\Support\Facades\Route;

Route::middleware('throttle:esempio')->get('/', function () {
    return view('welcome');
});
