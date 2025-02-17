<?php

namespace App\Framework\Core\Factory;

use App\Framework\Core\Classes\Route;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

class DispatcherFactory
{
    /**
     * @param Route[] $routes
     */
    public static function make(array $routes)
    {
        return simpleDispatcher(function (RouteCollector $r) use ($routes) {
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
}