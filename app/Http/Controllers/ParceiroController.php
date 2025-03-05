<?php

namespace App\Http\Controllers;

use Core\Database\Connection;
use App\Http\Middleware\IsAuth;
use Core\Http\Request;
use Core\Http\Response;
use App\Models\Parceiro;
use Exception;
use InvalidArgumentException;

class ParceiroController
{
    public function __construct()
    {
    }

    public static function show(string $id)
    {
        $parceiros = Parceiro::fromQuery('SELECT * FROM parceiro WHERE id=:id', ['id' => $id]);

        if (empty($parceiros)) {
            return '';
        }

        return view('partials/parceiro-form', ['parceiro' => $parceiros[0]]);
    }

    public static function update(string $id): Response|string
    {
        $request = Request::instance();

        try {
            // collect post data and build query
            ['query' => $query, 'params' => $params] = self::buildUpdateQuery('parceiro', $request->postParams, $id);
            // run update query
            Connection::instance()->query($query, $params);

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

    public static function create(): string
    {
        $request = Request::instance();

        if (array_key_exists('id_anao', $request->getParams)) {
            $id_anao = $request->getParams['id_anao'];
            return view('partials/parceiro-form', ['id_anao' => $id_anao]);
        }

        return '';
    }

    public static function store(): Response|string
    {
        $request = Request::instance();

        // collect data
        $name = $request->postParams['name'] ?? null;
        $contact = $request->postParams['contact'] ?? null;
        $is_anao = $request->postParams['is_anao'] ?? null;
        $id_anao = $request->postParams['id_anao'] ?? null;

        // validate
        if (!isset($name, $contact, $is_anao, $id_anao)) {
            return 'Faltando um ou mais parametros para cadastrar parceiro.';
        }

        // build query
        $query = <<<SQL
            INSERT INTO parceiro (name, contact, is_anao, id_anao) VALUES
            (:name, :contact, :is_anao, :id_anao)
            SQL;
        $params = [
            'name' => $name,
            'contact' => $contact,
            'is_anao' => $is_anao,
            'id_anao' => $id_anao,
        ];

        // run query
        Connection::instance()->query($query, $params);

        // get newly created parceiro
        $idParceiro = Connection::instance()->getPDO()->lastInsertId();
        $parceiros = Parceiro::fromQuery("SELECT * FROM parceiro WHERE id=:id", ['id' => $idParceiro]);

        return view('partials/parceiro-form', ['parceiro' => $parceiros[0]]);
    }

    public static function destroy(string $id): Response|string
    {
        // Delete element
        Connection::instance()->query('DELETE FROM parceiro WHERE id=:id', ['id' => $id]);

        // If it was deleted successfully, return void to remove the form card, otherwise return the form again
        $parceiros = Parceiro::fromQuery('SELECT * FROM parceiro WHERE id=:id', ['id' => $id]);
        if (!empty($parceiros)) {
            return view('partials/parceiro-form', ['parceiro' => $parceiros[0]]);
        }

        return '';
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
}