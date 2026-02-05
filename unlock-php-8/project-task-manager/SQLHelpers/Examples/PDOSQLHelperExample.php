<?php

namespace App\Examples;

use App\System\PDOSQLHelper;

class PDOSQLHelperExample
{
    private PDOSQLHelper $db;

    public function __construct()
    {
        $this->db = new PDOSQLHelper('localhost', 'database', 'root', '');
    }

    // Parâmetros NOMEADOS (recomendado)
    public function exemploNomeados(): void
    {
        // INSERT
        $this->db->execute(
            "INSERT INTO users (name, email, status) VALUES (:name, :email, :status)",
            [
                ':name' => 'João Silva',
                ':email' => 'joao@email.com',
                ':status' => 'active'
            ]
        );

        // UPDATE
        $this->db->execute(
            "UPDATE users SET name = :name WHERE id = :id",
            [':name' => 'João Santos', ':id' => 1]
        );

        // SELECT
        $user = $this->db->queryOne(
            "SELECT * FROM users WHERE email = :email AND status = :status",
            [':email' => 'joao@email.com', ':status' => 'active']
        );

        // Múltiplos registros
        $users = $this->db->queryAll(
            "SELECT * FROM users WHERE status = :status",
            [':status' => 'active']
        );
    }

    // Parâmetros POSICIONAIS (também funciona)
    public function exemploPosicionais(): void
    {
        $this->db->execute(
            "INSERT INTO users (name, email) VALUES (?, ?)",
            ['João', 'joao@email.com']
        );

        $user = $this->db->queryOne(
            "SELECT * FROM users WHERE id = ?",
            [1]
        );
    }

    // Exemplos práticos
    public function exemplosPraticos(): void
    {
        // Contar
        $count = $this->db->query("SELECT COUNT(*) FROM users");

        // Buscar por ID
        $user = $this->db->queryOne(
            "SELECT * FROM users WHERE id = :id",
            [':id' => 1]
        );

        // Listar ativos
        $users = $this->db->queryAll(
            "SELECT * FROM users WHERE status = :status ORDER BY name",
            [':status' => 'active']
        );

        // Último ID inserido
        $this->db->execute(
            "INSERT INTO users (name) VALUES (:name)",
            [':name' => 'Novo User']
        );
        $lastId = $this->db->lastInsertId();
    }
}
