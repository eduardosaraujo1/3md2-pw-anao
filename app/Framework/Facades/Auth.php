<?php

namespace App\Framework\Facades;

use App\Framework\Auth\AuthSession;

/**
 * @method static int query(string $query, array $params = [])
 * @method static array<object> fetch(string $query, array $params = [])
 * @method static bool transaction(callable $callback)
 * @method static void exec(string $query)
 * @method static void close()
 */
class Auth extends Facade
{
    protected static function getFacadeAccessor(): AuthSession
    {
        return AuthSession::singleton();
    }
}