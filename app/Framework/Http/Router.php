<?php

namespace App\Framework\Http;

use App\Framework\Core\Classes\Route;

class Router
{
    /** @var Route[] */
    private array $routeList = [];

    // singleton pattern
    private static ?Router $_instance = null;
    private function __construct()
    {
    }
    private static function singleton(): Router
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new Router();

            // Load the routes specified in routes/web.php
            require_once realpath(__DIR__ . '/../../../routes/web.php');
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
        return static::singleton()->routeList;
    }

    private function addRoute(string $method, string $path, mixed $handler): Route
    {
        // create the route
        $routeObject = new Route(
            httpMethod: $method,
            path: $path,
            handler: $handler,
        );

        // add the new route to route list
        array_push($this->routeList, $routeObject);

        return $routeObject;
    }

    public static function get(string $route, mixed $handler): Route
    {
        return static::singleton()
            ->addRoute('GET', $route, $handler);
    }

    public static function post(string $route, mixed $handler): Route
    {
        return static::singleton()
            ->addRoute('POST', $route, $handler);
    }

    public static function put(string $route, mixed $handler): Route
    {
        return static::singleton()
            ->addRoute('PUT', $route, $handler);
    }
    public static function delete(string $route, mixed $handler): Route
    {
        return static::singleton()
            ->addRoute('DELETE', $route, $handler);
    }
    public static function patch(string $route, mixed $handler): Route
    {
        return static::singleton()
            ->addRoute('PATCH', $route, $handler);
    }
}