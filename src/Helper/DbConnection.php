<?php

namespace App\Helper;

use App\Services\Router;
use Dotenv\Dotenv;
use Exception;
use PDO;
use PDOException;

class DbConnection
{
    private PDO $pdo;

    public function __construct()
    {
        try {
            self::initDbConnection();
        } catch (Exception $e) {
            Router::errorPage(500);
        }
    }

    private function initDbConnection()
    {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
        $dotenv->load();
        $dsn = 'pgsql:host=' . $_ENV['DB_HOST'] .
            ";port=" . $_ENV['DB_PORT'] .
            ";dbname=" . $_ENV['DB_NAME'] .
            ";user=" . $_ENV['DB_USER'] .
            ";password=" . $_ENV['DB_PASSWORD'];
        try {
            $this->pdo = new PDO($dsn);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function execGetStatus(string $sql, array $fields = []): bool
    {
        if (count($fields) != 0) {
            return $this->pdo->prepare($sql)->execute($fields);
        }
        return $this->pdo->prepare($sql)->execute();
    }

    public function execGetRowCount(string $sql, array $fields = []): int
    {
        $statement = $this->pdo->prepare($sql);
        if (count($fields) != 0) {
            $statement->execute($fields);
        } else {
            $statement->execute();
        }
        return $statement->rowCount();
    }

    public function execGetDataArray(string $sql, array $fields = []): array
    {
        $statement = $this->pdo->prepare($sql);
        if (count($fields) != 0) {
            $statement->execute($fields);
        } else {
            $statement->execute();
        }
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        if (isset($data)) {
            return $data;
        }
        return [];
    }

    public function getLastInsertId(): int
    {
        return $this->pdo->lastInsertId();
    }

    public function __serialize(): array
    {
        return [];
    }

    public function __unserialize(array $data): void
    {
        try {
            $this->initDbConnection();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
