<?php

use App\Framework\Http\Router;
use App\Http\Controllers\AnaoController;

Router::get('/', [AnaoController::class, 'index']);