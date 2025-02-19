<?php

use App\Framework\Facades\Route;
use App\Http\Controllers\AnaoController;
use App\Http\Controllers\AuthenticationController;

Route::get('/', function () {
    return '<a href="/login">Login</a>';
});

Route::get('/login', [AuthenticationController::class, 'index']);

Route::get('/anao', [AnaoController::class, 'index']);