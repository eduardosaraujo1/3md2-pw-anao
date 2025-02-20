<?php

use App\Framework\Database\Connection;
use App\Framework\Facades\Route;
use App\Framework\Http\Request;
use App\Http\Controllers\AnaoController;
use App\Http\Controllers\AuthenticationController;

Route::get('/', function (Request $request) {
    dd(Connection::singleton()->query("SELECT * FROM users"));
    return '<a href="/login">Login</a>';
});

Route::get('/login', [AuthenticationController::class, 'index']);

Route::get('/anao', [AnaoController::class, 'index']);

// for testing purposes (low time constraints will not invest on learning Pest)
Route::get('/test', [AnaoController::class, 'index']);
Route::get('/test/{id:\d+}', function (string $id) {
});
Route::post('/testPost', function () { });