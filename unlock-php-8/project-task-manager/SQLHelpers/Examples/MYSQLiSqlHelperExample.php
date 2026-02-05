<?php

namespace App\Examples;

use App\System\MYSQLiSqlHelper;
use mysqli;

class SqlHelperExample
{
    private MYSQLiSqlHelper $sql;

    public function __construct()
    {
        $conn = new mysqli('localhost', 'root', '', 'database');
        $this->sql = new MYSQLiSqlHelper($conn);
    }

    // Named parameters (estilo ADO.NET)
    public function exemploNamedParameters(): void
    {
        // INSERT
        $this->sql->execute(
            "INSERT INTO users (name, email, status) VALUES (:name, :email, :status)",
            [
                ':name' => 'João Silva',
                ':email' => 'joao@email.com',
                ':status' => 'active'
            ]
        );

        // UPDATE
        $this->sql->execute(
            "UPDATE users SET name = :name WHERE id = :id",
            [':name' => 'João Santos', ':id' => 1]
        );

        // SELECT
        $user = $this->sql->queryOne(
            "SELECT * FROM users WHERE email = :email AND status = :status",
            [':email' => 'joao@email.com', ':status' => 'active']
        );

        // Query escalar
        $count = $this->sql->query(
            "SELECT COUNT(*) FROM users WHERE status = :status",
            [':status' => 'active']
        );

        // Query all
        $users = $this->sql->queryAll(
            "SELECT * FROM users WHERE status = :status ORDER BY name",
            [':status' => 'active']
        );
    }

    // Positional parameters (também funciona)
    public function exemploPosicionais(): void
    {
        $this->sql->execute(
            "INSERT INTO users (name, email) VALUES (?, ?)",
            ['João', 'joao@email.com']
        );

        $user = $this->sql->queryOne(
            "SELECT * FROM users WHERE id = ?",
            [1]
        );
    }
}
