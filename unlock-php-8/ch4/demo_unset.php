<?php
session_start();

// Resetar exemplos
if (isset($_GET['reset'])) {
    session_destroy();
    header('Location: demo_unset.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entendendo unset() no PHP</title>
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

        .antes-depois {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 20px 0;
        }

        .box {
            padding: 15px;
            border-radius: 8px;
        }

        .antes {
            background: #fff3cd;
            border: 2px solid #ffc107;
        }

        .depois {
            background: #ffebee;
            border: 2px solid #f44336;
        }

        .destaque {
            background: #ffd700;
            padding: 3px 8px;
            border-radius: 3px;
            font-weight: bold;
            color: #000;
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

        .visual {
            background: white;
            border: 2px solid #667eea;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            text-align: center;
        }

        .item {
            display: inline-block;
            background: #4caf50;
            color: white;
            padding: 10px 20px;
            margin: 5px;
            border-radius: 5px;
            font-weight: bold;
        }

        .item.deletado {
            background: #f44336;
            text-decoration: line-through;
            opacity: 0.5;
        }

        .seta {
            font-size: 30px;
            color: #667eea;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>🗑️ Entendendo unset() no PHP</h1>
        <p style="text-align: center; font-size: 18px; color: #666;">
            <strong>unset()</strong> = Deletar/Remover uma variável da memória
        </p>

        <!-- EXEMPLO 1: BÁSICO -->
        <div class="exemplo">
            <h2>Exemplo 1️⃣: Deletar Variável Simples</h2>

            <div class="codigo">
                $nome = "João";<br>
                echo $nome; // Saída: João<br>
                <br>
                <span class="destaque">unset($nome);</span> // DELETA a variável<br>
                <br>
                echo $nome; // ❌ ERRO: variável não existe mais!
            </div>

            <div class="antes-depois">
                <div class="box antes">
                    <h3>✅ ANTES do unset()</h3>
                    <p><code>$nome</code> existe na memória</p>
                    <p>Valor: "João"</p>
                </div>
                <div class="box depois">
                    <h3>❌ DEPOIS do unset()</h3>
                    <p><code>$nome</code> não existe mais</p>
                    <p>Memória liberada</p>
                </div>
            </div>

            <div class="resultado">
                <strong>Teste real:</strong>
                <?php
                $nome = "João";
                echo "<br>Antes: \$nome = '$nome'";
                unset($nome);
                echo "<br>Depois: \$nome = " . (isset($nome) ? $nome : "❌ NÃO EXISTE");
                ?>
            </div>
        </div>

        <!-- EXEMPLO 2: ARRAY -->
        <div class="exemplo">
            <h2>Exemplo 2️⃣: Deletar Item de Array</h2>

            <div class="codigo">
                $frutas = ['Maçã', 'Banana', 'Laranja', 'Uva'];<br>
                <br>
                <span class="destaque">unset($frutas[1]);</span> // Remove "Banana" (índice 1)<br>
                <br>
                // Resultado: ['Maçã', 'Laranja', 'Uva']
            </div>

            <div class="visual">
                <h3>Visualização:</h3>
                <div>
                    <strong>ANTES:</strong><br>
                    <span class="item">0: Maçã</span>
                    <span class="item">1: Banana</span>
                    <span class="item">2: Laranja</span>
                    <span class="item">3: Uva</span>
                </div>
                <div class="seta">⬇️ unset($frutas[1])</div>
                <div>
                    <strong>DEPOIS:</strong><br>
                    <span class="item">0: Maçã</span>
                    <span class="item deletado">1: Banana</span>
                    <span class="item">2: Laranja</span>
                    <span class="item">3: Uva</span>
                </div>
            </div>

            <div class="resultado">
                <strong>Teste real:</strong>
                <?php
                $frutas = ['Maçã', 'Banana', 'Laranja', 'Uva'];
                echo "<br>Antes: " . implode(', ', $frutas);
                unset($frutas[1]);
                echo "<br>Depois: " . implode(', ', $frutas);
                ?>
            </div>
        </div>

        <!-- EXEMPLO 3: SESSÃO -->
        <div class="exemplo">
            <h2>Exemplo 3️⃣: Deletar Variável de Sessão (Muito Usado!)</h2>

            <div class="codigo">
                session_start();<br>
                $_SESSION['usuario'] = "João";<br>
                $_SESSION['email'] = "joao@email.com";<br>
                <br>
                <span class="destaque">unset($_SESSION['email']);</span> // Remove apenas o email<br>
                <br>
                // $_SESSION['usuario'] ainda existe<br>
                // $_SESSION['email'] foi deletado
            </div>

            <div class="resultado">
                <strong>Teste real:</strong>
                <?php
                $_SESSION['usuario'] = "João";
                $_SESSION['email'] = "joao@email.com";

                echo "<br><strong>Antes:</strong>";
                echo "<br>Usuário: " . $_SESSION['usuario'];
                echo "<br>Email: " . $_SESSION['email'];

                unset($_SESSION['email']);

                echo "<br><br><strong>Depois do unset(\$_SESSION['email']):</strong>";
                echo "<br>Usuário: " . $_SESSION['usuario'];
                echo "<br>Email: " . (isset($_SESSION['email']) ? $_SESSION['email'] : "❌ NÃO EXISTE");
                ?>
            </div>
        </div>

        <!-- EXEMPLO 4: MÚLTIPLAS VARIÁVEIS -->
        <div class="exemplo">
            <h2>Exemplo 4️⃣: Deletar Várias Variáveis de Uma Vez</h2>

            <div class="codigo">
                $a = 1;<br>
                $b = 2;<br>
                $c = 3;<br>
                <br>
                <span class="destaque">unset($a, $b, $c);</span> // Deleta as 3 de uma vez!
            </div>

            <div class="resultado">
                <strong>Teste real:</strong>
                <?php
                $a = 1;
                $b = 2;
                $c = 3;
                echo "<br>Antes: \$a=$a, \$b=$b, \$c=$c";
                unset($a, $b, $c);
                echo "<br>Depois: \$a=" . (isset($a) ? $a : "❌") .
                    ", \$b=" . (isset($b) ? $b : "❌") .
                    ", \$c=" . (isset($c) ? $c : "❌");
                ?>
            </div>
        </div>

        <!-- CASOS DE USO -->
        <div class="exemplo" style="border-left-color: #28a745;">
            <h2>💡 Quando Usar unset()?</h2>

            <table>
                <thead>
                    <tr>
                        <th>Situação</th>
                        <th>Exemplo</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Logout de usuário</strong></td>
                        <td><code>unset($_SESSION['usuario']);</code></td>
                    </tr>
                    <tr>
                        <td><strong>Remover item do carrinho</strong></td>
                        <td><code>unset($_SESSION['carrinho'][$id]);</code></td>
                    </tr>
                    <tr>
                        <td><strong>Limpar dados temporários</strong></td>
                        <td><code>unset($dados_temp);</code></td>
                    </tr>
                    <tr>
                        <td><strong>Liberar memória</strong></td>
                        <td><code>unset($array_grande);</code></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- DIFERENÇA: unset vs null -->
        <div class="exemplo" style="border-left-color: #ffc107;">
            <h2>⚠️ Diferença: unset() vs = null</h2>

            <div class="antes-depois">
                <div class="box antes">
                    <h3>unset($var)</h3>
                    <div class="codigo" style="font-size: 14px;">
                        $nome = "João";<br>
                        unset($nome);<br>
                        <br>
                        // Variável NÃO EXISTE<br>
                        isset($nome); // false
                    </div>
                    <p>✅ Deleta completamente</p>
                </div>
                <div class="box depois">
                    <h3>$var = null</h3>
                    <div class="codigo" style="font-size: 14px;">
                        $nome = "João";<br>
                        $nome = null;<br>
                        <br>
                        // Variável EXISTE mas é null<br>
                        isset($nome); // false
                    </div>
                    <p>⚠️ Variável existe, valor é null</p>
                </div>
            </div>
        </div>

        <!-- EXEMPLO PRÁTICO -->
        <div class="exemplo" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none;">
            <h2 style="color: white;">🎯 Exemplo Prático: Sistema de Login</h2>

            <div class="codigo">
                // LOGIN<br>
                session_start();<br>
                $_SESSION['usuario_id'] = 123;<br>
                $_SESSION['usuario_nome'] = "João";<br>
                $_SESSION['usuario_email'] = "joao@email.com";<br>
                <br>
                // LOGOUT<br>
                <span class="destaque">unset($_SESSION['usuario_id']);</span><br>
                <span class="destaque">unset($_SESSION['usuario_nome']);</span><br>
                <span class="destaque">unset($_SESSION['usuario_email']);</span><br>
                <br>
                // Ou deletar tudo de uma vez:<br>
                session_destroy(); // Deleta TODA a sessão
            </div>
        </div>

        <!-- RESUMO -->
        <div style="background: #e3f2fd; padding: 30px; border-radius: 15px; margin-top: 30px;">
            <h2 style="color: #1976d2; margin-top: 0;">📚 Resumo:</h2>
            <ul style="font-size: 18px; line-height: 2;">
                <li><strong>unset($var)</strong> = Deleta a variável</li>
                <li><strong>unset($array[0])</strong> = Remove item do array</li>
                <li><strong>unset($_SESSION['key'])</strong> = Remove da sessão</li>
                <li><strong>unset($a, $b, $c)</strong> = Deleta múltiplas</li>
            </ul>
        </div>
    </div>
</body>

</html>