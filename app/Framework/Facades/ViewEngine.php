<?php

namespace App\Framework\Facades;

use App\Framework\View\Engine;

/**
 * @method static string render(string $name, array $data = [])
 */
class ViewEngine extends Facade
{
    protected static function getFacadeAccessor(): Engine
    {
        return Engine::singleton();
    }
}