<?php

/**
 * Script para criar usuários de teste com senha criptografada
 * 
 * COMO USAR:
 * 1. Acesse: http://localhost/playground-php/unlock-php-8/project-task-manager/create_users_example.php
 * 2. O script vai:
 *    - Deletar todos os usuários antigos
 *    - Criar 3 usuários novos (joao, maria, pedro)
 *    - Senhas serão criptografadas automaticamente pelo modelo User
 * 3. Use as credenciais para fazer login
 * 
 * IMPORTANTE:
 * - As senhas são passadas em TEXTO PLANO ('123')
 * - O modelo User criptografa automaticamente com password_hash()
 * - No banco fica salvo o HASH ($2y$10$...)
 * - No login, password_verify() compara a senha digitada com o hash
 */

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/src/helpers.php';

use App\Models\User;
use App\System\DatabasePDO;

echo "<h1>Criar Usuários de Teste</h1>";
echo "<hr>";

// 1. Deletar todos os usuários antigos
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
    
    // A senha '123' será automaticamente criptografada pelo método create() do User
    $created = $user->create([
        'name' => $userData['name'],
        'login' => $userData['login'],
        'password' => $userData['password'], // ← Texto plano (será criptografado)
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ]);
    
    if ($created) {
        echo "✅ Usuário '<strong>{$userData['login']}</strong>' criado<br>";
        
        // Verifica se foi criptografado corretamente
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
echo "<h2>✅ Usuários criados com sucesso!</h2>";
echo "<p><strong>Credenciais para login:</strong></p>";
echo "<ul>";
echo "<li>Login: <strong>joao</strong> | Senha: <strong>123</strong></li>";
echo "<li>Login: <strong>maria</strong> | Senha: <strong>123</strong></li>";
echo "<li>Login: <strong>pedro</strong> | Senha: <strong>123</strong></li>";
echo "</ul>";
echo "<p><a href='/playground-php/unlock-php-8/project-task-manager/login'>Ir para Login</a></p>";

echo "<hr>";
echo "<h2>📝 Como funciona:</h2>";
echo "<ol>";
echo "<li><strong>Cadastro:</strong> Senha '123' → password_hash() → Banco salva '$2y$10$...'</li>";
echo "<li><strong>Login:</strong> Usuário digita '123' → password_verify('123', '$2y$10$...') → Retorna true</li>";
echo "<li><strong>Segurança:</strong> Mesmo que alguém veja o banco, não consegue descobrir a senha original</li>";
echo "</ol>";

echo "<h2>🛠️ Para criar mais usuários:</h2>";
echo "<pre style='background: #f5f5f5; padding: 15px; border-radius: 5px;'>";
echo htmlspecialchars("<?php\n");
echo htmlspecialchars("\n// Exemplo: Criar novo usuário\n");
echo htmlspecialchars("\$user = new User();\n");
echo htmlspecialchars("\$user->create([\n");
echo htmlspecialchars("    'name' => 'Novo Usuário',\n");
echo htmlspecialchars("    'login' => 'novo',\n");
echo htmlspecialchars("    'password' => 'senha123', // ← Texto plano (será criptografado)\n");
echo htmlspecialchars("    'created_at' => date('Y-m-d H:i:s'),\n");
echo htmlspecialchars("    'updated_at' => date('Y-m-d H:i:s')\n");
echo htmlspecialchars("]);\n");
echo "</pre>";
