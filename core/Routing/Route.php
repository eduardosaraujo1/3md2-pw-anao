<?php

namespace Core\Routing;

class Route
{
    public string $path;
    public string $httpMethod;
    public mixed $handler;
    /** @var array<callable> */
    public array $middlewares;

    public function __construct(
        string $path,
        string $httpMethod,
        mixed $handler
    ) {
        $this->httpMethod = strtoupper($httpMethod);
        $this->path = $path;
        $this->handler = $handler;
    }
}