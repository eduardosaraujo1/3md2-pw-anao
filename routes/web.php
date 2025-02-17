<?php

use App\Framework\Http\Router;
use App\Http\Controllers\AnaoController;
use App\Http\Controllers\AuthenticationController;

Router::get('/', function () {
    return '<a href="/login">Login</a>';
});

Router::get('/login', [AuthenticationController::class, 'index']);

Router::get('/anao', [AnaoController::class, 'index']);