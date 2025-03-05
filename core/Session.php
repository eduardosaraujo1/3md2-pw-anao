<?php

namespace Core;

use Core\Abstract\Singleton;

class Session extends Singleton
{
    protected function __construct()
    {
        session_start();
    }

    public static function start(): Session
    {
        return Session::instance();
    }

    public static function get(string $key, string $default = ''): string
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function set(string $key, string $value): void
    {
        $_SESSION[$key] = $value;
    }
}