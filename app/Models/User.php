<?php

namespace App\Models;

use App\Framework\Database\Model;

class User extends Model {
    protected static string $table = "users";

    /**
     * Created new instance of class using specified data
     * @param array{login:string,password:string} $data
     * @return void
     */
    public static function create(array $data): User
    {
        if (!isset($data['login'], $data['password'])) {
            throw new \Exception("Missing required values");
        }

        // TODO: finish implementation
    }
}