<?php

namespace App\Framework\Facades;

abstract class Facade
{
    protected static function getFacadeAccessor(): object
    {
        throw new \Exception("Facade accessor not defined", 1);
    }

    public static function __callStatic(string $method, array $args)
    {
        $accessor = static::getFacadeAccessor();

        if (!$accessor) {
            throw new \RuntimeException("No instance found for " . static::class);
        }

        if (!method_exists($accessor, $method)) {
            throw new \BadMethodCallException("The method '$method' does not exist on " . get_class($accessor));
        }

        return $accessor->$method(...$args);
    }
}
