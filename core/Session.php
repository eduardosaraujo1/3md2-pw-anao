<?php

namespace Core;

use Core\Abstract\Singleton;

class Session extends Singleton
{
    protected function __construct()
    {
        session_start();
    }

    public static function has(string $key): bool
    {
        return (bool) self::get($key);
    }

    public static function start(): Session
    {
        return Session::instance();
    }

    public static function get(string $key, mixed $default = ''): mixed
    {
        return $_SESSION['_flash'][$key] ?? $_SESSION[$key] ?? $default;
    }

    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function flash($key, $value)
    {
        $_SESSION['_flash'][$key] = $value;
    }

    public static function unflash()
    {
        unset($_SESSION['_flash']);
    }

    public static function destroy()
    {
        $_SESSION = [];

        session_destroy();
        session_start();

        // If using cookies for session, delete the session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name() ?: '',
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
    }
}