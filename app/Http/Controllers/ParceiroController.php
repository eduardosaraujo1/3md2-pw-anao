<?php

namespace App\Http\Controllers;

use App\Framework\Facades\DB;
use App\Http\Middleware\LoggedIn;
use App\Framework\Http\Request;
use App\Framework\Http\Response;
use App\Models\Anao;
use App\Models\Parceiro;
use InvalidArgumentException;

class ParceiroController
{
    public function __construct()
    {
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
        } catch (InvalidArgumentException $e) {
            return 'Ocorreu um erro ao enviar os dados a serem editados.';
        } catch (\Throwable $e) {
            return 'Erro interno ao salvar alterações';
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

    public static function store(Request $request): Response|string
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