<?php

namespace App\Http\Middleware;

use App\Framework\Facades\Auth;
use App\Framework\Http\Response;

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