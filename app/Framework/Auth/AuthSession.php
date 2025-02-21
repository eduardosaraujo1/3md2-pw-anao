<?php

namespace App\Framework\Auth;

use App\Framework\Auth\User;

class AuthSession
{
    private ?User $user = null;

    // singleton pattern
    private static self $_instance;

    private function __construct()
    {
        $storedId = $_SESSION['user_id'] ?? null;
        if (!isset($storedId)) {
            return;
        }

        $this->user = User::fromQuery('SELECT * FROM users WHERE id=:id', ['id' => $storedId])[0];
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
            $this->login($user);
        }

        return $password_checks;
    }
    // Auth::user() -> get current User model. Userful for retreiving name
    public function user(): ?User
    {
        return $this->user;
    }
    // Auth::check() -> get current User model. Userful for retreiving name
    public function check(): bool
    {
        return isset($this->user);
    }
    // Auth::logout() -> destroy current session
    public function logout(): void
    {
        session_destroy();
        session_start();

        // If using cookies for session, delete the session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        $this->user = null;
    }

    public function forceLogin(User $user): void
    {
        $this->login($user);
    }

    private function login(User $user): void
    {
        // regenerate ID to prevent session fixation (https://en.wikipedia.org/wiki/Session_fixation)
        session_regenerate_id();

        // reset session id
        $_SESSION['user_id'] = $user->id;

        // redefine locally defined user
        $this->user = $user;
    }
}