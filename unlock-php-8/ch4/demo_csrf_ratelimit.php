<?php
session_start();

// ========== CSRF TOKEN ==========
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

// ========== RATE LIMITING ==========
if (!isset($_SESSION['attempts'])) {
    $_SESSION['attempts'] = [
        'count' => 0,
        'reset_time' => time() + 300 // 5 minutos
    ];
}

// Reset automático
if (time() > $_SESSION['attempts']['reset_time']) {
    $_SESSION['attempts'] = [
        'count' => 0,
        'reset_time' => time() + 300
    ];
}

$limite = 5;
$restantes = $limite - $_SESSION['attempts']['count'];
$tempo_restante = $_SESSION['attempts']['reset_time'] - time();
$mensagem = '';

// Reset manual (teste)
if (isset($_GET['reset'])) {
    $_SESSION['attempts']['count'] = 0;
    header('Location: demo_csrf_ratelimit.php');
    exit;
}

// ========== PROCESSAR FORMULÁRIO ==========
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Verifica CSRF
    if (!hash_equals($_SESSION['token'], $_POST['token'] ?? '')) {
        $mensagem = '<div class="erro">❌ CSRF INVÁLIDO! Ataque bloqueado.</div>';
    }
    // 2. Verifica Rate Limit
    elseif ($_SESSION['attempts']['count'] >= $limite) {
        $minutos = ceil($tempo_restante / 60);
        $mensagem = "<div class='erro'>❌ LIMITE ATINGIDO! Aguarde $minutos minutos.</div>";
    }
    // 3. Processa
    else {
        $_SESSION['attempts']['count']++;
        $restantes--;
        $nome = htmlspecialchars($_POST['nome']);
        $mensagem = "<div class='sucesso'>✅ Formulário enviado com sucesso! Olá, $nome!<br>Tentativas restantes: $restantes/$limite</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo: CSRF + Rate Limiting</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 { font-size: 28px; margin-bottom: 10px; }
        .header p { opacity: 0.9; }
        .content { padding: 30px; }
        .secao {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
        }
        .secao h2 {
            color: #667eea;
            margin-bottom: 15px;
            font-size: 20px;
        }
        .info-box {
            background: white;
            padding: 15px;
            border-radius: 8px;
            margin: 10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .info-box strong { color: #333; }
        .badge {
            background: #667eea;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
        }
        .badge.danger { background: #dc3545; }
        .badge.success { background: #28a745; }
        form {
            background: white;
            padding: 20px;
            border-radius: 8px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: 600;
        }
        input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: border 0.3s;
        }
        input[type="text"]:focus {
            outline: none;
            border-color: #667eea;
        }
        button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s;
        }
        button:hover { transform: translateY(-2px); }
        button:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }
        .sucesso {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #28a745;
            margin-bottom: 20px;
        }
        .erro {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #dc3545;
            margin-bottom: 20px;
        }
        .token-display {
            background: #2d3748;
            color: #68d391;
            padding: 10px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            word-break: break-all;
            margin-top: 10px;
        }
        .explicacao {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .explicacao h3 {
            color: #856404;
            margin-bottom: 10px;
        }
        .explicacao ul {
            margin-left: 20px;
            color: #856404;
        }
        .reset-link {
            display: inline-block;
            margin-top: 10px;
            color: #dc3545;
            text-decoration: none;
            font-weight: bold;
        }
        .reset-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🛡️ Demonstração: CSRF + Rate Limiting</h1>
            <p>Proteção contra ataques e controle de requisições</p>
        </div>

        <div class="content">
            <?php if ($mensagem) echo $mensagem; ?>

            <!-- SEÇÃO 1: CSRF -->
            <div class="secao">
                <h2>🔒 Proteção CSRF (Cross-Site Request Forgery)</h2>
                <div class="info-box">
                    <strong>Token CSRF Ativo:</strong>
                    <span class="badge success">✓ Protegido</span>
                </div>
                <div class="token-display">
                    <?php echo substr($_SESSION['token'], 0, 40); ?>...
                </div>
            </div>

            <!-- SEÇÃO 2: RATE LIMITING -->
            <div class="secao">
                <h2>⏱️ Rate Limiting (Controle de Tentativas)</h2>
                <div class="info-box">
                    <strong>Tentativas Restantes:</strong>
                    <span class="badge <?php echo $restantes > 0 ? 'success' : 'danger'; ?>">
                        <?php echo $restantes; ?> / <?php echo $limite; ?>
                    </span>
                </div>
                <div class="info-box">
                    <strong>Reseta em:</strong>
                    <span class="badge"><?php echo ceil($tempo_restante / 60); ?> minutos</span>
                </div>
                <a href="?reset=1" class="reset-link">🔄 Resetar contador (teste)</a>
            </div>

            <!-- FORMULÁRIO -->
            <div class="secao">
                <h2>📝 Formulário de Teste</h2>
                <form method="POST">
                    <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
                    
                    <div class="form-group">
                        <label for="nome">Seu Nome:</label>
                        <input type="text" id="nome" name="nome" required placeholder="Digite seu nome">
                    </div>

                    <button type="submit" <?php echo $restantes <= 0 ? 'disabled' : ''; ?>>
                        <?php echo $restantes > 0 ? 'Enviar Formulário' : 'Limite Atingido'; ?>
                    </button>
                </form>
            </div>

            <!-- EXPLICAÇÃO -->
            <div class="explicacao">
                <h3>📚 Como funciona:</h3>
                <ul>
                    <li><strong>CSRF Token:</strong> Impede que sites maliciosos enviem requisições em seu nome</li>
                    <li><strong>Rate Limiting:</strong> Limita a <?php echo $limite; ?> tentativas a cada 5 minutos</li>
                    <li><strong>Sessão:</strong> Identifica você sem necessidade de login (ID: <?php echo substr(session_id(), 0, 10); ?>...)</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
