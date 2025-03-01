<?php

namespace App\Http\Controllers;

use App\Framework\Auth\User;
use App\Framework\Facades\Auth;
use App\Framework\Http\Request;
use App\Framework\Http\Response;
use App\Http\Middleware\IsGuest;

class AuthenticationController
{
    public function __construct()
    {
    }

    public static function index(Request $request): string|Response
    {
        if ($middleware = IsGuest::middleware())
            return $middleware;

        return view('auth.login');
    }

    public static function login(Request $request): string|Response
    {
        // collect user data
        $userName = $request->postParams['user_login'] ?? '';
        $userPassword = $request->postParams['user_password'] ?? '';

        $auth = Auth::attempt($userName, $userPassword);

        return $auth
            ? redirect('/anoes')
            : <<<HTML
                <h3 class="text-sm text-red-700">Usuário não encontrado ou senha incorreta.</h3>
                HTML;
    }

    public static function logout(): string|Response
    {
        Auth::logout();

        return redirect('/login');
    }
}