<?php
$usuarios = [
    ['nome' => 'João', 'idade' => 25, 'ativo' => true],
    ['nome' => 'Maria', 'idade' => 30, 'ativo' => false],
    ['nome' => 'Pedro', 'idade' => 22, 'ativo' => true]
];
$titulo = "Lista de Usuários";
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP + HTML: Qual a melhor forma?</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px;
            line-height: 1.6;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        h1 {
            color: #667eea;
            text-align: center;
            border-bottom: 3px solid #667eea;
            padding-bottom: 15px;
        }
        .metodo {
            background: #f8f9fa;
            padding: 25px;
            margin: 30px 0;
            border-radius: 10px;
            border-left: 5px solid #ccc;
        }
        .metodo.ruim { border-left-color: #dc3545; }
        .metodo.medio { border-left-color: #ffc107; }
        .metodo.bom { border-left-color: #28a745; }
        .metodo h2 {
            margin-top: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .codigo {
            background: #2d3748;
            color: #68d391;
            padding: 20px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            margin: 15px 0;
            overflow-x: auto;
            line-height: 1.8;
            font-size: 14px;
        }
        .resultado {
            background: white;
            border: 2px solid #667eea;
            padding: 20px;
            border-radius: 8px;
            margin: 15px 0;
        }
        .pros-cons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 15px 0;
        }
        .pros, .cons {
            padding: 15px;
            border-radius: 8px;
        }
        .pros {
            background: #e8f5e9;
            border-left: 4px solid #4caf50;
        }
        .cons {
            background: #ffebee;
            border-left: 4px solid #f44336;
        }
        .pros h4, .cons h4 {
            margin-top: 0;
        }
        ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .destaque {
            background: #ffd700;
            padding: 20px;
            border-radius: 10px;
            margin: 30px 0;
            border: 3px solid #ffb300;
        }
        .destaque h2 {
            color: #f57c00;
            margin-top: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        th, td {
            padding: 10px;
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
        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge.ativo {
            background: #4caf50;
            color: white;
        }
        .badge.inativo {
            background: #f44336;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎯 PHP + HTML: Qual a Forma MAIS LEGÍVEL?</h1>

        <!-- FORMA 1: HEREDOC -->
        <div class="metodo ruim">
            <h2>❌ Forma 1: HEREDOC (PIOR para HTML)</h2>
            
            <div class="codigo">
&lt;?php<br>
foreach($usuarios as $user) {<br>
&nbsp;&nbsp;&nbsp;&nbsp;echo &lt;&lt;&lt;HTML<br>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;div class="card"&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;h3&gt;$user[nome]&lt;/h3&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;p&gt;Idade: $user[idade]&lt;/p&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;/div&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;HTML;<br>
}<br>
?&gt;
            </div>

            <div class="pros-cons">
                <div class="cons">
                    <h4>❌ Contras:</h4>
                    <ul>
                        <li>Difícil de ler</li>
                        <li>Sem syntax highlighting do HTML</li>
                        <li>Difícil de debugar</li>
                        <li>Não funciona bem com IDEs</li>
                    </ul>
                </div>
                <div class="pros">
                    <h4>✅ Prós:</h4>
                    <ul>
                        <li>Útil para strings longas SEM HTML</li>
                        <li>Bom para SQL queries</li>
                    </ul>
                </div>
            </div>

            <div class="resultado">
                <strong>Resultado:</strong>
                <?php
                foreach($usuarios as $user) {
                    echo <<<HTML
                    <div style="background:#f0f0f0; padding:10px; margin:5px; border-radius:5px;">
                        <strong>$user[nome]</strong> - Idade: $user[idade]
                    </div>
HTML;
                }
                ?>
            </div>
        </div>

        <!-- FORMA 2: CONCATENAÇÃO -->
        <div class="metodo ruim">
            <h2>❌ Forma 2: Concatenação (HORRÍVEL)</h2>
            
            <div class="codigo">
&lt;?php<br>
foreach($usuarios as $user) {<br>
&nbsp;&nbsp;&nbsp;&nbsp;echo '&lt;div class="card"&gt;';<br>
&nbsp;&nbsp;&nbsp;&nbsp;echo '&lt;h3&gt;' . $user['nome'] . '&lt;/h3&gt;';<br>
&nbsp;&nbsp;&nbsp;&nbsp;echo '&lt;p&gt;Idade: ' . $user['idade'] . '&lt;/p&gt;';<br>
&nbsp;&nbsp;&nbsp;&nbsp;echo '&lt;/div&gt;';<br>
}<br>
?&gt;
            </div>

            <div class="pros-cons">
                <div class="cons">
                    <h4>❌ Contras:</h4>
                    <ul>
                        <li>Muitos pontos e aspas</li>
                        <li>Propenso a erros</li>
                        <li>Difícil de manter</li>
                        <li>Código confuso</li>
                    </ul>
                </div>
                <div class="pros">
                    <h4>✅ Prós:</h4>
                    <ul>
                        <li>Nenhum! Não use isso!</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- FORMA 3: SINTAXE ALTERNATIVA (MELHOR!) -->
        <div class="metodo bom">
            <h2>✅ Forma 3: Sintaxe Alternativa (MELHOR!)</h2>
            
            <div class="codigo">
&lt;?php foreach($usuarios as $user): ?&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;div class="card"&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;h3&gt;&lt;?php echo $user['nome']; ?&gt;&lt;/h3&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;p&gt;Idade: &lt;?php echo $user['idade']; ?&gt;&lt;/p&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;/div&gt;<br>
&lt;?php endforeach; ?&gt;
            </div>

            <div class="pros-cons">
                <div class="pros">
                    <h4>✅ Prós:</h4>
                    <ul>
                        <li><strong>Syntax highlighting funciona!</strong></li>
                        <li>HTML fica visível e legível</li>
                        <li>Fácil de debugar</li>
                        <li>IDEs entendem o código</li>
                        <li>Padrão da indústria</li>
                    </ul>
                </div>
                <div class="cons">
                    <h4>❌ Contras:</h4>
                    <ul>
                        <li>Nenhum significativo!</li>
                    </ul>
                </div>
            </div>

            <div class="resultado">
                <strong>Resultado:</strong>
                <?php foreach($usuarios as $user): ?>
                    <div style="background:#e8f5e9; padding:10px; margin:5px; border-radius:5px; border-left:4px solid #4caf50;">
                        <strong><?php echo $user['nome']; ?></strong> - Idade: <?php echo $user['idade']; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- DESTAQUE -->
        <div class="destaque">
            <h2>🏆 VENCEDOR: Sintaxe Alternativa</h2>
            <p style="font-size: 18px; line-height: 1.8;">
                <strong>Use esta sintaxe quando misturar PHP com HTML:</strong>
            </p>
            <div class="codigo">
&lt;?php if($condicao): ?&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;p&gt;HTML aqui&lt;/p&gt;<br>
&lt;?php endif; ?&gt;<br>
<br>
&lt;?php foreach($array as $item): ?&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;li&gt;&lt;?php echo $item; ?&gt;&lt;/li&gt;<br>
&lt;?php endforeach; ?&gt;
            </div>
        </div>

        <!-- EXEMPLO COMPLETO -->
        <h2 style="color: #667eea; margin-top: 40px;">📋 Exemplo Completo (Forma Correta)</h2>
        
        <div class="codigo">
&lt;h2&gt;&lt;?php echo $titulo; ?&gt;&lt;/h2&gt;<br>
<br>
&lt;table&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;thead&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;tr&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;th&gt;Nome&lt;/th&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;th&gt;Idade&lt;/th&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;th&gt;Status&lt;/th&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/tr&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;/thead&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;tbody&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;?php foreach($usuarios as $user): ?&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;tr&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;&lt;?php echo $user['nome']; ?&gt;&lt;/td&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;&lt;?php echo $user['idade']; ?&gt;&lt;/td&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;?php if($user['ativo']): ?&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;span class="badge ativo"&gt;Ativo&lt;/span&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;?php else: ?&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;span class="badge inativo"&gt;Inativo&lt;/span&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;?php endif; ?&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/td&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/tr&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;?php endforeach; ?&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;/tbody&gt;<br>
&lt;/table&gt;
        </div>

        <div class="resultado">
            <h2><?php echo $titulo; ?></h2>
            
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Idade</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($usuarios as $user): ?>
                        <tr>
                            <td><?php echo $user['nome']; ?></td>
                            <td><?php echo $user['idade']; ?></td>
                            <td>
                                <?php if($user['ativo']): ?>
                                    <span class="badge ativo">Ativo</span>
                                <?php else: ?>
                                    <span class="badge inativo">Inativo</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- RESUMO FINAL -->
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; border-radius: 15px; margin-top: 40px;">
            <h2 style="color: white; margin-top: 0;">📚 Resumo Final:</h2>
            <table style="color: white; border: none;">
                <tr style="background: rgba(255,255,255,0.1);">
                    <th style="background: rgba(255,255,255,0.2);">Situação</th>
                    <th style="background: rgba(255,255,255,0.2);">Use</th>
                </tr>
                <tr style="background: rgba(255,255,255,0.05);">
                    <td><strong>PHP dentro de HTML</strong></td>
                    <td>✅ Sintaxe Alternativa (<?php if(): ?> ... <?php endif; ?>)</td>
                </tr>
                <tr style="background: rgba(255,255,255,0.1);">
                    <td><strong>SQL Queries longas</strong></td>
                    <td>✅ HEREDOC</td>
                </tr>
                <tr style="background: rgba(255,255,255,0.05);">
                    <td><strong>Emails/Textos longos</strong></td>
                    <td>✅ HEREDOC</td>
                </tr>
                <tr style="background: rgba(255,255,255,0.1);">
                    <td><strong>Templates HTML</strong></td>
                    <td>❌ NUNCA use HEREDOC</td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
