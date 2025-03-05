<?php

namespace App\Http\Controllers;

use Core\Database\Connection;
use Core\Http\Request;
use Core\Http\Response;
use App\Models\Anao;
use App\Models\Parceiro;
use Core\Session;
use InvalidArgumentException;

class AnaoController
{
    public function __construct()
    {
    }

    public static function index(): Response|string
    {
        $anoes = Anao::fromQuery("SELECT * FROM anao");

        return view('anao.index', [
            'anoes' => $anoes
        ]);
    }

    public static function show(string $id): Response|string
    {

        $anao = Anao::fromQuery("SELECT * FROM anao WHERE id=:id", ['id' => $id]);

        if (empty($anao)) {
            return new Response(
                status: 404,
                content: view('error', ['code' => 404, 'message' => "Recurso não encontrado: Anão $id"])
            );
        }

        $parceiros = Parceiro::fromQuery('SELECT * FROM parceiro WHERE id_anao=:id', ['id' => $anao[0]->id]);

        return view('anao.view', [
            'anao' => $anao[0],
            'parceiros' => $parceiros,
            'errors' => [],
        ]);
    }

    public static function update(string $id): Response|string
    {
        $request = Request::instance();

        try {
            // collect post data and build query
            ['query' => $query, 'params' => $params] = self::buildUpdateQuery('anao', $request->postParams, $id);
            // run update query
            Connection::instance()->query($query, $params);
        } catch (InvalidArgumentException $e) {
            Session::flash('errors', [
                'Ocorreu um erro ao enviar os dados a serem editados.'
            ]);
        } catch (\Throwable $e) {
            Session::flash('errors', [
                'Erro interno ao salvar alterações'
            ]);
        }

        return redirect("/anao/$id");
    }

    public static function create(): Response|string
    {
        return view('anao.create');
    }

    public static function store(): Response|string
    {
        $request = Request::instance();

        // collect data
        $name = $request->postParams['name'] ?? null;
        $age = $request->postParams['age'] ?? null;
        $race = $request->postParams['race'] ?? null;
        $height = $request->postParams['height'] ?? null;

        // validate
        if (!isset($name, $age, $race, $height)) {
            Session::flash('errors', [
                'Faltando um ou mais parametros para cadastrar anão.'
            ]);
            return redirect("/anao/create");
        }

        // build query
        $query = <<<SQL
            INSERT INTO anao (name, age, race, height) VALUES
            (:name, :age, :race, :height)
            SQL;
        $params = [
            'name' => $name,
            'age' => $age,
            'race' => $race,
            'height' => $height,
        ];

        // run query
        Connection::instance()->query($query, $params);

        return redirect('/home');
    }

    public static function destroy(string $id): Response|string
    {
        // Delete element
        Connection::instance()->query('DELETE FROM anao WHERE id=:id', ['id' => $id]);

        // If it was deleted successfully, return void to remove the form card, otherwise return the form again
        $parceiros = Parceiro::fromQuery('SELECT * FROM anao WHERE id=:id', ['id' => $id]);
        if (!empty($parceiros)) {
            return redirect("/anao/$id");
        }

        return '';
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
}