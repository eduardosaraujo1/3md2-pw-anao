<?php

namespace App\Http\Controllers;

use Core\Auth\Auth;
use Core\Auth\User;
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
        return view('auth.login');
    }

    public static function login(): string|Response
    {
        $request = Request::instance();

        // collect user data
        $userName = $request->postParams['user_login'] ?? '';
        $userPassword = $request->postParams['user_password'] ?? '';

        $auth = Auth::instance()->attempt($userName, $userPassword);

        return $auth
            ? redirect('/anoes')
            : 'Usuário não encontrado ou senha incorreta.';
    }

    public static function logout(): string|Response
    {
        Auth::instance()->logout();

        return redirect('/login');
    }
}