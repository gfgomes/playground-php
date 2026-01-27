<?php
session_start();
$_SESSION['saldo'] = $_SESSION['saldo'] ?? 10000;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $destino = $_POST['destino'];
    $valor = $_POST['valor'];
    
    $_SESSION['saldo'] -= $valor;
    echo "<h2 style='color:red'>TRANSFERIDO: R$ $valor para $destino</h2>";
    echo "<h3>Saldo restante: R$ {$_SESSION['saldo']}</h3>";
}
?>

<h1>Banco Vulnerável (SEM proteção CSRF)</h1>
<p>Saldo atual: R$ <?php echo $_SESSION['saldo']; ?></p>

<form method="POST">
    <label>Transferir para:</label>
    <input type="text" name="destino" required><br><br>
    
    <label>Valor:</label>
    <input type="number" name="valor" required><br><br>
    
    <button type="submit">Transferir</button>
</form>

<hr>
<p><strong>⚠️ PROBLEMA:</strong> Qualquer site pode enviar uma requisição POST para cá!</p>
