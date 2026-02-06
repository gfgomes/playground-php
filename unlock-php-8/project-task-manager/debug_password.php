<?php

/**
 * Script de Debug - Verificar problema de senha
 * Acesse: http://localhost/playground-php/unlock-php-8/project-task-manager/debug_password.php
 */

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/src/helpers.php';

use App\Models\User;

echo "<h1>Debug de Senha</h1>";
echo "<hr>";

// 1. Verificar se usuário existe
echo "<h2>1. Verificar usuários no banco</h2>";
$users = (new User())->all();

if (empty($users)) {
    echo "❌ <strong>PROBLEMA:</strong> Nenhum usuário encontrado no banco!<br>";
    echo "Execute o script create_users_example.php primeiro<br>";
    exit;
}

echo "✅ Encontrados " . count($users) . " usuários:<br><br>";

foreach ($users as $user) {
    echo "<strong>ID:</strong> {$user->id}<br>";
    echo "<strong>Nome:</strong> {$user->name}<br>";
    echo "<strong>Login:</strong> {$user->login}<br>";
    echo "<strong>Senha (hash):</strong> <code>" . substr($user->password, 0, 50) . "...</code><br>";
    echo "<strong>Tamanho do hash:</strong> " . strlen($user->password) . " caracteres<br>";
    
    // Verifica se é um hash válido
    if (strlen($user->password) < 20) {
        echo "⚠️ <strong>PROBLEMA:</strong> Senha parece estar em texto plano!<br>";
    } elseif (str_starts_with($user->password, '$2y$')) {
        echo "✅ Hash bcrypt válido<br>";
    } else {
        echo "⚠️ Hash em formato desconhecido<br>";
    }
    
    echo "<hr>";
}

// 2. Testar password_verify com usuário específico
echo "<h2>2. Testar password_verify</h2>";

$testUser = (new User())->where('login', '=', 'joao')->first();

if (!$testUser) {
    echo "❌ Usuário 'joao' não encontrado<br>";
} else {
    echo "<strong>Testando usuário:</strong> {$testUser->login}<br>";
    echo "<strong>Hash no banco:</strong> <code>{$testUser->password}</code><br><br>";
    
    // Testa várias senhas
    $senhasTeste = ['123', 'nova_senha', '456', 'joao'];
    
    foreach ($senhasTeste as $senha) {
        $resultado = password_verify($senha, $testUser->password);
        $icone = $resultado ? '✅' : '❌';
        echo "$icone Senha '<strong>$senha</strong>': " . ($resultado ? 'CORRETA' : 'INCORRETA') . "<br>";
    }
}

echo "<hr>";

// 3. Testar criação de novo usuário
echo "<h2>3. Criar usuário de teste</h2>";

$loginTeste = 'teste_' . time();
$senhaTeste = '123';

$userTest = new User();
$created = $userTest->create([
    'name' => 'Usuário Teste',
    'login' => $loginTeste,
    'password' => $senhaTeste,
    'created_at' => date('Y-m-d H:i:s'),
    'updated_at' => date('Y-m-d H:i:s')
]);

if ($created) {
    echo "✅ Usuário criado: <strong>$loginTeste</strong><br>";
    
    // Busca o usuário recém-criado
    $userCreated = (new User())->where('login', '=', $loginTeste)->first();
    
    if ($userCreated) {
        echo "<strong>Hash salvo:</strong> <code>{$userCreated->password}</code><br>";
        
        // Testa a senha
        if (password_verify($senhaTeste, $userCreated->password)) {
            echo "✅ password_verify('<strong>$senhaTeste</strong>') = <strong>TRUE</strong><br>";
        } else {
            echo "❌ password_verify('<strong>$senhaTeste</strong>') = <strong>FALSE</strong><br>";
            echo "⚠️ <strong>PROBLEMA:</strong> A senha não foi criptografada corretamente!<br>";
        }
    }
} else {
    echo "❌ Erro ao criar usuário<br>";
}

echo "<hr>";

// 4. Verificar método create do User
echo "<h2>4. Verificar modelo User</h2>";

$userModel = new User();
$reflection = new ReflectionClass($userModel);

if ($reflection->hasMethod('create')) {
    $method = $reflection->getMethod('create');
    $declaringClass = $method->getDeclaringClass()->getName();
    echo "✅ Método create() existe<br>";
    echo "<strong>Declarado em:</strong> $declaringClass<br>";
    
    if ($declaringClass === 'App\Models\User') {
        echo "✅ Método sobrescrito no User (deve criptografar senha)<br>";
    } else {
        echo "⚠️ Método herdado de $declaringClass (pode não criptografar)<br>";
    }
} else {
    echo "❌ Método create() não encontrado<br>";
}

echo "<hr>";

// 5. Gerar hash manualmente para comparação
echo "<h2>5. Gerar hash manualmente</h2>";

$senhaManual = '123';
$hashManual = password_hash($senhaManual, PASSWORD_DEFAULT);

echo "<strong>Senha:</strong> $senhaManual<br>";
echo "<strong>Hash gerado:</strong> <code>$hashManual</code><br>";
echo "<strong>Verificação:</strong> ";

if (password_verify($senhaManual, $hashManual)) {
    echo "✅ password_verify funciona corretamente<br>";
} else {
    echo "❌ password_verify NÃO funciona (problema no PHP?)<br>";
}

echo "<hr>";
echo "<h2>✅ Debug concluído!</h2>";
echo "<p>Se encontrou problemas, verifique:</p>";
echo "<ul>";
echo "<li>O método create() do User está criptografando a senha?</li>";
echo "<li>As senhas no banco estão em texto plano ou hash?</li>";
echo "<li>O password_verify() está funcionando?</li>";
echo "</ul>";
