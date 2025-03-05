<?php

use Core\Facades\Auth;
use Core\Http\Request;
use App\Http\Controllers\AnaoController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ParceiroController;
use Core\Routing\Router;

if (!$this instanceof Router) {
    throw new Exception('web.php file was not required within the Router.php context');
}

$this->get('/', function (Request $request) {
    if (Auth::check()) {
        return redirect('/anoes');
    }
    return redirect('/login');
});

$this->get('/login', [AuthenticationController::class, 'index']);

$this->post('/login', [AuthenticationController::class, 'login']);
$this->get('/logout', [AuthenticationController::class, 'logout']);

$this->get('/home', function () {
    return redirect('/anoes');
});

$this->get('/anoes', [AnaoController::class, 'index']);
$this->get('/anao/create', [AnaoController::class, 'create']);
$this->get('/anao/{id:\d+}', [AnaoController::class, 'show']);

$this->post('/anao/update/{id:\d+}', [AnaoController::class, 'update']);
$this->post('/anao/store', [AnaoController::class, 'store']);

$this->get('/parceiro/{id:\d+}', [ParceiroController::class, 'show']);
$this->get('/parceiro/create', [ParceiroController::class, 'create']);

$this->post('/parceiro/update/{id:\d+}', [ParceiroController::class, 'update']);
$this->post('/parceiro/store', [ParceiroController::class, 'store']);
$this->post('/parceiro/destroy/{id:\d+}', [ParceiroController::class, 'destroy']);