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

    public static function get(string $route, mixed $handler): void
    {
        $router = static::getInstance();

        // create the route
        $routeObject = new Route(
            httpMethod: 'GET',
            path: $route,
            handler: $handler,
        );

        // add the new route to route list
        array_push($router->routeList, $routeObject);
    }
}