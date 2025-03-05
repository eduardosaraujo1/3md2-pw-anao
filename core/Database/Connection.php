<?php

namespace Core\Database;

use Core\Abstract\Singleton;
use Core\Exceptions\Database\InvalidConnectionCredentialsException;
use PDO;

class Connection extends Singleton
{
    public ?PDO $pdo;

    protected function __construct(
        string $host,
        string $user,
        string $password,
        ?string $database = null,
        string $port = '3306'
    ) {
        $dsn = isset($database)
            ? "mysql:host=$host;dbname=$database;port=$port;charset=utf8"
            : "mysql:host=$host;port=$port;charset=utf8";

        $pdo = new PDO(
            dsn: $dsn,
            username: $user,
            password: $password
        );

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->pdo = $pdo;
    }

    /**
     * Creates a connection from the environment variables
     * @param bool $useSchema should the connection start with the schema defined in DB_DATABASE?
     * @throws \Core\Exceptions\Database\InvalidConnectionCredentialsException
     * @return Connection
     */
    public static function createFromEnv(bool $useSchema): Connection
    {
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
     * @return int
     */
    public function query(string $query, array $params = []): int
    {
        $stmt = $this->pdo?->prepare($query);

        if (!$stmt) {
            throw new \RuntimeException("Failed to prepare query: " . (string) $this->pdo?->errorInfo()[2]);
        }

        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        if (!$stmt->execute()) {
            throw new \RuntimeException("Query execution failed: " . (string) implode(", ", $stmt->errorInfo()));
        }

        return $stmt->rowCount();
    }

    /**
     * Runs a database query on the current connection
     * @param string $query
     * @param array<array<string,mixed>> $params
     * @return array<mixed>
     */
    public function fetch(string $query, array $params = []): array
    {
        $stmt = $this->pdo?->prepare($query);

        if (!$stmt) {
            throw new \RuntimeException("Failed to prepare query: " . (string) $this->pdo?->errorInfo()[2]);
        }

        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        if (!$stmt->execute()) {
            throw new \RuntimeException("Query execution failed: " . (string) implode(", ", $stmt->errorInfo()));
        }

        return $stmt->fetchAll();
    }

    /**
     * Runs \$callback in a php transaction
     * @param callable(PDO): mixed $callback
     * @return bool
     */
    public function transaction(callable $callback): bool
    {
        $this->pdo?->beginTransaction();

        try {
            $callback($this->pdo);
            $status = $this->pdo?->commit();
        } catch (\Throwable $e) {
            $status = $this->pdo?->rollBack();
        }

        return $status ?? false;
    }

    public function exec(string $query): void
    {
        $this->pdo?->exec($query);
    }

    public function close(): void
    {
        $this->pdo = null;
    }

    public function getPDO(): PDO|null
    {
        return $this->pdo;
    }
}