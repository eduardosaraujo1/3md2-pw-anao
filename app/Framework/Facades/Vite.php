<?php

namespace App\Framework\Facades;

use App\Framework\Database\Connection;

/**
 * @method static string compile(array<string> $resources)
 */
class Vite extends Facade
{
    protected static function getFacadeAccessor(): \App\Framework\Support\Vite
    {
        return \App\Framework\Support\Vite::singleton(); // TODO: move singleton responsability to facade (not sure why I didn't do that earlier)
    }
}