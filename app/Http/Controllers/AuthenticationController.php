<?php

namespace App\Http\Controllers;

use Core\Auth\Auth;
use Core\Auth\User;
use Core\Http\Request;
use Core\Http\Response;
use App\Http\Middleware\IsGuest;
use Core\Session;

class AuthenticationController
{
    public function __construct()
    {
    }

    public static function index(): string|Response
    {
        return view('auth.login', [
            'errors' => Session::get('errors', [])
        ]);
    }

    public static function login(): string|Response
    {
        $request = Request::instance();

        // attempt login
        $userName = $request->postParams['user_login'] ?? '';
        $userPassword = $request->postParams['user_password'] ?? '';
        $auth = Auth::instance()->attempt($userName, $userPassword);

        // TODO: flash error message to session, and redirect to /login
        if (!$auth) {
            Session::flash('errors', ['Usuário não encontrado ou senha incorreta.']);
            return redirect('/login');
        }

        return redirect('/anoes');
    }

    public static function logout(): string|Response
    {
        Auth::instance()->logout();

        return redirect('/login');
    }
}