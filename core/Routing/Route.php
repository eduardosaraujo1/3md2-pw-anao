<?php

namespace Core\Routing;

class Route
{
    public string $path;
    public string $httpMethod;
    public mixed $handler;
    public array $middlewares = [];

    public function __construct(
        string $path,
        string $httpMethod,
        mixed $handler
    ) {
        $this->httpMethod = strtoupper($httpMethod);
        $this->path = $path;
        $this->handler = $handler;
    }

    public function middleware(callable|string|array $middleware): static
    {
        if (is_string($middleware)) {
            if (!class_exists($middleware)) {
                throw new \Exception("Middleware class $middleware does not exist.");
            }

            if (!method_exists($middleware, 'handle')) {
                throw new \Exception("Middleware class $middleware must have a handle() method.");
            }

            $middleware = [new $middleware, 'handle'];
        }

        if (!is_callable($middleware)) {
            throw new \Exception("Invalid middleware: must be a class with a handle() method, a callable, or a closure.");
        }

        // call middlewares along side handlers (middleware should support two params: $callback, $args)
        $previousHandler = $this->handler;
        $this->handler = fn(...$args) => call_user_func_array($middleware, [$previousHandler, $args]);

        return $this;
    }
}