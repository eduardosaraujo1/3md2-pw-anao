<?php

namespace App\Http\Middleware;

use Core\Auth\Auth;
use Core\Http\Request;
use Core\Http\Response;

class IsAuth
{
    public static function handle(callable $next, array $params): Response|string
    {
        if (Auth::instance()->check()) {
            return $next(...$params);
        }

        return redirect('/login');
    }
}