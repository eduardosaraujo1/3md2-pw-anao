<?php

use App\Http\Controllers\AnaoController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ParceiroController;
use App\Http\Middleware\IsAuth;
use App\Http\Middleware\IsGuest;
use Core\Auth\Auth;
use Core\Routing\Router;

if (!$this instanceof Router) {
    throw new Exception('web.php file was not required within the Router.php context');
}

$this->get('/', function () {
    if (Auth::instance()->check()) {
        return redirect('/anoes');
    }
    return redirect('/login');
});

$this->get('/login', [AuthenticationController::class, 'index'])->middleware(IsGuest::class);

$this->post('/login', [AuthenticationController::class, 'login'])->middleware(IsGuest::class);
$this->get('/logout', [AuthenticationController::class, 'logout']);

$this->get('/home', function () {
    return redirect('/anoes');
});

$this->get('/anoes', [AnaoController::class, 'index'])->middleware(IsAuth::class);
$this->get('/anao/create', [AnaoController::class, 'create'])->middleware(IsAuth::class);
$this->get('/anao/{id:\d+}', [AnaoController::class, 'show'])->middleware(IsAuth::class);

$this->post('/anao/update/{id:\d+}', [AnaoController::class, 'update'])->middleware(IsAuth::class);
$this->post('/anao/store', [AnaoController::class, 'store'])->middleware(IsAuth::class);
$this->post('/anao/destroy/{id:\d+}', [AnaoController::class, 'destroy'])->middleware(IsAuth::class);

$this->get('/parceiro/{id:\d+}', [ParceiroController::class, 'show'])->middleware(IsAuth::class);
$this->get('/parceiro/create', [ParceiroController::class, 'create'])->middleware(IsAuth::class);

$this->post('/parceiro/update/{id:\d+}', [ParceiroController::class, 'update'])->middleware(IsAuth::class);
$this->post('/parceiro/store', [ParceiroController::class, 'store'])->middleware(IsAuth::class);
$this->post('/parceiro/destroy/{id:\d+}', [ParceiroController::class, 'destroy'])->middleware(IsAuth::class);