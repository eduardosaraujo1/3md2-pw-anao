<?php

namespace App\Framework\Facades;

use App\Framework\Auth\AuthSession;
use App\Framework\Auth\User;

/**
 * @method static bool attempt(string $login, string $password)
 * @method static bool check()
 * @method static User user()
 * @method static void logout()
 * @method static void forceLogin(User $user)
 */
class Auth extends Facade
{
    protected static function getFacadeAccessor(): AuthSession
    {
        return AuthSession::singleton();
    }
}