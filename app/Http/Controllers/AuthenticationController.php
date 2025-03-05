<?php

namespace App\Http\Controllers;

use Core\Auth\User;
use Core\Facades\Auth;
use Core\Http\Request;
use Core\Http\Response;
use App\Http\Middleware\IsGuest;

class AuthenticationController
{
    public function __construct()
    {
    }

    public static function index(): string|Response
    {
        if ($middleware = IsGuest::middleware())
            return $middleware;

        return view('auth.login');
    }

    public static function login(): string|Response
    {
        $request = Request::instance();

        // collect user data
        $userName = $request->postParams['user_login'] ?? '';
        $userPassword = $request->postParams['user_password'] ?? '';

        $auth = Auth::attempt($userName, $userPassword);

        return $auth
            ? redirect('/anoes')
            : 'Usuário não encontrado ou senha incorreta.';
    }

    public static function logout(): string|Response
    {
        Auth::logout();

        return redirect('/login');
    }
}