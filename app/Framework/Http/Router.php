<?php

namespace App\Framework\Http;

use App\Framework\Classes\Route;

class Router
{
    /** @var array<Route> */
    private array $routeList = [];

    // singleton pattern
    private static ?Router $_instance = null;
    private function __construct()
    {
    }
    private static function createNewRouter()
    {
        // Define router instance to the getter
        self::$_instance = new Router();

        // Load the routes specified in routes/web.php
        require_once dirname(__DIR__) . '../../../routes/web.php';
    }
    private static function getInstance(): Router
    {
        if (!isset(self::$_instance)) {
            static::createNewRouter();
        }
        return self::$_instance;
    }
    // end singleton pattern

    /**
     * Gets the routes registered to the router
     * @return Route[]
     */
    public static function getRoutes(): array
    {
        return static::getInstance()->routeList;
    }

    private function addRoute(string $method, string $path, mixed $handler)
    {

        // create the route
        $routeObject = new Route(
            httpMethod: $method,
            path: $path,
            handler: $handler,
        );

        // add the new route to route list
        array_push($this->routeList, $routeObject);
    }

    public static function get(string $route, mixed $handler): void
    {
        $router = static::getInstance();
        $router->addRoute('GET', $route, $handler);
    }

    public static function post(string $route, mixed $handler): void
    {
        $router = static::getInstance();
        $router->addRoute('POST', $route, $handler);
    }

    public static function put(string $route, mixed $handler): void
    {
        $router = static::getInstance();
        $router->addRoute('PUT', $route, $handler);
    }
    public static function delete(string $route, mixed $handler): void
    {
        $router = static::getInstance();
        $router->addRoute('DELETE', $route, $handler);
    }
    public static function patch(string $route, mixed $handler): void
    {
        $router = static::getInstance();
        $router->addRoute('PATCH', $route, $handler);
    }
}