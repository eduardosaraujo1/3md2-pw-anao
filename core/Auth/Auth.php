<?php

namespace Core\Auth;

use Core\Abstract\Singleton;
use Core\Auth\User;
use Core\Session;

class Auth extends Singleton
{
    public const USER_KEY = 'user_id';
    public function attempt(string $login, string $password): bool
    {
        // get user by name
        $user = User::fromQuery('SELECT * FROM users WHERE login=:login', ['login' => $login])[0] ?? null;

        // if the user does not exist, return false
        if (!$user) {
            return false;
        }

        // since user is set, check passwords
        $hash = $user->password;
        $password_checks = password_verify($password, $hash);

        // if they're valid, then login and return true, otherwise return false
        if ($password_checks) {
            $this->login($user->id);
        }

        return $password_checks;
    }

    public function user(): ?User
    {
        $storedId = Session::get(static::USER_KEY);

        return User::fromQuery('SELECT * FROM users WHERE id=:id', ['id' => $storedId])[0] ?? null;
    }

    public function check(): bool
    {
        return Session::has(static::USER_KEY);
    }

    public function logout(): void
    {
        Session::destroy();
    }

    public function forceLogin(int $id): void
    {
        $this->login($id);
    }

    private function login(int $id): void
    {
        // regenerate ID to prevent session fixation (https://en.wikipedia.org/wiki/Session_fixation)
        session_regenerate_id();

        // reset session id
        Session::set(static::USER_KEY, $id);
    }
}