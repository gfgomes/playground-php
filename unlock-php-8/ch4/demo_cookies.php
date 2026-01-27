<?php
// Processar ações
if (isset($_GET['acao'])) {
    switch ($_GET['acao']) {
        case 'simples':
            setcookie('nome', 'João', time() + 3600);
            break;
        case 'array':
            $usuario = ['nome' => 'Maria', 'idade' => 25, 'email' => 'maria@email.com'];
            setcookie('usuario', json_encode($usuario), time() + 3600);
            break;
        case 'limpar':
            setcookie('nome', '', time() - 3600);
            setcookie('usuario', '', time() - 3600);
            break;
    }
    header('Location: demo_cookies.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Como Cookies Funcionam</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        h1 {
            color: #667eea;
            text-align: center;
            border-bottom: 3px solid #667eea;
            padding-bottom: 15px;
        }

        .fluxo {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 10px;
            margin: 30px 0;
        }

        .passo {
            background: white;
            padding: 20px;
            margin: 15px 0;
            border-radius: 8px;
            border-left: 5px solid #667eea;
        }

        .codigo {
            background: #2d3748;
            color: #68d391;
            padding: 20px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            margin: 15px 0;
            line-height: 1.8;
            overflow-x: auto;
        }

        .destaque {
            background: #ffd700;
            padding: 3px 8px;
            border-radius: 3px;
            font-weight: bold;
            color: #000;
        }

        .comparacao {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 20px 0;
        }

        .box {
            padding: 20px;
            border-radius: 10px;
        }

        .box.ruim {
            background: #ffebee;
            border: 3px solid #f44336;
        }

        .box.bom {
            background: #e8f5e9;
            border: 3px solid #4caf50;
        }

        .teste {
            background: #e3f2fd;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }

        button {
            background: #667eea;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            margin: 5px;
        }

        button:hover {
            background: #5568d3;
        }

        .alerta {
            background: #fff3cd;
            border: 2px solid #ffc107;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background: #667eea;
            color: white;
        }

        tr:nth-child(even) {
            background: #f8f9fa;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>🍪 Como Cookies Funcionam em PHP</h1>

        <!-- PERGUNTA 1: COMO SÃO ENVIADOS -->
        <div class="fluxo">
            <h2 style="color: #667eea;">📡 Como cookies são enviados entre requisições?</h2>

            <div class="passo">
                <h3>1️⃣ Servidor CRIA o cookie</h3>
                <div class="codigo">
                    // PHP cria o cookie<br>
                    setcookie('nome', 'João', time() + 3600);
                </div>
                <p>PHP envia no <strong>cabeçalho HTTP</strong>:</p>
                <div class="codigo" style="background: #fff3cd; color: #856404;">
                    Set-Cookie: nome=João; expires=...; path=/
                </div>
            </div>

            <div class="passo">
                <h3>2️⃣ Navegador SALVA o cookie</h3>
                <p>O navegador salva em um arquivo de texto no computador do usuário:</p>
                <div class="codigo" style="background: #e3f2fd; color: #1976d2;">
                    📁 C:\Users\Usuario\AppData\Local\Google\Chrome\User Data\Default\Cookies<br>
                    <br>
                    Conteúdo:<br>
                    localhost | nome | João | expires: ...
                </div>
            </div>

            <div class="passo">
                <h3>3️⃣ Navegador ENVIA automaticamente</h3>
                <p>Em TODA requisição para o mesmo domínio, o navegador envia:</p>
                <div class="codigo" style="background: #e8f5e9; color: #2e7d32;">
                    GET /pagina.php HTTP/1.1<br>
                    Host: localhost<br>
                    <span class="destaque">Cookie: nome=João</span>
                </div>
            </div>

            <div class="passo">
                <h3>4️⃣ PHP LÊ o cookie</h3>
                <div class="codigo">
                    // PHP recebe automaticamente<br>
                    echo $_COOKIE['nome']; // João
                </div>
            </div>
        </div>

        <!-- VISUALIZAÇÃO -->
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; border-radius: 15px; margin: 30px 0;">
            <h2 style="color: white; margin-top: 0;">🔄 Fluxo Completo</h2>
            <div style="text-align: center; font-size: 18px; line-height: 2.5;">
                <strong>1. PHP</strong> → Set-Cookie (cabeçalho HTTP) →<br>
                <strong>2. Navegador</strong> → Salva em arquivo →<br>
                <strong>3. Navegador</strong> → Cookie: nome=valor (cabeçalho HTTP) →<br>
                <strong>4. PHP</strong> → $_COOKIE['nome']
            </div>
        </div>

        <!-- PERGUNTA 2: ARRAYS/OBJETOS -->
        <h2 style="color: #667eea; margin-top: 50px;">📦 Como armazenar Arrays/Objetos em Cookies?</h2>

        <div class="comparacao">
            <div class="box ruim">
                <h3>❌ ERRADO (Não funciona)</h3>
                <div class="codigo" style="font-size: 14px;">
                    $usuario = [<br>
                    &nbsp;&nbsp;'nome' => 'João',<br>
                    &nbsp;&nbsp;'idade' => 25<br>
                    ];<br>
                    <br>
                    // ❌ Salva "Array"<br>
                    setcookie('user', $usuario);
                </div>
                <p style="color: #f44336;"><strong>Problema:</strong> Cookie só aceita STRING!</p>
            </div>

            <div class="box bom">
                <h3>✅ CORRETO (json_encode)</h3>
                <div class="codigo" style="font-size: 14px;">
                    $usuario = [<br>
                    &nbsp;&nbsp;'nome' => 'João',<br>
                    &nbsp;&nbsp;'idade' => 25<br>
                    ];<br>
                    <br>
                    // ✅ Converte para JSON<br>
                    setcookie('user',<br>
                    &nbsp;&nbsp;<span class="destaque">json_encode($usuario)</span>,<br>
                    &nbsp;&nbsp;time() + 3600<br>
                    );
                </div>
                <p style="color: #4caf50;"><strong>Solução:</strong> Converter para JSON (string)</p>
            </div>
        </div>

        <!-- EXEMPLO COMPLETO -->
        <div class="alerta">
            <h3>📝 Exemplo Completo: Salvar e Ler Array</h3>

            <div class="codigo">
                // ========== SALVAR ==========<br>
                $usuario = [<br>
                &nbsp;&nbsp;&nbsp;&nbsp;'nome' => 'Maria',<br>
                &nbsp;&nbsp;&nbsp;&nbsp;'idade' => 25,<br>
                &nbsp;&nbsp;&nbsp;&nbsp;'email' => 'maria@email.com'<br>
                ];<br>
                <br>
                // Converte array para JSON (string)<br>
                $json = <span class="destaque">json_encode($usuario)</span>;<br>
                // Resultado: {"nome":"Maria","idade":25,"email":"maria@email.com"}<br>
                <br>
                // Salva no cookie<br>
                setcookie('usuario', $json, time() + 3600);<br>
                <br>
                // ========== LER ==========<br>
                if (isset($_COOKIE['usuario'])) {<br>
                &nbsp;&nbsp;&nbsp;&nbsp;// Converte JSON de volta para array<br>
                &nbsp;&nbsp;&nbsp;&nbsp;$usuario = <span class="destaque">json_decode($_COOKIE['usuario'], true)</span>;<br>
                &nbsp;&nbsp;&nbsp;&nbsp;<br>
                &nbsp;&nbsp;&nbsp;&nbsp;echo $usuario['nome']; // Maria<br>
                &nbsp;&nbsp;&nbsp;&nbsp;echo $usuario['idade']; // 25<br>
                }
            </div>
        </div>

        <!-- TESTE INTERATIVO -->
        <div class="teste">
            <h3>🧪 Teste Interativo</h3>

            <div style="margin: 20px 0;">
                <a href="?acao=simples"><button>Criar Cookie Simples</button></a>
                <a href="?acao=array"><button>Criar Cookie com Array</button></a>
                <a href="?acao=limpar"><button style="background: #f44336;">Limpar Cookies</button></a>
            </div>

            <div style="background: white; padding: 20px; border-radius: 8px; margin-top: 20px;">
                <h4>Cookies Atuais:</h4>
                <?php if (empty($_COOKIE)): ?>
                    <p>Nenhum cookie definido</p>
                <?php else: ?>
                    <table>
                        <tr>
                            <th>Nome</th>
                            <th>Valor</th>
                            <th>Tipo</th>
                        </tr>
                        <?php foreach ($_COOKIE as $nome => $valor): ?>
                            <tr>
                                <td><strong><?php echo $nome; ?></strong></td>
                                <td><code><?php echo htmlspecialchars($valor); ?></code></td>
                                <td>
                                    <?php
                                    $decoded = json_decode($valor, true);
                                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                        echo "Array (JSON)";
                                        echo "<pre style='margin-top:10px;'>" . print_r($decoded, true) . "</pre>";
                                    } else {
                                        echo "String simples";
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php endif; ?>
            </div>
        </div>

        <!-- BOAS PRÁTICAS -->
        <div style="background: #e8f5e9; padding: 30px; border-radius: 15px; margin-top: 30px;">
            <h2 style="color: #2e7d32; margin-top: 0;">✅ Melhores Práticas</h2>

            <table>
                <thead>
                    <tr>
                        <th>Situação</th>
                        <th>Solução</th>
                        <th>Código</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Salvar array</strong></td>
                        <td>json_encode()</td>
                        <td><code>setcookie('data', json_encode($array))</code></td>
                    </tr>
                    <tr>
                        <td><strong>Ler array</strong></td>
                        <td>json_decode()</td>
                        <td><code>$array = json_decode($_COOKIE['data'], true)</code></td>
                    </tr>
                    <tr>
                        <td><strong>Dados sensíveis</strong></td>
                        <td>❌ NÃO use cookies!</td>
                        <td>Use $_SESSION no servidor</td>
                    </tr>
                    <tr>
                        <td><strong>Segurança</strong></td>
                        <td>httponly, secure</td>
                        <td><code>setcookie('nome', 'valor', [..., true, true])</code></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- COOKIES vs SESSÃO -->
        <h2 style="color: #667eea; margin-top: 50px;">⚖️ Cookies vs Sessão</h2>

        <div class="comparacao">
            <div class="box" style="background: #fff3cd; border-color: #ffc107;">
                <h3>🍪 Cookies</h3>
                <ul>
                    <li>✅ Armazenado no navegador</li>
                    <li>✅ Persiste após fechar navegador</li>
                    <li>❌ Visível ao usuário (inseguro)</li>
                    <li>❌ Limite de 4KB</li>
                    <li><strong>Use para:</strong> Preferências, "lembrar-me"</li>
                </ul>
            </div>

            <div class="box" style="background: #e3f2fd; border-color: #2196f3;">
                <h3>🔐 Sessão</h3>
                <ul>
                    <li>✅ Armazenado no servidor</li>
                    <li>✅ Seguro (usuário não vê)</li>
                    <li>✅ Sem limite de tamanho</li>
                    <li>❌ Expira ao fechar navegador</li>
                    <li><strong>Use para:</strong> Login, dados sensíveis</li>
                </ul>
            </div>
        </div>

        <!-- RESUMO -->
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; border-radius: 15px; margin-top: 30px;">
            <h2 style="color: white; margin-top: 0;">📚 Resumo</h2>
            <ul style="font-size: 18px; line-height: 2;">
                <li><strong>Como funciona:</strong> Navegador envia cookies automaticamente no cabeçalho HTTP</li>
                <li><strong>Arrays/Objetos:</strong> Use json_encode() para salvar, json_decode() para ler</li>
                <li><strong>Segurança:</strong> Nunca armazene senhas ou dados sensíveis em cookies</li>
                <li><strong>Limite:</strong> 4KB por cookie</li>
            </ul>
        </div>
    </div>
</body>

</html>