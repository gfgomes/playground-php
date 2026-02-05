<?php

namespace App\System;

use PDO;

class PDOSQLHelper
{
    private PDO $pdo;

    public function __construct(string $host, string $db, string $user, string $pass)
    {
        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
        $this->pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }

    /**
     * INSERT, UPDATE, DELETE
     * Exemplo: $db->execute("INSERT INTO users (name) VALUES (:name)", [':name' => 'João']);
     */
    public function execute(string $query, array $params = []): int
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    /**
     * SELECT retornando valor único
     * Exemplo: $count = $db->query("SELECT COUNT(*) FROM users WHERE status = :status", [':status' => 'active']);
     */
    public function query(string $query, array $params = []): mixed
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    /**
     * SELECT retornando uma linha
     * Exemplo: $user = $db->queryOne("SELECT * FROM users WHERE id = :id", [':id' => 1]);
     */
    public function queryOne(string $query, array $params = []): ?array
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * SELECT retornando todas as linhas
     * Exemplo: $users = $db->queryAll("SELECT * FROM users WHERE status = :status", [':status' => 'active']);
     */
    public function queryAll(string $query, array $params = []): array
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Retorna último ID inserido
     * Exemplo: $id = $db->lastInsertId();
     */
    public function lastInsertId(): string
    {
        return $this->pdo->lastInsertId();
    }
}
