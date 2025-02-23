<?php

namespace App\Http\Controllers;

use App\Models\Parceiro;
use App\Models\User;
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
        // dump(Anao::fromQuery("SELECT * FROM anao"));
        // dump(User::fromQuery("SELECT * FROM users"));
        // dump(Parceiro::fromQuery("SELECT * FROM parceiro"));
        return view('anao');
    }

    public static function show()
    {
        return view('partials.anao.view');
    }

    public static function edit()
    {
        return view('partials.anao.edit');
    }
}