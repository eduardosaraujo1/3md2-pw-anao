<?php

namespace App\Framework\Facades;

use App\Framework\Database\Connection;

/**
 * @method static string compile(array<string> $resources)
 */
class Vite extends Facade
{
    private static object $_instance;
    protected static function getFacadeAccessor(): \App\Framework\Support\Vite
    {
        if (!isset(static::$_instance)) {
            static::$_instance = new \App\Framework\Support\Vite();
        }

        return static::$_instance;
    }
}