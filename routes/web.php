<?php

use App\Framework\Facades\Auth;
use App\Framework\Facades\Route;
use App\Framework\Http\Request;
use App\Http\Controllers\AnaoController;
use App\Http\Controllers\AuthenticationController;

Route::get('/', function (Request $request) {
    $auth = Auth::check();

    if ($auth) {
        return redirect('/anao');
    }
    return redirect('/login');
});

Route::get('/login', [AuthenticationController::class, 'index']);
Route::post('/login', [AuthenticationController::class, 'login']);

Route::get('/anao', [AnaoController::class, 'index']);
Route::get('/anao/show', [AnaoController::class, 'show']);
Route::get('/anao/edit', [AnaoController::class, 'edit']);
