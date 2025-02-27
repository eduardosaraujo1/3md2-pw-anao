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
        $anoes = Anao::fromQuery("SELECT * FROM anao");
        // dump(Anao::fromQuery("SELECT * FROM anao"));
        // dump(User::fromQuery("SELECT * FROM users"));
        // dump(Parceiro::fromQuery("SELECT * FROM parceiro"));
        return view('anao', [
            'anoes' => $anoes
        ]);
    }

    public static function show(): string
    {
        return view('partials.anao.view');
    }

    public static function edit(): string
    {
        return view('partials.anao.edit');
    }

    public static function update(): string
    {
        return '';
    }

    public static function create(): string
    {
        return '';
    }

    public static function store(): string
    {
        return '';
    }

    public static function destroy(): string
    {
        return '';
    }
}