<?php

namespace Core\Facades;

use Core\Routing\Router;
use Core\Routing\RouteDispatchResult;
use Core\Routing\Route as RouteDTO;

/**
 * @method static RouteDispatchResult dispatch(string $method, string $uri)
 * @method static RouteDTO get(string $route, callable $handler)
 * @method static RouteDTO post(string $route, callable $handler)
 * @method static RouteDTO put(string $route, callable $handler)
 * @method static RouteDTO patch(string $route, callable $handler)
 * @method static RouteDTO delete(string $route, callable $handler)
 */
class Route extends Facade
{
    private static object $_instance;
    protected static function getFacadeAccessor(): Router
    {
        if (!isset(static::$_instance)) {
            static::$_instance = new Router();
        }

        return static::$_instance;
    }
}