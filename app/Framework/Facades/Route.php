<?php

namespace App\Framework\Facades;

use App\Framework\Routing\Router;
use App\Framework\Classes\DispatchResult;
use App\Framework\Routing\Route as RouteDTO;

/**
 * @method static DispatchResult dispatch(string $method, string $uri)
 * @method static RouteDTO get(string $route, callable $handler)
 * @method static RouteDTO post(string $route, callable $handler)
 * @method static RouteDTO put(string $route, callable $handler)
 * @method static RouteDTO patch(string $route, callable $handler)
 * @method static RouteDTO delete(string $route, callable $handler)
 */
class Route extends Facade
{
    protected static function getFacadeAccessor(): Router
    {
        return Router::singleton();
    }
}