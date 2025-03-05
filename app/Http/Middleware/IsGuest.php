<?php

namespace App\Http\Middleware;

use Core\Facades\Auth;
use Core\Http\Response;

class IsGuest
{
    public static function middleware(): Response|null
    {
        if (Auth::check()) {
            return redirect('/anoes');
        }

        return null;
    }
}