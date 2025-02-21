<?php

namespace App\Framework\Auth;

use App\Framework\Auth\User;

class AuthSession
{
    private ?User $user;

    // singleton pattern
    private static self $_instance;

    private function __construct()
    {
    }

    public static function singleton(): self
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    // end singleton pattern

    // Auth::attempt($user, $password) -> check database for credentials($this->checkCredentials) and if they match create current session. Return status either success or failure
    public function attempt(): bool
    {
        return false;
    }
    // Auth::user() -> get current User model. Userful for retreiving name
    public function user(): User
    {
        return $this->user;
    }
    // Auth::check() -> get current User model. Userful for retreiving name
    public function check(): bool
    {
        return isset($this->user);
    }
    // Auth::logout() -> destroy current session
    public function logout()
    {
        session_destroy();
        $this->user = null;
    }
}