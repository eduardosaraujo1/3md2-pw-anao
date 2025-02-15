<?php

namespace App\Framework\Http;

readonly class Request
{
    public function __construct(
        private array $getParams,
        private array $postParams,
        private array $cookies,
        private array $files,
        private array $server,
    ) {
    }

    public static function createFromGlobals(): static
    {
        return new static($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
    }
}