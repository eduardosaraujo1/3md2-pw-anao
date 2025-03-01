<?php

namespace App\Http\Middleware;

use App\Framework\Facades\Auth;
use App\Framework\Http\Response;

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