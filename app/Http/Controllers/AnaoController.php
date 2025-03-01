<?php

namespace App\Http\Controllers;

use App\Http\Middleware\LoggedIn;
use App\Framework\Http\Request;
use App\Framework\Http\Response;
use App\Models\Anao;

class AnaoController
{
    public function __construct()
    {
    }

    public static function index(Request $request): Response|string
    {
        if ($middleware = LoggedIn::middleware()) {
            return $middleware;
        }

        $anoes = Anao::fromQuery("SELECT * FROM anao");

        return view('anao.index', [
            'anoes' => $anoes
        ]);
    }

    public static function show(string $id): Response|string
    {
        if ($middleware = LoggedIn::middleware()) {
            return $middleware;
        }

        return view('anao.view');
    }

    public static function edit(string $id): Response|string
    {
        if ($middleware = LoggedIn::middleware()) {
            return $middleware;
        }

        return view('anao.edit');
    }

    public static function update(): Response|string
    {
        if ($middleware = LoggedIn::middleware()) {
            return $middleware;
        }

        return '';
    }

    public static function create(): Response|string
    {
        if ($middleware = LoggedIn::middleware()) {
            return $middleware;
        }

        return '';
    }

    public static function store(): Response|string
    {
        if ($middleware = LoggedIn::middleware()) {
            return $middleware;
        }

        return '';
    }

    public static function destroy(string $id): Response|string
    {
        if ($middleware = LoggedIn::middleware()) {
            return $middleware;
        }

        return '';
    }
}