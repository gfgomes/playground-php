<?php
$list = [
    ['number' => 1, 'name' => 'Notebook'],
    ['number' => 2, 'name' => 'Mouse'],
    ['number' => 3, 'name' => 'Teclado']
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entendendo HEREDOC</title>
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
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        h1 { color: #667eea; border-bottom: 3px solid #667eea; padding-bottom: 10px; }
        h2 { color: #667eea; margin-top: 30px; }
        .metodo {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            border-left: 5px solid #667eea;
        }
        .codigo {
            background: #2d3748;
            color: #68d391;
            padding: 15px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            margin: 10px 0;
            overflow-x: auto;
            line-height: 1.8;
            font-size: 14px;
        }
        .resultado {
            background: white;
            border: 2px solid #667eea;
            padding: 15px;
            border-radius: 8px;
            margin: 10px 0;
        }
        .destaque {
            background: #ffd700;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
        }
        ul { list-style: none; padding: 0; }
        li {
            background: #f0f0f0;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
            border-left: 4px solid #667eea;
        }
        .comparacao {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 20px 0;
        }
        .box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
        }
        .box.ruim { border: 3px solid #dc3545; }
        .box.bom { border: 3px solid #28a745; }
        .explicacao {
            background: #fff3cd;
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #ffc107;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📝 Entendendo HEREDOC em PHP</h1>
        <p>Veja 3 formas de fazer a mesma coisa e entenda por que HEREDOC é melhor!</p>

        <!-- MÉTODO 1: TRADICIONAL (RUIM) -->
        <div class="metodo">
            <h2>❌ Método 1: Tradicional (Confuso)</h2>
            <div class="codigo">
&lt;ul&gt;<br>
&lt;?php foreach($list as $item) { ?&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;li id="itm-&lt;?php echo $item['number']; ?&gt;"&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Item &lt;?php echo $item['name']; ?&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;/li&gt;<br>
&lt;?php } ?&gt;<br>
&lt;/ul&gt;
            </div>
            <p><strong>Problema:</strong> Muitas tags PHP abrindo e fechando (<?php ?>) - código confuso!</p>
            
            <div class="resultado">
                <strong>Resultado:</strong>
                <ul>
                <?php foreach($list as $item) { ?>
                    <li id="itm-<?php echo $item['number']; ?>">
                        Item <?php echo $item['name']; ?>
                    </li>
                <?php } ?>
                </ul>
            </div>
        </div>

        <!-- MÉTODO 2: CONCATENAÇÃO (PIOR) -->
        <div class="metodo">
            <h2>❌ Método 2: Concatenação (Horrível)</h2>
            <div class="codigo">
&lt;ul&gt;<br>
&lt;?php<br>
foreach($list as $item) {<br>
&nbsp;&nbsp;&nbsp;&nbsp;echo '&lt;li id="itm-' . $item['number'] . '"&gt;';<br>
&nbsp;&nbsp;&nbsp;&nbsp;echo 'Item ' . $item['name'];<br>
&nbsp;&nbsp;&nbsp;&nbsp;echo '&lt;/li&gt;';<br>
}<br>
?&gt;<br>
&lt;/ul&gt;
            </div>
            <p><strong>Problema:</strong> Muitos pontos (.) e aspas - difícil de ler e manter!</p>
            
            <div class="resultado">
                <strong>Resultado:</strong>
                <ul>
                <?php
                foreach($list as $item) {
                    echo '<li id="itm-' . $item['number'] . '">';
                    echo 'Item ' . $item['name'];
                    echo '</li>';
                }
                ?>
                </ul>
            </div>
        </div>

        <!-- MÉTODO 3: HEREDOC (BOM!) -->
        <div class="metodo" style="border-left-color: #28a745;">
            <h2>✅ Método 3: HEREDOC (Elegante!)</h2>
            <div class="codigo">
&lt;ul&gt;<br>
&lt;?php foreach($list as $item): echo<br>
<span class="destaque">&lt;&lt;&lt;ITEM</span><br>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;li id="itm-$item[number]"&gt;Item $item[name]&lt;/li&gt;<br>
<span class="destaque">ITEM</span>;<br>
endforeach; ?&gt;<br>
&lt;/ul&gt;
            </div>
            <p><strong>Vantagem:</strong> Uma única tag PHP, variáveis diretas, código limpo!</p>
            
            <div class="resultado">
                <strong>Resultado:</strong>
                <ul>
                <?php foreach($list as $item): echo <<<ITEM
    <li id="itm-$item[number]">Item $item[name]</li>
ITEM;
                endforeach; ?>
                </ul>
            </div>
        </div>

        <!-- EXPLICAÇÃO DETALHADA -->
        <div class="explicacao">
            <h2>🔍 Como funciona o HEREDOC?</h2>
            
            <div class="codigo" style="background: white; color: #333; border: 2px solid #667eea;">
echo <span class="destaque">&lt;&lt;&lt;IDENTIFICADOR</span><br>
&nbsp;&nbsp;&nbsp;&nbsp;Aqui você escreve o que quiser<br>
&nbsp;&nbsp;&nbsp;&nbsp;Pode usar $variaveis diretamente<br>
&nbsp;&nbsp;&nbsp;&nbsp;Múltiplas linhas sem problema<br>
<span class="destaque">IDENTIFICADOR</span>;
            </div>

            <h3 style="margin-top: 20px;">📋 Regras:</h3>
            <ol style="margin-left: 20px; line-height: 2;">
                <li><code>&lt;&lt;&lt;NOME</code> - Inicia o bloco (NOME pode ser qualquer palavra)</li>
                <li>Escreva o conteúdo (HTML, texto, etc.)</li>
                <li>Use variáveis diretamente: <code>$variavel</code> ou <code>$array[chave]</code></li>
                <li><code>NOME;</code> - Fecha o bloco (mesmo NOME do início)</li>
            </ol>
        </div>

        <!-- COMPARAÇÃO VISUAL -->
        <h2>📊 Comparação Visual:</h2>
        <div class="comparacao">
            <div class="box ruim">
                <h3 style="color: #dc3545;">❌ Tradicional</h3>
                <ul style="list-style: disc; margin-left: 20px;">
                    <li>Muitas tags <?php ?></li>
                    <li>Difícil de ler</li>
                    <li>Propenso a erros</li>
                </ul>
            </div>
            <div class="box bom">
                <h3 style="color: #28a745;">✅ HEREDOC</h3>
                <ul style="list-style: disc; margin-left: 20px;">
                    <li>Uma tag PHP</li>
                    <li>Código limpo</li>
                    <li>Fácil manutenção</li>
                </ul>
            </div>
        </div>

        <!-- EXEMPLO PRÁTICO -->
        <h2>💡 Exemplo Prático Completo:</h2>
        <div class="codigo">
&lt;?php<br>
$nome = "João";<br>
$idade = 25;<br>
<br>
echo &lt;&lt;&lt;HTML<br>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;div class="card"&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;h2&gt;$nome&lt;/h2&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;p&gt;Idade: $idade anos&lt;/p&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;/div&gt;<br>
HTML;<br>
?&gt;
        </div>

        <div style="background: #e3f2fd; padding: 20px; border-radius: 10px; margin-top: 30px;">
            <h3 style="color: #1976d2;">🎯 Resumo:</h3>
            <p style="font-size: 16px; line-height: 1.8;">
                <strong>HEREDOC</strong> é uma forma elegante de escrever blocos de texto/HTML em PHP.<br>
                Use quando precisar de múltiplas linhas com variáveis.<br>
                Sintaxe: <code>&lt;&lt;&lt;NOME ... NOME;</code>
            </p>
        </div>
    </div>
</body>
</html>
