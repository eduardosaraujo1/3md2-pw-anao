<?php

namespace App\Framework\Facades;

use App\Framework\Database\Connection;

/**
 * @method static int query(string $query, array $params = [])
 * @method static array<object> fetch(string $query, array $params = [])
 * @method static bool transaction(callable $callback)
 * @method static void close()
 */
class DB extends Facade {
    protected static function getFacadeAccessor(): Connection {
        return Connection::singleton();
    }
}