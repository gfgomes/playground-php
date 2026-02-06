<?php

namespace App\System;

use mysqli;

class MYSQLiSqlHelper
{
    private mysqli $conn;

    public function __construct(mysqli $connection)
    {
        $this->conn = $connection;
    }

    /**
     * Execute INSERT, UPDATE, DELETE
     * Suporta parâmetros nomeados (:name) ou posicionais (?)
     * Exemplo: $sql->execute("INSERT INTO users (name, email) VALUES (:name, :email)", [':name' => 'João', ':email' => 'joao@email.com']);
     */
    public function execute(string $query, array $params = []): int
    {
        [$query, $params] = $this->convertNamedParams($query, $params);
        $stmt = $this->prepare($query, $params);
        $stmt->execute();
        $affectedRows = $stmt->affected_rows;
        $stmt->close();
        return $affectedRows;
    }

    /**
     * SELECT returning single row
     * Exemplo: $user = $sql->queryOne("SELECT * FROM users WHERE id = :id", [':id' => 1]);
     */
    public function queryOne(string $query, array $params = []): ?array
    {
        [$query, $params] = $this->convertNamedParams($query, $params);
        $stmt = $this->prepare($query, $params);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    /**
     * SELECT returning all rows
     * Exemplo: $users = $sql->queryAll("SELECT * FROM users WHERE status = :status", [':status' => 'active']);
     */
    public function queryAll(string $query, array $params = []): array
    {
        [$query, $params] = $this->convertNamedParams($query, $params);
        $stmt = $this->prepare($query, $params);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $data;
    }

    /**
     * SELECT returning single value
     * Exemplo: $count = $sql->query("SELECT COUNT(*) FROM users WHERE status = :status", [':status' => 'active']);
     */
    public function query(string $query, array $params = []): mixed
    {
        [$query, $params] = $this->convertNamedParams($query, $params);
        $stmt = $this->prepare($query, $params);
        $stmt->execute();
        $result = $stmt->get_result();
        $value = $result->fetch_row()[0] ?? null;
        $stmt->close();
        return $value;
    }

    /**
     * Converte parâmetros nomeados (:name) para posicionais (?)
     */
    private function convertNamedParams(string $query, array $params): array
    {
        if (empty($params) || !$this->hasNamedParams($params)) {
            return [$query, $params];
        }

        $newParams = [];
        $newQuery = preg_replace_callback('/:([a-zA-Z0-9_]+)/', function($matches) use ($params, &$newParams) {
            $key = ':' . $matches[1];
            if (isset($params[$key])) {
                $newParams[] = $params[$key];
            }
            return '?';
        }, $query);

        return [$newQuery, $newParams];
    }

    /**
     * Verifica se array tem parâmetros nomeados
     */
    private function hasNamedParams(array $params): bool
    {
        foreach (array_keys($params) as $key) {
            if (is_string($key) && str_starts_with($key, ':')) {
                return true;
            }
        }
        return false;
    }

    /**
     * Prepare statement with parameters
     */
    private function prepare(string $query, array $params): \mysqli_stmt
    {
        $stmt = $this->conn->prepare($query);
        
        if ($stmt === false) {
            throw new \Exception("Erro ao preparar query: " . $this->conn->error);
        }

        if (!empty($params)) {
            $types = $this->getTypes($params);
            $stmt->bind_param($types, ...array_values($params));
        }

        return $stmt;
    }

    /**
     * Get parameter types for bind_param
     */
    private function getTypes(array $params): string
    {
        $types = '';
        foreach ($params as $param) {
            $types .= match (gettype($param)) {
                'integer' => 'i',
                'double' => 'd',
                'string' => 's',
                default => 'b'
            };
        }
        return $types;
    }
}
