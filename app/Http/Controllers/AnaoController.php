<?php

namespace App\Http\Controllers;

use App\Framework\Facades\DB;
use App\Http\Middleware\LoggedIn;
use App\Framework\Http\Request;
use App\Framework\Http\Response;
use App\Models\Anao;
use InvalidArgumentException;

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

        $anao = Anao::fromQuery("SELECT * FROM anao WHERE id=:id", ['id' => $id]);

        if (empty($anao)) {
            return new Response(
                status: 404,
                content: view('error', ['code' => 404, 'message' => "Recurso não encontrado: Anão $id"])
            );
        }

        return view('anao.view', [
            'anao' => $anao[0]
        ]);
    }

    public static function update(Request $request, string $id): Response|string
    {
        if ($middleware = LoggedIn::middleware()) {
            // ensure only logged in users may update
            return $middleware;
        }

        try {
            // collect post data and build query
            ['query' => $query, 'params' => $params] = self::buildUpdateQuery('anao', $request->postParams, $id);
            // run update query
            DB::query($query, $params);
        } catch (\Throwable $e) {
            return <<<HTML
                <h3 class="text-sm text-red-700">Erro interno ao salvar alterações</h3>
                HTML;
        }

        // Handle HTMX Refreshing
        return new Response(
            status: 301,
            headers: ['HX-Trigger: soft-refresh'],
        );
    }

    /**
     * @param array<string,string> $data
     */
    private static function buildUpdateQuery(string $table, array $data, int $id)
    {
        // Filter data to only contain allowed fields
        $allowedFields = ['name', 'age', 'race', 'height'];
        $filteredData = array_intersect_key($data, array_flip($allowedFields));

        if (empty($filteredData)) {
            throw new InvalidArgumentException("No valid data provided for update.");
        }

        $query = "UPDATE {$table} SET ";
        $params = ['id' => $id];

        $setClauses = [];

        foreach ($filteredData as $key => $value) {
            $setClauses[] = "{$key} = :{$key}";
            $params[$key] = $value;
        }

        $query .= implode(', ', $setClauses);
        $query .= " WHERE id = :id";

        return ['query' => $query, 'params' => $params];
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