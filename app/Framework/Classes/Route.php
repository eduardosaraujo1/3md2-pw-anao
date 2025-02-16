<?php

namespace App\Framework\Classes;

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
}