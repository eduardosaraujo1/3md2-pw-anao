<?php

namespace App\Framework\Http;

readonly class Request
{

    public function __construct(
        /** @var array<mixed> */
        public array $getParams,
        /** @var array<mixed> */
        public array $postParams,
        /** @var array<mixed> */
        public array $cookies,
        /** @var array<mixed> */
        public array $files,
        /** @var array<mixed> */
        public array $server,
    ) {
    }

    public function getPathInfo(): string
    {
        return strtok($this->server['REQUEST_URI'] ?? '', '?') ?: '';
    }

    public function getMethod(): string
    {
        return strtoupper($this->server['REQUEST_METHOD'] ?? '');
    }

    public static function createFromGlobals(): self
    {
        return new self($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
    }
}