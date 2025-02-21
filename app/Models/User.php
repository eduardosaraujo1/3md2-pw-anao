<?php

namespace App\Models;

class User extends \App\Framework\Auth\User
{
    public function __construct(
        public int $id,
        public string $login,
        public string $password,
    ) {
    }

    /**
     * Make instance from array of parameters
     * @param array<string,mixed> $params
     * @return self
     */
    public static function make(array $params): User
    {
        return new User(
            id: $params['id'],
            login: $params['login'],
            password: $params['password'],
        );
    }
}