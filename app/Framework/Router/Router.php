<?php

namespace App\Framework\Router;

use App\Framework\Classes\Route;

class Router
{
    /** @var Route[] */
    private array $routeList = [];

    // singleton pattern
    private static ?Router $_instance = null;
    private function __construct()
    {
    }
    private static function getInstance(): Router
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new Router();
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
        return static::getInstance()
            ->addRoute('GET', $route, $handler);
    }

    public static function post(string $route, mixed $handler): Route
    {
        return static::getInstance()
            ->addRoute('POST', $route, $handler);
    }

    public static function put(string $route, mixed $handler): Route
    {
        return static::getInstance()
            ->addRoute('PUT', $route, $handler);
    }
    public static function delete(string $route, mixed $handler): Route
    {
        return static::getInstance()
            ->addRoute('DELETE', $route, $handler);
    }
    public static function patch(string $route, mixed $handler): Route
    {
        return static::getInstance()
            ->addRoute('PATCH', $route, $handler);
    }
}