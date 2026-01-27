<?php
// ========== EXEMPLO 1: O BÁSICO ==========
$nome = "Maria";
$idade = 30;

// Forma NORMAL (ruim para textos longos)
$texto1 = "Olá, meu nome é " . $nome . " e tenho " . $idade . " anos.";

// Forma HEREDOC (melhor!)
$texto2 = <<<TEXTO
Olá, meu nome é $nome e tenho $idade anos.
TEXTO;

// ========== EXEMPLO 2: HTML ==========
$produto = "Notebook";
$preco = 2999.90;

// HEREDOC com HTML
$card = <<<HTML
<div class="produto">
    <h2>$produto</h2>
    <p>Preço: R$ $preco</p>
    <button>Comprar</button>
</div>
HTML;

// ========== EXEMPLO 3: LISTA ==========
$frutas = ['Maçã', 'Banana', 'Laranja'];
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HEREDOC Super Simples</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }

        .passo {
            background: white;
            padding: 30px;
            margin: 20px 0;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .passo h2 {
            color: #667eea;
            border-bottom: 3px solid #667eea;
            padding-bottom: 10px;
        }

        .codigo {
            background: #2d3748;
            color: #68d391;
            padding: 20px;
            border-radius: 8px;
            font-family: monospace;
            margin: 15px 0;
            line-height: 1.8;
            overflow-x: auto;
        }

        .destaque {
            background: #ffd700;
            color: #000;
            padding: 3px 8px;
            border-radius: 3px;
            font-weight: bold;
        }

        .resultado {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #2196f3;
            margin: 15px 0;
        }

        .comparacao {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 20px 0;
        }

        .box {
            padding: 15px;
            border-radius: 8px;
        }

        .ruim {
            background: #ffebee;
            border: 2px solid #f44336;
        }

        .bom {
            background: #e8f5e9;
            border: 2px solid #4caf50;
        }

        .grande {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            padding: 20px;
            background: #667eea;
            color: white;
            border-radius: 10px;
            margin: 30px 0;
        }
    </style>
</head>

<body>
    <h1 style="text-align: center; color: #667eea;">📝 HEREDOC em 3 Passos Simples</h1>

    <!-- PASSO 1 -->
    <div class="passo">
        <h2>Passo 1️⃣: Entenda o Problema</h2>

        <p><strong>Você quer escrever isso:</strong></p>
        <div class="codigo">
            Olá, meu nome é Maria e tenho 30 anos.
        </div>

        <p><strong>Forma RUIM (concatenação):</strong></p>
        <div class="codigo">
            $texto = "Olá, meu nome é " . $nome . " e tenho " . $idade . " anos.";
        </div>
        <p>😵 Muitos pontos (.) e aspas - confuso!</p>

        <p><strong>Forma BOA (HEREDOC):</strong></p>
        <div class="codigo">
            $texto = <span class="destaque">&lt;&lt;&lt;FIM</span><br>
            Olá, meu nome é $nome e tenho $idade anos.<br>
            <span class="destaque">FIM</span>;
        </div>
        <p>😊 Limpo e fácil de ler!</p>

        <div class="resultado">
            <strong>Resultado:</strong> <?php echo $texto2; ?>
        </div>
    </div>

    <!-- PASSO 2 -->
    <div class="passo">
        <h2>Passo 2️⃣: A Sintaxe</h2>

        <div class="grande">
            &lt;&lt;&lt;PALAVRA<br>
            seu texto aqui<br>
            PALAVRA;
        </div>

        <div style="background: #fff3cd; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <h3>📋 Regras:</h3>
            <ol style="line-height: 2;">
                <li><strong>&lt;&lt;&lt;PALAVRA</strong> - Abre o bloco (PALAVRA pode ser qualquer nome)</li>
                <li>Escreva seu texto (pode ter várias linhas)</li>
                <li><strong>PALAVRA;</strong> - Fecha o bloco (mesma PALAVRA do início)</li>
            </ol>
        </div>

        <p><strong>Exemplo real:</strong></p>
        <div class="codigo">
            &lt;?php<br>
            $nome = "João";<br>
            <br>
            echo <span class="destaque">&lt;&lt;&lt;MENSAGEM</span><br>
            Bem-vindo, $nome!<br>
            Hoje é um ótimo dia.<br>
            <span class="destaque">MENSAGEM</span>;<br>
            ?&gt;
        </div>

        <div class="resultado">
            <strong>Saída:</strong><br>
            <?php
            $nome_teste = "João";
            echo <<<MENSAGEM
                    Bem-vindo, $nome_teste!<br>
                    Hoje é um ótimo dia.
                MENSAGEM;
            ?>
        </div>

        <!-- // A forma alternativa de sintaxe (usando : e endif) é recomendada quando misturando PHP com HTML
        // pois torna o código mais legível e evita confusão com as chaves {}
        <?php if (1 == 1): ?>
            <span>teste</span>
        <?php elseif (2 == 2): ?>
            <span>teste2</span>
        <?php else: ?>
            <span>teste3</span>
        <?php endif ?> -->
    </div>

    <!-- PASSO 3 -->
    <div class="passo">
        <h2>Passo 3️⃣: Exemplo com HTML</h2>

        <p><strong>Código:</strong></p>
        <div class="codigo">
            &lt;?php<br>
            $produto = "Notebook";<br>
            $preco = 2999.90;<br>
            <br>
            echo <span class="destaque">&lt;&lt;&lt;HTML</span><br>
            &lt;div class="produto"&gt;<br>
            &nbsp;&nbsp;&nbsp;&nbsp;&lt;h2&gt;$produto&lt;/h2&gt;<br>
            &nbsp;&nbsp;&nbsp;&nbsp;&lt;p&gt;Preço: R$ $preco&lt;/p&gt;<br>
            &nbsp;&nbsp;&nbsp;&nbsp;&lt;button&gt;Comprar&lt;/button&gt;<br>
            &lt;/div&gt;<br>
            <span class="destaque">HTML</span>;<br>
            ?&gt;
        </div>

        <div class="resultado">
            <strong>Resultado visual:</strong>
            <?php echo $card; ?>
        </div>
    </div>

    <!-- COMPARAÇÃO FINAL -->
    <div class="passo">
        <h2>🎯 Comparação Final</h2>

        <div class="comparacao">
            <div class="box ruim">
                <h3>❌ SEM HEREDOC</h3>
                <div class="codigo" style="font-size: 12px;">
                    echo "&lt;div&gt;";<br>
                    echo "&lt;h2&gt;" . $nome . "&lt;/h2&gt;";<br>
                    echo "&lt;p&gt;" . $idade . "&lt;/p&gt;";<br>
                    echo "&lt;/div&gt;";
                </div>
                <p>😵 Confuso e difícil</p>
            </div>

            <div class="box bom">
                <h3>✅ COM HEREDOC</h3>
                <div class="codigo" style="font-size: 12px;">
                    echo &lt;&lt;&lt;HTML<br>
                    &lt;div&gt;<br>
                    &nbsp;&nbsp;&lt;h2&gt;$nome&lt;/h2&gt;<br>
                    &nbsp;&nbsp;&lt;p&gt;$idade&lt;/p&gt;<br>
                    &lt;/div&gt;<br>
                    HTML;
                </div>
                <p>😊 Limpo e fácil</p>
            </div>
        </div>
    </div>

    <!-- EXEMPLO INTERATIVO -->
    <div class="passo">
        <h2>🎮 Teste Você Mesmo!</h2>

        <p>Veja este código funcionando:</p>
        <div class="codigo">
            &lt;?php<br>
            foreach(['Maçã', 'Banana', 'Laranja'] as $fruta) {<br>
            &nbsp;&nbsp;&nbsp;&nbsp;echo <span class="destaque">&lt;&lt;&lt;ITEM</span><br>
            &nbsp;&nbsp;&nbsp;&nbsp;&lt;div style="padding:10px; background:#f0f0f0; margin:5px;"&gt;<br>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;🍎 $fruta<br>
            &nbsp;&nbsp;&nbsp;&nbsp;&lt;/div&gt;<br>
            &nbsp;&nbsp;&nbsp;&nbsp;<span class="destaque">ITEM</span>;<br>
            }<br>
            ?&gt;
        </div>

        <div class="resultado">
            <strong>Resultado:</strong>
            <?php
            foreach ($frutas as $fruta) {
                echo <<<ITEM
                <div style="padding:10px; background:#f0f0f0; margin:5px; border-radius:5px;">
                    🍎 $fruta
                </div>
ITEM;
            }
            ?>
        </div>
    </div>

    <!-- RESUMO -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; border-radius: 15px; margin-top: 30px;">
        <h2 style="color: white; border: none;">📚 Resumo Ultra Simples:</h2>
        <div style="font-size: 18px; line-height: 2;">
            <p><strong>1.</strong> HEREDOC = forma de escrever texto longo</p>
            <p><strong>2.</strong> Sintaxe: <code style="background: rgba(255,255,255,0.2); padding: 5px 10px; border-radius: 5px;">&lt;&lt;&lt;NOME ... NOME;</code></p>
            <p><strong>3.</strong> Pode usar variáveis: <code style="background: rgba(255,255,255,0.2); padding: 5px 10px; border-radius: 5px;">$variavel</code></p>
            <p><strong>4.</strong> Perfeito para HTML dentro do PHP</p>
        </div>
    </div>
</body>

</html>