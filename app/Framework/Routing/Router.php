<?php

namespace App\Framework\Routing;

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

class Router
{
    /** @var Route[] */
    private array $routeList = [];

    public function __construct()
    {
    }

    private function addRoute(string $method, string $path, callable $handler): Route
    {
        $routeObject = new Route(
            httpMethod: $method,
            path: $path,
            handler: $handler,
        );

        $this->routeList[] = $routeObject;

        return $routeObject;
    }

    public function dispatch(string $method, string $uri): RouteDispatchResult
    {
        // Load the routes specified in routes/web.php
        require_once realpath(__DIR__ . '/../../../routes/web.php');

        $dispatcher = simpleDispatcher(function (RouteCollector $r) {
            foreach ($this->routeList as $route) {
                $r->addRoute(
                    httpMethod: $route->httpMethod,
                    route: $route->path,
                    handler: $route->handler
                );
            }
        });

        /** @var array{int,callable,array<string,string>}|array{int,array<string>}|array{int} $rawResult */
        $rawResult = $dispatcher->dispatch($method, $uri);
        $result = RouteDispatchResult::createFromFastRoute($rawResult);

        return $result;
    }

    public function get(string $route, callable $handler): Route
    {
        return $this->addRoute('GET', $route, $handler);
    }

    public function post(string $route, callable $handler): Route
    {
        return $this->addRoute('POST', $route, $handler);
    }

    public function put(string $route, callable $handler): Route
    {
        return $this->addRoute('PUT', $route, $handler);
    }
    public function delete(string $route, callable $handler): Route
    {
        return $this->addRoute('DELETE', $route, $handler);
    }
    public function patch(string $route, callable $handler): Route
    {
        return $this->addRoute('PATCH', $route, $handler);
    }
}