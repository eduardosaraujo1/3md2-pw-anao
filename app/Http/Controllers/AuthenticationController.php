<?php

namespace App\Http\Controllers;

use App\Framework\Auth\User;
use App\Framework\Facades\Auth;
use App\Framework\Http\Request;
use App\Framework\Http\Response;

class AuthenticationController
{
    public function __construct()
    {
    }

    public static function index(Request $request): Response
    {
        return view('auth.login');
    }
}