<?php

namespace App\Framework\Facades;

use App\Framework\Database\Connection;

/**
 * @method static int query(string $query, array<string,mixed> $params = [])
 * @method static array<array<string,mixed>> fetch(string $query, array<string,mixed> $params = [])
 * @method static bool transaction(callable $callback)
 * @method static void exec(string $query)
 * @method static void close()
 */
class DB extends Facade
{
    protected static function getFacadeAccessor(): Connection
    {
        return Connection::singleton();
    }
}