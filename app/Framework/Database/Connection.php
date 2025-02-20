<?php

namespace App\Framework\Database;

use App\Framework\Exception\Database\InvalidConnectionCredentialsException;
use PDO;
use PDOStatement;

class Connection
{
    private PDO $pdo;

    // singleton pattern
    private static self $_instance;

    private function __construct($pdo)
    {
        $this->pdo = $pdo;
    }
    public static function singleton(): self
    {
        if (!isset(self::$_instance)) {
            self::$_instance = self::createFromEnv();
        }

        return self::$_instance;
    }
    // end singleton pattern

    // Connection::query(string $query, array $params) - perform a query using PDO from the database.
    private static function createFromEnv(): Connection
    {
        /** @var array<string,string> */
        $env = getenv();

        $host = $env['DB_HOST'] ?? null;
        $port = $env['DB_PORT'] ?? '3306';
        $user = $env['DB_USER'] ?? null;
        $password = $env['DB_PASSWORD'] ?? null;
        $db = $env['DB_DATABASE'] ?? 'db_anoes';

        if (!isset($host, $user)) {
            throw new InvalidConnectionCredentialsException("Connection: parametros DB_HOST e/ou DB_USER não estão definidos em seu .env");
        }

        $pdo = new PDO(
            dsn: "mysql:host=$host;dbname=$db;charset=utf8;port=$port",
            username: $user,
            password: $password
        );

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

        return new Connection($pdo);
    }

    /**
     * Runs a database query on the current connection
     * @param string $query
     * @param array<string,mixed> $params
     * @return array|int
     */
    public function query(string $query, array $params = []): array|int
    {
        $stmt = $this->pdo->prepare($query);

        if (!$stmt) {
            throw new \RuntimeException("Failed to prepare query: " . $this->pdo->errorInfo()[2]);
        }

        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        if (!$stmt->execute()) {
            throw new \RuntimeException("Query execution failed: " . implode(", ", $stmt->errorInfo()));
        }

        // Check if it's a SELECT query to return results
        if (stripos(trim($query), "SELECT") === 0) {
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }

        // For non-SELECT queries, return number of affected rows
        return $stmt->rowCount();
    }


    public function exec(string $query)
    {
        return $this->pdo->exec($query);
    }
}