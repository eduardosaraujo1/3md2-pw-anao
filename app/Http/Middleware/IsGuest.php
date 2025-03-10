<?php

namespace App\Http\Middleware;

use Core\Auth\Auth;
use Core\Http\Response;

class IsGuest
{
    public static function handle(callable $next, array $params): Response|string
    {
        if (Auth::instance()->check()) {
            return redirect('/anoes');
        }

        return $next(...$params);
    }
}