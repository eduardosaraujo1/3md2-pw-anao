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
        return '<h1>Hello from the login page. I am not a template yet sadly :(</h1>';
    }
}