<?php

namespace Core\Routing;

use Core\Exception\Routing\RouteMethodNotAllowedException;
use Core\Exception\Routing\RouteNotFoundException;
use Core\Http\Response;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

class Router
{
    /** @var Route[] */
    private array $routeList = [];

    public function __construct()
    {
        require_once base_path('routes/web.php');
    }

    public function dispatch(string $method, string $uri): array
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $r) {
            // routeList is defined in the constructor indirectly, web.php calls methods to push items to the routeList.
            foreach ($this->routeList as $route) {
                $r->addRoute(
                    httpMethod: $route->httpMethod,
                    route: $route->path,
                    handler: $route->handler
                );
            }
        });

        return $dispatcher->dispatch($method, $uri);
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