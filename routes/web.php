<?php

use App\Framework\Facades\Auth;
use App\Framework\Facades\Route;
use App\Framework\Http\Request;
use App\Http\Controllers\AnaoController;
use App\Http\Controllers\AuthenticationController;

Route::get('/', function (Request $request) {
    if (Auth::check()) {
        return redirect('/anoes');
    }
    return redirect('/login');
});

Route::get('/login', [AuthenticationController::class, 'index']);

Route::post('/login', [AuthenticationController::class, 'login']);
Route::get('/logout', [AuthenticationController::class, 'logout']);

Route::get('/home', function () {
    return redirect('/anoes');
});

Route::get('/anoes', [AnaoController::class, 'index']);
Route::get('/anao/create', [AnaoController::class, 'create']);
Route::get('/anao/{id:\d+}', [AnaoController::class, 'show']);

Route::post('/anao/update/{id:\d+}', [AnaoController::class, 'update']);
Route::post('/anao/store', [AnaoController::class, 'store']);