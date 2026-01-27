<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entendendo isset() no PHP</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px;
        }

        .container {
            max-width: 1000px;
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

        .exemplo {
            background: #f8f9fa;
            padding: 25px;
            margin: 25px 0;
            border-radius: 10px;
            border-left: 5px solid #667eea;
        }

        .exemplo h2 {
            color: #667eea;
            margin-top: 0;
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

        .resultado {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #2196f3;
            margin: 15px 0;
        }

        .tabela-teste {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .tabela-teste th,
        .tabela-teste td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .tabela-teste th {
            background: #667eea;
            color: white;
        }

        .tabela-teste tr:nth-child(even) {
            background: #f8f9fa;
        }

        .true {
            color: #4caf50;
            font-weight: bold;
        }

        .false {
            color: #f44336;
            font-weight: bold;
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
            border-radius: 8px;
        }

        .box.certo {
            background: #e8f5e9;
            border: 2px solid #4caf50;
        }

        .box.errado {
            background: #ffebee;
            border: 2px solid #f44336;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>🔍 Entendendo isset() no PHP</h1>
        <p style="text-align: center; font-size: 18px; color: #666;">
            <strong>isset()</strong> = Verifica se uma variável EXISTE e NÃO é null
        </p>

        <!-- EXEMPLO 1: BÁSICO -->
        <div class="exemplo">
            <h2>Exemplo 1️⃣: O Básico</h2>

            <div class="codigo">
                $nome = "João";<br>
                <br>
                if (<span class="destaque">isset($nome)</span>) {<br>
                &nbsp;&nbsp;&nbsp;&nbsp;echo "Variável existe!";<br>
                } else {<br>
                &nbsp;&nbsp;&nbsp;&nbsp;echo "Variável NÃO existe!";<br>
                }
            </div>

            <div class="resultado">
                <strong>Teste real:</strong>
                <?php
                $nome = "João";
                echo "<br>isset(\$nome) = " . (isset($nome) ? "<span class='true'>true</span>" : "<span class='false'>false</span>");
                echo "<br>Resultado: Variável existe!";
                ?>
            </div>
        </div>

        <!-- TABELA DE TESTES -->
        <div class="exemplo">
            <h2>📊 Tabela de Testes: Quando isset() retorna true/false?</h2>

            <table class="tabela-teste">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>isset() retorna</th>
                        <th>Explicação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $testes = [
                        ['$nome = "João";', true, 'Variável existe com valor'],
                        ['$idade = 0;', true, 'Zero é um valor válido'],
                        ['$vazio = "";', true, 'String vazia é um valor'],
                        ['$nulo = null;', false, 'null = não existe'],
                        ['// $inexistente', false, 'Variável nunca foi criada'],
                        ['unset($nome);', false, 'Variável foi deletada']
                    ];

                    foreach ($testes as $teste) {
                        $cor = $teste[1] ? 'true' : 'false';
                        echo "<tr>";
                        echo "<td><code>{$teste[0]}</code></td>";
                        echo "<td class='$cor'>" . ($teste[1] ? 'true ✅' : 'false ❌') . "</td>";
                        echo "<td>{$teste[2]}</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>

            <div class="resultado">
                <strong>Testes reais:</strong>
                <?php
                $nome = "João";
                $idade = 0;
                $vazio = "";
                $nulo = null;

                echo "<br>isset(\$nome) = " . (isset($nome) ? "<span class='true'>true</span>" : "<span class='false'>false</span>");
                echo "<br>isset(\$idade) = " . (isset($idade) ? "<span class='true'>true</span>" : "<span class='false'>false</span>");
                echo "<br>isset(\$vazio) = " . (isset($vazio) ? "<span class='true'>true</span>" : "<span class='false'>false</span>");
                echo "<br>isset(\$nulo) = " . (isset($nulo) ? "<span class='true'>true</span>" : "<span class='false'>false</span>");
                echo "<br>isset(\$inexistente) = " . (isset($inexistente) ? "<span class='true'>true</span>" : "<span class='false'>false</span>");
                ?>
            </div>
        </div>

        <!-- EXEMPLO 2: FORMULÁRIOS -->
        <div class="exemplo">
            <h2>Exemplo 2️⃣: Verificar Dados de Formulário (Muito Usado!)</h2>

            <div class="codigo">
                if (<span class="destaque">isset($_POST['nome'])</span>) {<br>
                &nbsp;&nbsp;&nbsp;&nbsp;$nome = $_POST['nome'];<br>
                &nbsp;&nbsp;&nbsp;&nbsp;echo "Nome enviado: $nome";<br>
                } else {<br>
                &nbsp;&nbsp;&nbsp;&nbsp;echo "Formulário não foi enviado";<br>
                }
            </div>

            <div class="comparacao">
                <div class="box errado">
                    <h3>❌ SEM isset() (ERRO!)</h3>
                    <div class="codigo" style="font-size: 14px;">
                        $nome = $_POST['nome'];<br>
                        <br>
                        // ⚠️ ERRO se formulário<br>
                        // não foi enviado!
                    </div>
                </div>
                <div class="box certo">
                    <h3>✅ COM isset() (CORRETO)</h3>
                    <div class="codigo" style="font-size: 14px;">
                        if (isset($_POST['nome'])) {<br>
                        &nbsp;&nbsp;$nome = $_POST['nome'];<br>
                        }<br>
                        // ✅ Sem erros!
                    </div>
                </div>
            </div>
        </div>

        <!-- EXEMPLO 3: ARRAYS -->
        <div class="exemplo">
            <h2>Exemplo 3️⃣: Verificar Índice de Array</h2>

            <div class="codigo">
                $usuario = ['nome' => 'João', 'idade' => 25];<br>
                <br>
                if (<span class="destaque">isset($usuario['email'])</span>) {<br>
                &nbsp;&nbsp;&nbsp;&nbsp;echo $usuario['email'];<br>
                } else {<br>
                &nbsp;&nbsp;&nbsp;&nbsp;echo "Email não cadastrado";<br>
                }
            </div>

            <div class="resultado">
                <strong>Teste real:</strong>
                <?php
                $usuario = ['nome' => 'João', 'idade' => 25];

                echo "<br>isset(\$usuario['nome']) = " . (isset($usuario['nome']) ? "<span class='true'>true</span>" : "<span class='false'>false</span>");
                echo "<br>isset(\$usuario['email']) = " . (isset($usuario['email']) ? "<span class='true'>true</span>" : "<span class='false'>false</span>");

                if (isset($usuario['email'])) {
                    echo "<br>Email: " . $usuario['email'];
                } else {
                    echo "<br>Email: ❌ não cadastrado";
                }
                ?>
            </div>
        </div>

        <!-- EXEMPLO 4: MÚLTIPLAS VARIÁVEIS -->
        <div class="exemplo">
            <h2>Exemplo 4️⃣: Verificar Múltiplas Variáveis</h2>

            <div class="codigo">
                // Verifica se TODAS existem<br>
                if (<span class="destaque">isset($nome, $email, $idade)</span>) {<br>
                &nbsp;&nbsp;&nbsp;&nbsp;echo "Todos os dados foram preenchidos!";<br>
                } else {<br>
                &nbsp;&nbsp;&nbsp;&nbsp;echo "Faltam dados!";<br>
                }
            </div>

            <div class="resultado">
                <strong>Teste real:</strong>
                <?php
                $nome = "João";
                $email = "joao@email.com";
                // $idade não existe

                echo "<br>isset(\$nome, \$email, \$idade) = " .
                    (isset($nome, $email, $idade) ? "<span class='true'>true</span>" : "<span class='false'>false</span>");
                echo "<br>Resultado: Faltam dados! (idade não existe)";
                ?>
            </div>
        </div>

        <!-- CASOS DE USO -->
        <div class="exemplo" style="border-left-color: #28a745;">
            <h2>💡 Quando Usar isset()?</h2>

            <table class="tabela-teste">
                <thead>
                    <tr>
                        <th>Situação</th>
                        <th>Exemplo</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Verificar formulário enviado</strong></td>
                        <td><code>if (isset($_POST['submit'])) { ... }</code></td>
                    </tr>
                    <tr>
                        <td><strong>Verificar sessão de usuário</strong></td>
                        <td><code>if (isset($_SESSION['usuario'])) { ... }</code></td>
                    </tr>
                    <tr>
                        <td><strong>Verificar parâmetro GET</strong></td>
                        <td><code>if (isset($_GET['id'])) { ... }</code></td>
                    </tr>
                    <tr>
                        <td><strong>Verificar índice de array</strong></td>
                        <td><code>if (isset($array['chave'])) { ... }</code></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- ISSET vs EMPTY -->
        <div class="exemplo" style="border-left-color: #ffc107;">
            <h2>⚖️ isset() vs empty()</h2>

            <table class="tabela-teste">
                <thead>
                    <tr>
                        <th>Valor</th>
                        <th>isset()</th>
                        <th>empty()</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $comparacoes = [
                        ['"João"', 'true', 'false'],
                        ['""', 'true', 'true'],
                        ['0', 'true', 'true'],
                        ['null', 'false', 'true'],
                        ['não existe', 'false', 'true']
                    ];

                    foreach ($comparacoes as $comp) {
                        echo "<tr>";
                        echo "<td><code>\$var = {$comp[0]}</code></td>";
                        echo "<td class='" . ($comp[1] == 'true' ? 'true' : 'false') . "'>{$comp[1]}</td>";
                        echo "<td class='" . ($comp[2] == 'true' ? 'true' : 'false') . "'>{$comp[2]}</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>

            <div class="resultado">
                <strong>Diferença:</strong><br>
                • <strong>isset()</strong> = Existe? (true se existe e não é null)<br>
                • <strong>empty()</strong> = Está vazio? (true se vazio, 0, "", null, false)
            </div>
        </div>

        <!-- EXEMPLO PRÁTICO -->
        <div class="exemplo" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none;">
            <h2 style="color: white;">🎯 Exemplo Prático: Verificar Login</h2>

            <div class="codigo">
                session_start();<br>
                <br>
                // Verifica se usuário está logado<br>
                if (<span class="destaque">isset($_SESSION['usuario_id'])</span>) {<br>
                &nbsp;&nbsp;&nbsp;&nbsp;echo "Bem-vindo, " . $_SESSION['usuario_nome'];<br>
                } else {<br>
                &nbsp;&nbsp;&nbsp;&nbsp;header('Location: login.php');<br>
                &nbsp;&nbsp;&nbsp;&nbsp;exit;<br>
                }
            </div>
        </div>

        <!-- RESUMO -->
        <div style="background: #e3f2fd; padding: 30px; border-radius: 15px; margin-top: 30px;">
            <h2 style="color: #1976d2; margin-top: 0;">📚 Resumo:</h2>
            <ul style="font-size: 18px; line-height: 2;">
                <li><strong>isset($var)</strong> = Variável existe e não é null?</li>
                <li><strong>Retorna true</strong> = Variável existe</li>
                <li><strong>Retorna false</strong> = Não existe ou é null</li>
                <li><strong>Uso principal</strong> = Evitar erros ao acessar variáveis</li>
            </ul>
        </div>
    </div>
</body>

</html>