<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>== vs === em PHP</title>
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
        h1 {
            color: #667eea;
            text-align: center;
            border-bottom: 3px solid #667eea;
            padding-bottom: 15px;
        }
        .comparacao {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 30px 0;
        }
        .box {
            padding: 20px;
            border-radius: 10px;
        }
        .box-igual {
            background: #fff3cd;
            border: 3px solid #ffc107;
        }
        .box-identico {
            background: #e8f5e9;
            border: 3px solid #4caf50;
        }
        .box h2 {
            margin-top: 0;
            text-align: center;
        }
        .codigo {
            background: #2d3748;
            color: #68d391;
            padding: 15px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            margin: 10px 0;
            text-align: center;
            font-size: 18px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }
        th {
            background: #667eea;
            color: white;
        }
        tr:nth-child(even) {
            background: #f8f9fa;
        }
        .true {
            background: #4caf50;
            color: white;
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .false {
            background: #f44336;
            color: white;
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .exemplo {
            background: #f8f9fa;
            padding: 20px;
            margin: 20px 0;
            border-radius: 10px;
            border-left: 5px solid #667eea;
        }
        .destaque {
            background: #ffd700;
            padding: 3px 8px;
            border-radius: 3px;
            font-weight: bold;
        }
        .alerta {
            background: #ffebee;
            border: 2px solid #f44336;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>⚖️ Diferença entre == e === em PHP</h1>

        <!-- COMPARAÇÃO VISUAL -->
        <div class="comparacao">
            <div class="box box-igual">
                <h2>== (Igual)</h2>
                <div class="codigo">
                    Compara apenas VALOR
                </div>
                <p style="text-align: center; margin-top: 15px;">
                    Converte tipos automaticamente
                </p>
            </div>
            <div class="box box-identico">
                <h2>=== (Idêntico)</h2>
                <div class="codigo">
                    Compara VALOR + TIPO
                </div>
                <p style="text-align: center; margin-top: 15px;">
                    Não converte tipos
                </p>
            </div>
        </div>

        <!-- TABELA DE COMPARAÇÕES -->
        <h2 style="color: #667eea;">📊 Tabela de Comparações</h2>
        <table>
            <thead>
                <tr>
                    <th>Comparação</th>
                    <th>== (Igual)</th>
                    <th>=== (Idêntico)</th>
                    <th>Por quê?</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $testes = [
                    ['5 vs "5"', '5 == "5"', '5 === "5"', 'Tipos diferentes (int vs string)'],
                    ['0 vs false', '0 == false', '0 === false', 'Tipos diferentes (int vs bool)'],
                    ['1 vs true', '1 == true', '1 === true', 'Tipos diferentes (int vs bool)'],
                    ['"" vs false', '"" == false', '"" === false', 'Tipos diferentes (string vs bool)'],
                    ['null vs false', 'null == false', 'null === false', 'Tipos diferentes (null vs bool)'],
                    ['5 vs 5', '5 == 5', '5 === 5', 'Mesmo valor E mesmo tipo'],
                    ['"abc" vs "abc"', '"abc" == "abc"', '"abc" === "abc"', 'Mesmo valor E mesmo tipo']
                ];

                foreach($testes as $teste) {
                    $resultado_igual = eval("return {$teste[1]};");
                    $resultado_identico = eval("return {$teste[2]};");
                    
                    echo "<tr>";
                    echo "<td><strong>{$teste[0]}</strong></td>";
                    echo "<td><span class='" . ($resultado_igual ? 'true' : 'false') . "'>" . 
                         ($resultado_igual ? 'true' : 'false') . "</span></td>";
                    echo "<td><span class='" . ($resultado_identico ? 'true' : 'false') . "'>" . 
                         ($resultado_identico ? 'true' : 'false') . "</span></td>";
                    echo "<td>{$teste[3]}</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- EXEMPLO 1 -->
        <div class="exemplo">
            <h2>Exemplo 1️⃣: O Clássico (5 vs "5")</h2>
            
            <div class="codigo" style="text-align: left;">
$numero = 5;<br>
$texto = "5";<br>
<br>
// Compara apenas valor<br>
if ($numero <span class="destaque">==</span> $texto) {<br>
&nbsp;&nbsp;&nbsp;&nbsp;echo "São iguais!"; // ✅ EXECUTA<br>
}<br>
<br>
// Compara valor E tipo<br>
if ($numero <span class="destaque">===</span> $texto) {<br>
&nbsp;&nbsp;&nbsp;&nbsp;echo "São idênticos!"; // ❌ NÃO EXECUTA<br>
}
            </div>

            <div style="background: #e3f2fd; padding: 15px; border-radius: 8px; margin-top: 15px;">
                <strong>Teste real:</strong>
                <?php
                $numero = 5;
                $texto = "5";
                
                echo "<br>5 == \"5\" → " . ($numero == $texto ? "<span class='true'>true</span>" : "<span class='false'>false</span>");
                echo " (PHP converte \"5\" para 5)";
                
                echo "<br>5 === \"5\" → " . ($numero === $texto ? "<span class='true'>true</span>" : "<span class='false'>false</span>");
                echo " (int ≠ string)";
                ?>
            </div>
        </div>

        <!-- EXEMPLO 2 -->
        <div class="exemplo">
            <h2>Exemplo 2️⃣: Armadilha com 0 e false</h2>
            
            <div class="codigo" style="text-align: left;">
$valor = 0;<br>
<br>
if ($valor <span class="destaque">==</span> false) {<br>
&nbsp;&nbsp;&nbsp;&nbsp;echo "É false!"; // ✅ EXECUTA (ARMADILHA!)<br>
}<br>
<br>
if ($valor <span class="destaque">===</span> false) {<br>
&nbsp;&nbsp;&nbsp;&nbsp;echo "É false!"; // ❌ NÃO EXECUTA (CORRETO!)<br>
}
            </div>

            <div style="background: #e3f2fd; padding: 15px; border-radius: 8px; margin-top: 15px;">
                <strong>Teste real:</strong>
                <?php
                $valor = 0;
                
                echo "<br>0 == false → " . ($valor == false ? "<span class='true'>true</span>" : "<span class='false'>false</span>");
                echo " (0 é considerado falso)";
                
                echo "<br>0 === false → " . ($valor === false ? "<span class='true'>true</span>" : "<span class='false'>false</span>");
                echo " (int ≠ bool)";
                ?>
            </div>
        </div>

        <!-- EXEMPLO 3: PRÁTICO -->
        <div class="exemplo">
            <h2>Exemplo 3️⃣: Caso Prático (Verificar formulário)</h2>
            
            <div class="codigo" style="text-align: left;">
// Usuário enviou "0" no formulário<br>
$_POST['idade'] = "0";<br>
<br>
// ❌ ERRADO - Considera vazio!<br>
if ($_POST['idade'] == false) {<br>
&nbsp;&nbsp;&nbsp;&nbsp;echo "Idade não preenchida";<br>
}<br>
<br>
// ✅ CORRETO - Verifica corretamente<br>
if ($_POST['idade'] === "") {<br>
&nbsp;&nbsp;&nbsp;&nbsp;echo "Idade não preenchida";<br>
}
            </div>

            <div style="background: #e3f2fd; padding: 15px; border-radius: 8px; margin-top: 15px;">
                <strong>Teste real:</strong>
                <?php
                $_POST['idade'] = "0";
                
                echo "<br>\"0\" == false → " . ($_POST['idade'] == false ? "<span class='true'>true</span>" : "<span class='false'>false</span>");
                echo " (Considera 0 como vazio - ERRADO!)";
                
                echo "<br>\"0\" === \"\" → " . ($_POST['idade'] === "" ? "<span class='true'>true</span>" : "<span class='false'>false</span>");
                echo " (Verifica corretamente - CERTO!)";
                ?>
            </div>
        </div>

        <!-- ALERTA -->
        <div class="alerta">
            <h2 style="color: #f44336; margin-top: 0;">⚠️ CUIDADO: Bugs Comuns</h2>
            
            <div class="codigo" style="text-align: left; background: #ffcdd2; color: #c62828;">
// BUG 1: Verificar se existe no array<br>
$posicao = array_search('item', $array);<br>
if ($posicao <span class="destaque">==</span> false) { // ❌ ERRADO!<br>
&nbsp;&nbsp;&nbsp;&nbsp;// Se item está na posição 0, entra aqui!<br>
}<br>
if ($posicao <span class="destaque">===</span> false) { // ✅ CORRETO!<br>
&nbsp;&nbsp;&nbsp;&nbsp;// Só entra se realmente não encontrou<br>
}<br>
<br>
// BUG 2: Verificar retorno de função<br>
$resultado = strpos("texto", "x");<br>
if ($resultado <span class="destaque">==</span> false) { // ❌ ERRADO!<br>
&nbsp;&nbsp;&nbsp;&nbsp;// Se "x" está na posição 0, entra aqui!<br>
}<br>
if ($resultado <span class="destaque">===</span> false) { // ✅ CORRETO!<br>
&nbsp;&nbsp;&nbsp;&nbsp;// Só entra se não encontrou<br>
}
            </div>
        </div>

        <!-- QUANDO USAR -->
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; border-radius: 15px; margin-top: 30px;">
            <h2 style="color: white; margin-top: 0;">🎯 Quando Usar Cada Um?</h2>
            
            <table style="color: white;">
                <thead>
                    <tr style="background: rgba(255,255,255,0.2);">
                        <th>Situação</th>
                        <th>Use</th>
                        <th>Motivo</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="background: rgba(255,255,255,0.1);">
                        <td>Comparar tipos diferentes</td>
                        <td><strong>===</strong></td>
                        <td>Evita bugs</td>
                    </tr>
                    <tr style="background: rgba(255,255,255,0.05);">
                        <td>Verificar array_search()</td>
                        <td><strong>===</strong></td>
                        <td>Posição 0 ≠ false</td>
                    </tr>
                    <tr style="background: rgba(255,255,255,0.1);">
                        <td>Verificar strpos()</td>
                        <td><strong>===</strong></td>
                        <td>Posição 0 ≠ false</td>
                    </tr>
                    <tr style="background: rgba(255,255,255,0.05);">
                        <td>Comparar números</td>
                        <td><strong>== ou ===</strong></td>
                        <td>Ambos funcionam</td>
                    </tr>
                    <tr style="background: rgba(255,255,255,0.1);">
                        <td>Comparar strings</td>
                        <td><strong>== ou ===</strong></td>
                        <td>Ambos funcionam</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- RESUMO -->
        <div style="background: #e3f2fd; padding: 30px; border-radius: 15px; margin-top: 30px;">
            <h2 style="color: #1976d2; margin-top: 0;">📚 Resumo:</h2>
            <ul style="font-size: 18px; line-height: 2;">
                <li><strong>==</strong> = Compara apenas valor (converte tipos)</li>
                <li><strong>===</strong> = Compara valor + tipo (sem conversão)</li>
                <li><strong>Regra de ouro:</strong> Use === sempre que possível!</li>
                <li><strong>Exceção:</strong> Quando você QUER conversão de tipo</li>
            </ul>
        </div>
    </div>
</body>
</html>
