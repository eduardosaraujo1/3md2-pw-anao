<?php

namespace App\Http\Controllers;

use App\Framework\Http\Request;
use App\Models\Anao;

class AnaoController
{
    public function __construct()
    {
    }

    public static function index(Request $request): string
    {
        dd(Anao::all());
        return view('anao.view');
    }
}