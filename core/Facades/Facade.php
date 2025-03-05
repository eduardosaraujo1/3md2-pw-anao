<?php

namespace Core\Facades;

abstract class Facade
{
    protected static function getFacadeAccessor(): object
    {
        throw new \Exception("Facade accessor not defined", 1);
    }

    /**
     * @param string $method
     * @param array<mixed> $args
     * @throws \RuntimeException
     * @throws \BadMethodCallException
     * @return mixed
     */
    public static function __callStatic(string $method, array $args): mixed
    {
        $accessor = static::getFacadeAccessor();

        if (!method_exists($accessor, $method)) {
            throw new \BadMethodCallException("The method '$method' does not exist on " . get_class($accessor));
        }

        return $accessor->$method(...$args);
    }
}
