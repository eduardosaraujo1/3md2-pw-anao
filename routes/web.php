<?php

use App\Framework\Facades\Route;
use App\Framework\Http\Request;
use App\Http\Controllers\AnaoController;
use App\Http\Controllers\AuthenticationController;

Route::get('/', function (Request $request) {
    // return '<a href="/login">Login</a>';
    return redirect('/login');
});

Route::get('/login', [AuthenticationController::class, 'index']);

Route::get('/anao', [AnaoController::class, 'index']);
Route::get('/anao/show', [AnaoController::class, 'show']);
Route::get('/anao/edit', [AnaoController::class, 'edit']);
