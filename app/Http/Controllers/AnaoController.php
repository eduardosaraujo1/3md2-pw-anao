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
        Anao::fromQuery("SELECT * FROM anao WHERE id = :id", ['id' => 5]);
        return view('anao.view');
    }
}