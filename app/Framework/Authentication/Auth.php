<?php

namespace App\Framework\Authentication;

class Auth
{
    // singleton pattern
    // Auth::logout() -> destroy current session
    // Auth::login($user, $password) -> check database for credentials and if they match create current session. Return status either success or failure
    // Auth::user() -> get current User model. Userful for retreiving name
}