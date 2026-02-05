<?php

namespace App\Examples;

use App\System\SqlHelper;
use App\System\DatabaseHelper;
use mysqli;

/**
 * EXEMPLOS DE USO - SqlHelper (MySQLi) e DatabaseHelper (PDO)
 */

// ============================================
// SqlHelper - MySQLi com suporte a named parameters
// ============================================

$conn = new mysqli('localhost', 'root', '', 'database');
$sql = new SqlHelper($conn);

// 1. execute() - INSERT, UPDATE, DELETE
// ============================================

// INSERT com named parameters
$affected = $sql->execute(
    "INSERT INTO users (name, email, age, salary) VALUES (:name, :email, :age, :salary)",
    [
        ':name' => 'João Silva',
        ':email' => 'joao@email.com',
        ':age' => 25,
        ':salary' => 5500.50
    ]
);
echo "Linhas afetadas: $affected\n";

// UPDATE com named parameters
$sql->execute(
    "UPDATE users SET name = :name, email = :email WHERE id = :id",
    [':name' => 'João Santos', ':email' => 'joao.santos@email.com', ':id' => 1]
);

// DELETE com positional parameters
$sql->execute("DELETE FROM users WHERE id = ?", [1]);

// 2. query() - SELECT retornando valor único
// ============================================

// Contar registros
$count = $sql->query("SELECT COUNT(*) FROM users");
echo "Total de usuários: $count\n";

// Buscar nome específico
$name = $sql->query(
    "SELECT name FROM users WHERE id = :id",
    [':id' => 1]
);
echo "Nome: $name\n";

// Soma de salários
$total = $sql->query(
    "SELECT SUM(salary) FROM users WHERE status = :status",
    [':status' => 'active']
);

// 3. queryOne() - SELECT retornando uma linha
// ============================================

// Buscar usuário por ID
$user = $sql->queryOne(
    "SELECT * FROM users WHERE id = :id",
    [':id' => 1]
);
echo $user['name'] ?? 'Não encontrado';

// Buscar por email
$user = $sql->queryOne(
    "SELECT id, name, email FROM users WHERE email = :email",
    [':email' => 'joao@email.com']
);

// Com múltiplos filtros
$user = $sql->queryOne(
    "SELECT * FROM users WHERE email = :email AND status = :status",
    [':email' => 'joao@email.com', ':status' => 'active']
);

// 4. queryAll() - SELECT retornando todas as linhas
// ============================================

// Listar todos
$users = $sql->queryAll("SELECT * FROM users");
foreach ($users as $user) {
    echo $user['name'] . "\n";
}

// Com filtro
$users = $sql->queryAll(
    "SELECT * FROM users WHERE status = :status ORDER BY name",
    [':status' => 'active']
);

// Com múltiplos filtros
$users = $sql->queryAll(
    "SELECT * FROM users WHERE status = :status AND age >= :age",
    [':status' => 'active', ':age' => 18]
);

// ============================================
// DatabaseHelper - PDO com named parameters nativos
// ============================================

$db = new DatabaseHelper('localhost', 'database', 'root', '');

// 1. execute() - INSERT, UPDATE, DELETE
// ============================================

// INSERT
$affected = $db->execute(
    "INSERT INTO users (name, email, age) VALUES (:name, :email, :age)",
    [':name' => 'Maria Silva', ':email' => 'maria@email.com', ':age' => 30]
);

// UPDATE
$db->execute(
    "UPDATE users SET name = :name WHERE id = :id",
    [':name' => 'Maria Santos', ':id' => 2]
);

// DELETE
$db->execute("DELETE FROM users WHERE id = :id", [':id' => 2]);

// 2. query() - SELECT retornando valor único
// ============================================

$count = $db->query("SELECT COUNT(*) FROM users");
$name = $db->query("SELECT name FROM users WHERE id = :id", [':id' => 1]);
$maxAge = $db->query("SELECT MAX(age) FROM users");

// 3. queryOne() - SELECT retornando uma linha
// ============================================

$user = $db->queryOne(
    "SELECT * FROM users WHERE id = :id",
    [':id' => 1]
);

$user = $db->queryOne(
    "SELECT * FROM users WHERE email = :email AND status = :status",
    [':email' => 'joao@email.com', ':status' => 'active']
);

// 4. queryAll() - SELECT retornando todas as linhas
// ============================================

$users = $db->queryAll("SELECT * FROM users ORDER BY name");

$users = $db->queryAll(
    "SELECT * FROM users WHERE status = :status",
    [':status' => 'active']
);

// 5. lastInsertId() - Último ID inserido
// ============================================

$db->execute(
    "INSERT INTO users (name, email) VALUES (:name, :email)",
    [':name' => 'Pedro', ':email' => 'pedro@email.com']
);
$lastId = $db->lastInsertId();
echo "ID inserido: $lastId\n";

// ============================================
// COMPARAÇÃO: Quando usar cada um?
// ============================================

/*
SqlHelper (MySQLi):
- Usa MySQLi (mais comum em hospedagens compartilhadas)
- Suporte a named parameters via conversão interna
- Boa performance
- Específico para MySQL

DatabaseHelper (PDO):
- Usa PDO (mais moderno e flexível)
- Suporte nativo a named parameters
- Funciona com múltiplos bancos (MySQL, PostgreSQL, SQLite, etc)
- Recomendado para novos projetos

AMBOS:
✅ Protegem contra SQL Injection
✅ Suportam named parameters (:name)
✅ Suportam positional parameters (?)
✅ Detecção automática de tipos
*/
