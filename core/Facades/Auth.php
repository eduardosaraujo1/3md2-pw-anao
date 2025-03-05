<?php

namespace Core\Facades;

use Core\Auth\Authentication;
use Core\Auth\User;

/**
 * @method static bool attempt(string $login, string $password)
 * @method static bool check()
 * @method static User user()
 * @method static void logout()
 * @method static void forceLogin(User $user)
 */
class Auth extends Facade
{
    private static object $_instance;
    protected static function getFacadeAccessor(): Authentication
    {
        if (!isset(static::$_instance)) {
            static::$_instance = new Authentication();
        }

        return static::$_instance;
    }
}