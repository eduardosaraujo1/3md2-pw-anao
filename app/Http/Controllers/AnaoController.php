<?php

namespace App\Http\Controllers;

use App\Framework\Http\Request;

class AnaoController
{
    public function __construct()
    {
    }

    public static function index(Request $request)
    {
        return '<h1>Hello from the AnaoController! I am still not a template :(</h1>';
    }
}