<?php

use App\Framework\Http\Router;
use App\Http\Controllers\AnaoController;
use App\Http\Controllers\AuthenticationController;

Router::get('/', [AnaoController::class, 'index']);
Router::get('/login', [AuthenticationController::class, 'index']);