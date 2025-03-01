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
        return view('anao.index', [
            'anoes' => $anoes
        ]);
    }

    public static function show(string $id): string
    {
        return view('anao.view');
    }

    public static function edit(string $id): string
    {
        return view('anao.edit');
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

    public static function destroy(string $id): string
    {
        return '';
    }
}