<?php

namespace Core\Facades;

use Core\Database\Connection;

/**
 * @method static string compile(array<string> $resources)
 */
class Vite extends Facade
{
    private static object $_instance;
    protected static function getFacadeAccessor(): \Core\Vite
    {
        if (!isset(static::$_instance)) {
            static::$_instance = new \Core\Vite();
        }

        return static::$_instance;
    }
}