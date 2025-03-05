<?php

namespace Core\Routing;

use function PHPUnit\Framework\throwException;

class Route
{
    public string $path;
    public string $httpMethod;
    public mixed $handler;

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

        // wrap existing handler with middleware
        $previousHandler = $this->handler;
        $this->handler = fn() => call_user_func($middleware, $previousHandler);

        return $this;
    }
}