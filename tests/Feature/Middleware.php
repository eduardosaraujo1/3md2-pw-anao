<?php

use Core\Routing\Router;

class MockMiddleware
{
    public function handle(callable $next)
    {
        global $middlewareExecuted;
        $middlewareExecuted = true;
        return $next();
    }

    public function handleWithoutNext(callable $next)
    {
        global $middlewareSkipped;
        $middlewareSkipped = true;
        // does not call $next(), preventing the callback from executing.
    }
}

test('middleware executes before route callback', function () {
    $router = Router::instance();

    global $middlewareExecuted, $callbackExecuted, $middlewareSkipped, $callbackSkipped;
    $middlewareExecuted = false;
    $callbackExecuted = false;
    $middlewareSkipped = false;
    $callbackSkipped = false;

    // route with middleware that allows execution
    $router->get('TEST_MIDDLEWARE_EXECUTES', function () {
        global $callbackExecuted;
        $callbackExecuted = true;
    })->middleware(MockMiddleware::class);

    // route with middleware that prevents execution
    $router->get('TEST_MIDDLEWARE_SKIPS', function () {
        global $callbackSkipped;
        $callbackSkipped = true;
    })->middleware([new MockMiddleware(), 'handleWithoutNext']);

    // dispatch first request (middleware calls $next)
    [$callback, $params] = $router->dispatch('GET', 'TEST_MIDDLEWARE_EXECUTES');
    call_user_func_array($callback, $params);

    // dispatch second request (middleware does NOT call $next)
    [$callback, $params] = $router->dispatch('GET', 'TEST_MIDDLEWARE_SKIPS');
    call_user_func_array($callback, $params);

    // assertions
    expect($middlewareExecuted)->toBeTrue();  // middleware should run
    expect($callbackExecuted)->toBeTrue();    // callback should run since $next() was called

    expect($middlewareSkipped)->toBeTrue();   // middleware should run
    expect($callbackSkipped)->toBeFalse();    // callback should NOT run since $next() was not called
});