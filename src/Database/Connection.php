<?php

namespace App\Database;

use PDO;
use PDOException;

class Connection
{
    private PDO $pdo;

    public function __construct(
        private string $dsn,
        private string $username,
        private string $password
    ) {
        $this->connect();
    }

    private function connect(): void
    {
        try {
            $this->pdo = new PDO($this->dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            throw new \Exception('Database connection failed: ' . $e->getMessage());
        }
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }
}
