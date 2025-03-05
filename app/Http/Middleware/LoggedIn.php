<?php

namespace App\Http\Middleware;

use Core\Facades\Auth;
use Core\Http\Response;

class LoggedIn
{
    public static function middleware(): Response|null
    {
        if (Auth::check()) {
            return null;
        }

        return redirect('/login');
    }
}