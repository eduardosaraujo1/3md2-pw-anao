<?php

namespace App\Framework\Database;

use App\Framework\Exception\Database\InvalidConnectionCredentialsException;
use PDO;

class Connection
{
    public ?PDO $pdo;

    // singleton pattern
    private static self $_instance;

    private function __construct(
        string $host, 
        string $user, 
        string $password, 
        ?string $database = null,
        string $port = '3306'
        )
    {
        $dsn = isset($database) 
            ? "mysql:host=$host;dbname=$database;port=$port;charset=utf8"
            : "mysql:host=$host;port=$port;charset=utf8";

        $pdo = new PDO(
            dsn: $dsn,
            username: $user,
            password: $password
        );

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

        $this->pdo = $pdo;
    }
    public static function singleton(): self
    {
        if (!isset(self::$_instance)) {
            self::$_instance = self::createFromEnv(useSchema: true);
        }

        return self::$_instance;
    }
    // end singleton pattern

    public static function createFromEnv(bool $useSchema): Connection
    {
        /** @var array<string,string> */
        $host = $_ENV['DB_HOST'] ?? null;
        $port = $_ENV['DB_PORT'] ?? '3306';
        $user = $_ENV['DB_USER'] ?? null;
        $password = $_ENV['DB_PASSWORD'] ?? '';
        if ($useSchema) {
            $db = $_ENV['DB_DATABASE'] ?? null;
        }

        if (!isset($host, $user)) {
            throw new InvalidConnectionCredentialsException("Connection: parametros DB_HOST e/ou DB_USER não estão definidos em seu .env");
        }

        return new Connection(
            host: $host,
            user: $user,
            port: $port,
            password: $password,
            database: $db ?? null
        );
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

        return $stmt->rowCount();
    }

    /**
     * Runs a database query on the current connection
     * @param string $query
     * @param array<string,mixed> $params
     * @return array|int
     */
    public function fetch(string $query, array $params = []): array|int
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

        return $stmt->fetchAll();
    }

    public function close()
    {
        $this->pdo = null;
    }
}