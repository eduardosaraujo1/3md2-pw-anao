<?php

namespace App\Framework\Facades;

use App\Framework\Database\Connection;

/**
 * @method static int query(string $query, array<string,mixed> $params = [])
 * @method static array<array<string,mixed>> fetch(string $query, array<string,mixed> $params = [])
 * @method static bool transaction(callable $callback)
 * @method static void exec(string $query)
 * @method static void close()
 * @method static PDO|null getPDO()
 */
class DB extends Facade
{
    private static object $_instance;
    protected static function getFacadeAccessor(): Connection
    {
        if (!isset(static::$_instance)) {
            static::$_instance = Connection::createFromEnv(true);
        }

        return static::$_instance;
    }
}