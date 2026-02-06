<?php

/**
 * Script para LIMPAR e RECRIAR usuários com senha criptografada
 * Acesse: http://localhost/playground-php/unlock-php-8/project-task-manager/fix_users.php
 */

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/src/helpers.php';

use App\Models\User;
use App\System\DatabasePDO;

echo "<h1>Corrigir Usuários</h1>";
echo "<hr>";

// 1. Deletar todos os usuários
echo "<h2>1. Limpando usuários antigos...</h2>";

$db = DatabasePDO::instance()->getConnection();
$db->exec("DELETE FROM users");

echo "✅ Todos os usuários deletados<br><br>";

// 2. Criar usuários com senha criptografada
echo "<h2>2. Criando novos usuários...</h2>";

$usuarios = [
    ['name' => 'João Silva', 'login' => 'joao', 'password' => '123'],
    ['name' => 'Maria Santos', 'login' => 'maria', 'password' => '123'],
    ['name' => 'Pedro Costa', 'login' => 'pedro', 'password' => '123'],
];

foreach ($usuarios as $userData) {
    $user = new User();
    $created = $user->create([
        'name' => $userData['name'],
        'login' => $userData['login'],
        'password' => $userData['password'], // Será criptografado automaticamente
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ]);
    
    if ($created) {
        echo "✅ Usuário '<strong>{$userData['login']}</strong>' criado<br>";
        
        // Verifica se foi criptografado
        $userCheck = (new User())->where('login', '=', $userData['login'])->first();
        if ($userCheck) {
            $hashLength = strlen($userCheck->password);
            if ($hashLength > 20) {
                echo "&nbsp;&nbsp;&nbsp;✅ Senha criptografada (hash: {$hashLength} caracteres)<br>";
                
                // Testa password_verify
                if (password_verify($userData['password'], $userCheck->password)) {
                    echo "&nbsp;&nbsp;&nbsp;✅ password_verify() funciona!<br>";
                } else {
                    echo "&nbsp;&nbsp;&nbsp;❌ password_verify() falhou!<br>";
                }
            } else {
                echo "&nbsp;&nbsp;&nbsp;❌ Senha em texto plano! (tamanho: {$hashLength})<br>";
            }
        }
    } else {
        echo "❌ Erro ao criar usuário '{$userData['login']}'<br>";
    }
    echo "<br>";
}

echo "<hr>";
echo "<h2>✅ Usuários corrigidos!</h2>";
echo "<p><strong>Credenciais para login:</strong></p>";
echo "<ul>";
echo "<li>Login: <strong>joao</strong> | Senha: <strong>123</strong></li>";
echo "<li>Login: <strong>maria</strong> | Senha: <strong>123</strong></li>";
echo "<li>Login: <strong>pedro</strong> | Senha: <strong>123</strong></li>";
echo "</ul>";
echo "<p><a href='/playground-php/unlock-php-8/project-task-manager/login'>Ir para Login</a></p>";
