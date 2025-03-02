<?php

namespace App\Http\Controllers;

use App\Framework\Facades\DB;
use App\Http\Middleware\LoggedIn;
use App\Framework\Http\Request;
use App\Framework\Http\Response;
use App\Models\Anao;
use App\Models\Parceiro;
use Exception;
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
            ['query' => $query, 'params' => $params] = self::buildUpdateQuery('parceiro', $request->postParams, $id);
            // run update query
            DB::query($query, $params);

            // build form from result of the update
            $parceiros = Parceiro::fromQuery('SELECT * FROM parceiro WHERE id=:id', ['id' => $id]);

            if (empty($parceiros)) {
                throw new Exception("Ocorreu um erro no parametro $id");
            }

            return view('partials/parceiro-form', ['parceiro' => $parceiros[0]]);
        } catch (InvalidArgumentException $e) {
            return 'Ocorreu um erro ao enviar os dados a serem editados.';
        } catch (\Throwable $e) {
            return 'Erro interno ao salvar alterações';
        }
    }

    /**
     * @param array<string,string> $data
     */
    private static function buildUpdateQuery(string $table, array $data, int $id)
    {
        // Filter data to only contain allowed fields
        $allowedFields = ['name', 'contact', 'is_anao'];
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