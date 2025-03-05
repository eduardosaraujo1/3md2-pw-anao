<?php

namespace Core\Routing;

use Core\Abstract\Singleton;
use Core\Exceptions\Routing\RouteMethodNotAllowedException;
use Core\Exceptions\Routing\RouteNotFoundException;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

class Router extends Singleton
{
    /** @var Route[] */
    private array $routeList = [];

    protected function __construct()
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

        $result = $dispatcher->dispatch($method, $uri);

        if ($result[0] === Dispatcher::METHOD_NOT_ALLOWED) {
            throw new RouteMethodNotAllowedException(implode(', ', $result[1]));
        }

        if ($result[0] === Dispatcher::NOT_FOUND) {
            throw new RouteNotFoundException();
        }

        $handler = $result[1];
        $params = $result[2];

        return [$handler, $params];
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