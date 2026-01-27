<?php
session_start();

// Simula banco de dados em sessão
if (!isset($_SESSION['usuarios'])) {
    $_SESSION['usuarios'] = [
        ['id' => 1, 'nome' => 'Maria', 'email' => 'maria@email.com'],
        ['id' => 2, 'nome' => 'João', 'email' => 'joao@email.com']
    ];
}

$mensagem = '';
$tipo = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $metodo = $_POST['metodo'];
    
    if ($metodo === 'vulneravel') {
        // ❌ VULNERÁVEL - Concatenação direta
        $sql = "INSERT INTO usuarios (nome, email) VALUES ('$nome', '$email')";
        
        // Simula SQL Injection
        if (strpos($nome, "'; DROP") !== false || strpos($nome, "'; DELETE") !== false) {
            $_SESSION['usuarios'] = []; // Simula destruição do banco
            $mensagem = "💥 <strong>BANCO DESTRUÍDO!</strong> SQL Injection bem-sucedido!<br>
                        SQL executado: <code>$sql</code>";
            $tipo = 'erro';
        } else {
            $_SESSION['usuarios'][] = ['id' => count($_SESSION['usuarios']) + 1, 'nome' => $nome, 'email' => $email];
            $mensagem = "✅ Usuário inserido (mas ainda vulnerável!)<br>SQL: <code>$sql</code>";
            $tipo = 'sucesso';
        }
    } else {
        // ✅ PROTEGIDO - Prepared Statement
        // Simula prepared statement (proteção automática)
        $nome_limpo = htmlspecialchars($nome, ENT_QUOTES);
        $email_limpo = htmlspecialchars($email, ENT_QUOTES);
        
        $_SESSION['usuarios'][] = ['id' => count($_SESSION['usuarios']) + 1, 'nome' => $nome_limpo, 'email' => $email_limpo];
        $mensagem = "🛡️ <strong>PROTEGIDO!</strong> Prepared statement bloqueou o ataque!<br>
                    Dados tratados como texto, não como código SQL.";
        $tipo = 'sucesso';
    }
}

// Reset
if (isset($_GET['reset'])) {
    $_SESSION['usuarios'] = [
        ['id' => 1, 'nome' => 'Maria', 'email' => 'maria@email.com'],
        ['id' => 2, 'nome' => 'João', 'email' => 'joao@email.com']
    ];
    header('Location: demo_sql_injection.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SQL Injection vs Prepared Statements</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .content { padding: 30px; }
        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        .card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            border: 3px solid #e0e0e0;
        }
        .card.vulneravel { border-color: #dc3545; }
        .card.protegido { border-color: #28a745; }
        .card h3 {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .card.vulneravel h3 { color: #dc3545; }
        .card.protegido h3 { color: #28a745; }
        input[type="text"], input[type="email"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px 0;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            font-size: 14px;
        }
        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-vulneravel {
            background: #dc3545;
            color: white;
        }
        .btn-protegido {
            background: #28a745;
            color: white;
        }
        .mensagem {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .mensagem.sucesso {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        .mensagem.erro {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        .tabela {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #e0e0e0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background: #667eea;
            color: white;
            padding: 12px;
            text-align: left;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #e0e0e0;
        }
        tr:hover { background: #f8f9fa; }
        .ataque-exemplo {
            background: #fff3cd;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #ffc107;
        }
        code {
            background: #2d3748;
            color: #68d391;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: monospace;
        }
        .explicacao {
            background: #e3f2fd;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }
        .explicacao h3 {
            color: #1976d2;
            margin-bottom: 10px;
        }
        .codigo-comparacao {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 20px 0;
        }
        .codigo-box {
            background: #2d3748;
            color: #68d391;
            padding: 15px;
            border-radius: 8px;
            font-family: monospace;
            font-size: 12px;
            line-height: 1.6;
        }
        .codigo-box.vulneravel { border: 3px solid #dc3545; }
        .codigo-box.protegido { border: 3px solid #28a745; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>💉 SQL Injection vs Prepared Statements</h1>
            <p>Veja a diferença entre código vulnerável e protegido</p>
        </div>

        <div class="content">
            <?php if ($mensagem): ?>
                <div class="mensagem <?php echo $tipo; ?>">
                    <?php echo $mensagem; ?>
                </div>
            <?php endif; ?>

            <div class="ataque-exemplo">
                <strong>💀 Teste de Ataque SQL Injection:</strong><br>
                Cole este texto no campo "Nome" do formulário VULNERÁVEL:<br>
                <code>João'; DROP TABLE usuarios; --</code>
            </div>

            <div class="grid">
                <!-- FORMULÁRIO VULNERÁVEL -->
                <div class="card vulneravel">
                    <h3>❌ Método VULNERÁVEL</h3>
                    <form method="POST">
                        <input type="hidden" name="metodo" value="vulneravel">
                        <label>Nome:</label>
                        <input type="text" name="nome" required>
                        <label>Email:</label>
                        <input type="email" name="email" required>
                        <button type="submit" class="btn-vulneravel">Inserir (Vulnerável)</button>
                    </form>
                </div>

                <!-- FORMULÁRIO PROTEGIDO -->
                <div class="card protegido">
                    <h3>✅ Prepared Statement</h3>
                    <form method="POST">
                        <input type="hidden" name="metodo" value="protegido">
                        <label>Nome:</label>
                        <input type="text" name="nome" required>
                        <label>Email:</label>
                        <input type="email" name="email" required>
                        <button type="submit" class="btn-protegido">Inserir (Protegido)</button>
                    </form>
                </div>
            </div>

            <!-- TABELA DE USUÁRIOS -->
            <h2>👥 Banco de Dados (<?php echo count($_SESSION['usuarios']); ?> usuários)</h2>
            <div class="tabela">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($_SESSION['usuarios'])): ?>
                            <tr>
                                <td colspan="3" style="text-align:center; color:red; font-weight:bold;">
                                    💥 BANCO DESTRUÍDO POR SQL INJECTION!
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($_SESSION['usuarios'] as $user): ?>
                                <tr>
                                    <td><?php echo $user['id']; ?></td>
                                    <td><?php echo htmlspecialchars($user['nome']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div style="text-align:center; margin-top:20px;">
                <a href="?reset=1" style="color:#dc3545; font-weight:bold;">🔄 Resetar Banco de Dados</a>
            </div>

            <!-- EXPLICAÇÃO DO CÓDIGO -->
            <div class="explicacao">
                <h3>📚 Como funciona o código que você perguntou:</h3>
                
                <div class="codigo-comparacao">
                    <div>
                        <h4 style="color:#dc3545; margin-bottom:10px;">❌ VULNERÁVEL</h4>
                        <div class="codigo-box vulneravel">
$sql = "INSERT INTO table<br>
&nbsp;&nbsp;VALUES ('$name', '$email')";<br>
$connection->query($sql);<br>
<br>
// Problema: $name pode conter<br>
// código SQL malicioso!
                        </div>
                    </div>

                    <div>
                        <h4 style="color:#28a745; margin-bottom:10px;">✅ PROTEGIDO</h4>
                        <div class="codigo-box protegido">
$stmt = $connection->prepare(<br>
&nbsp;&nbsp;"INSERT INTO table<br>
&nbsp;&nbsp;VALUES (?, ?)");<br>
$stmt->bind_param("ss",<br>
&nbsp;&nbsp;$name, $email);<br>
$stmt->execute();<br>
<br>
// ? = placeholder seguro<br>
// "ss" = 2 strings<br>
// Dados tratados como TEXTO
                        </div>
                    </div>
                </div>

                <h4 style="margin-top:20px;">🔍 Explicação linha por linha:</h4>
                <ol style="margin-left:20px; margin-top:10px;">
                    <li><code>prepare()</code> - Prepara SQL com placeholders (?)</li>
                    <li><code>bind_param("ss", ...)</code> - Liga variáveis aos placeholders
                        <ul style="margin-left:20px;">
                            <li>"ss" = 2 strings (s = string, i = integer, d = double)</li>
                        </ul>
                    </li>
                    <li><code>execute()</code> - Executa com segurança (dados = texto, não código)</li>
                </ol>
            </div>
        </div>
    </div>
</body>
</html>
