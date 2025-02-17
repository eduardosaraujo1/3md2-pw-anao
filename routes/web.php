<?php

use App\Framework\Router\Router;
use App\Http\Controllers\AnaoController;
use App\Http\Controllers\AuthenticationController;

Router::get('/', function () {
    return '<a href="/login">Login</a>';
});

Router::get('/login', [AuthenticationController::class, 'index']);

Router::get('/anoes', [AnaoController::class, 'index']);