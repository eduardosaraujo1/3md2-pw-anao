<?php

namespace App\Http\Controllers;

use App\Framework\Http\Request;

class AuthenticationController
{
    public function __construct()
    {
    }

    public static function index(Request $request)
    {
        return view('auth.login');
    }
}