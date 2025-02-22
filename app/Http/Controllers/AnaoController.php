<?php

namespace App\Http\Controllers;

use App\Framework\Http\Request;
use App\Framework\Http\Response;
use App\Models\Anao;

class AnaoController
{
    public function __construct()
    {
    }

    public static function index(Request $request): string
    {
        Anao::fromQuery("SELECT * FROM anao");
        return view('anao');
    }
}