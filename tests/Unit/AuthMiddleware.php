<?php

use App\Http\Middleware\IsAuth;
use App\Http\Middleware\IsGuest;
use App\Models\User;
use Core\Auth\Auth;
use Core\Routing\Router;

test('if auth middleware rejects guests and guest middleware allows guests', function () {
    global $guestRan, $authRan;
    $authRan = false;
    $guestRan = false;

    $auth = Auth::instance();
    $router = Router::instance();

    $auth->logout();

    $router->get('AUTH_TEST', function () {
        global $authRan;

        $authRan = true;
    })->middleware(IsAuth::class);

    $router->get('GUEST_TEST', function () {
        global $guestRan;

        $guestRan = true;
    })->middleware(IsGuest::class);

    [$authHandler] = $router->dispatch('GET', 'AUTH_TEST');
    $authHandler();

    [$guestHandler] = $router->dispatch('GET', 'GUEST_TEST');
    $guestHandler();

    expect($auth->check())->toBeFalse();
    expect($authRan)->toBeFalse();
    expect($guestRan)->toBeTrue();
});

test('if guest middleware accepts guests and auth middleware rejects guests', function () {
    global $guestRan, $authRan;
    $authRan = false;
    $guestRan = false;

    $auth = Auth::instance();
    $router = Router::instance();

    $auth->forceLogin(1);

    $router->get('AUTH_TEST', function () {
        global $authRan;

        $authRan = true;
    })->middleware(IsAuth::class);

    $router->get('GUEST_TEST', function () {
        global $guestRan;

        $guestRan = true;
    })->middleware(IsGuest::class);

    [$authHandler] = $router->dispatch('GET', 'AUTH_TEST');
    $authHandler();

    [$guestHandler] = $router->dispatch('GET', 'GUEST_TEST');
    $guestHandler();

    expect($auth->check())->toBeTrue();
    expect($authRan)->toBeTrue();
    expect($guestRan)->toBeFalse();
});