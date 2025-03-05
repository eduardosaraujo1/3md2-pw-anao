<?php

namespace Core\Auth;

use Core\Database\Model;
use Core\Exceptions\NullPropertyException;

class User extends Model
{
    public function __construct(
        public int $id,
        public string $login,
        public string $password
    ) {
    }

    /**
     * Make instance from array of parameters
     * @param array<string,mixed> $params
     */
    public static function make(array $params): User
    {
        if (
            !isset(
            $params['id'],
            $params['login'],
            $params['password']
        )
        ) {
            throw new NullPropertyException("Missing property to make 'User': " . var_export($params, true));
        }
        return new User(
            id: $params['id'],
            login: $params['login'],
            password: $params['password'],
        );
    }
}