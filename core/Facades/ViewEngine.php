<?php

namespace Core\Facades;

use Core\View\Engine;

/**
 * @method static string render(string $name, array<string,mixed> $data = [])
 */
class ViewEngine extends Facade
{
    private static object $_instance;

    protected static function getFacadeAccessor(): Engine
    {
        if (!isset(static::$_instance)) {
            static::$_instance = new Engine();
        }

        return static::$_instance;
    }
}