<?php

namespace App\Framework\Http;

readonly class Request
{

    public function __construct(
        public array $getParams,
        public array $postParams,
        public array $cookies,
        public array $files,
        public array $server,
    ) {
    }

    public function getPathInfo(): string
    {
        return strtok($this->server['REQUEST_URI'], '?');
    }

    public function getMethod(): string
    {
        return strtoupper($this->server['REQUEST_METHOD']);
    }

    public static function createFromGlobals(): static
    {
        return new static($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
    }
}