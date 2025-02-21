<?php

namespace App\Framework\Auth;

class User
{
    public function __construct(
        public int $id,
        public string $login,
        public string $password
    ) {
    }
}