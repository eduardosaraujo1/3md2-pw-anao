<?php

namespace App\Framework\Http;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

class Kernel
{
    public function __construct()
    {
    }

    private function makeDispatcher()
    {
        return simpleDispatcher(function (RouteCollector $r) {
            // get route list
            $routes = Router::getRoutes();

            // add each route to the dispatcher
            foreach ($routes as $route) {
                $r->addRoute(
                    httpMethod: $route->httpMethod,
                    route: $route->path,
                    handler: $route->handler
                );
            }
        });
    }

    private function callHandler(mixed $handler, array $params, Request $request)
    {
        // Reflection API: used to read the arguments a function has specified and using that to decide which params should be passed
        // pretty much replicates Laravel's Service Provider pattern
        // (W.I.P) for now just throw an error if the param count was incorrect
        try {
            $result = call_user_func_array($handler, [$request, ...$params]);
            return new Response($result);
        } catch (\ArgumentCountError $e) {
            return new Response(
                content: '
                    <h3>Ocorreu um erro crítico, contate seu administrador.</h3>
                    <b>Descrição:</b> The controller method to this route was not built to receive the route parameters and the request.
                ',
                status: 500,
            );
        }
    }

    public function handle(Request $request)
    {
        // make route dispatcher (basically a callback resolver)
        $dispatcher = $this->makeDispatcher();

        // get request route and method
        $method = $request->getMethod();
        $path = $request->getPathInfo();

        // get route status, callback and extra return variables
        [$status, $handler, $routeParams] = $dispatcher->dispatch($method, $path);

        if ($status === Dispatcher::FOUND && isset($handler)) {
            return $this->callHandler($handler, $routeParams, $request);
        }
    }
}
