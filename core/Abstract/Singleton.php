<?php

namespace Core\Abstract;

abstract class Singleton
{
    /**
     * @var array<mixed>
     */
    private static array $_instances = [];

    protected function __construct()
    {
    }

    protected static function createInstance(): static
    {
        return new static();
    }

    public static function instance(): static
    {
        $class = static::class;

        if (!isset(self::$_instances[$class])) {
            self::$_instances[$class] = static::createInstance();
        }

        return self::$_instances[$class];
    }
}