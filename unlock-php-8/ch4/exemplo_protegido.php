<?php
session_start();
$_SESSION['saldo'] = $_SESSION['saldo'] ?? 10000;

// GERA TOKEN ÚNICO
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // VERIFICA O TOKEN
    if (!hash_equals($_SESSION['token'], $_POST['token'] ?? '')) {
        die('<h1 style="color:red">❌ ATAQUE CSRF BLOQUEADO!</h1>
             <p>O token não confere. Esta requisição não veio do nosso formulário.</p>');
    }
    
    $destino = $_POST['destino'];
    $valor = $_POST['valor'];
    
    $_SESSION['saldo'] -= $valor;
    echo "<h2 style='color:green'>✅ TRANSFERIDO: R$ $valor para $destino</h2>";
    echo "<h3>Saldo restante: R$ {$_SESSION['saldo']}</h3>";
}
?>

<h1>Banco Protegido (COM proteção CSRF)</h1>
<p>Saldo atual: R$ <?php echo $_SESSION['saldo']; ?></p>

<form method="POST">
    <!-- TOKEN SECRETO -->
    <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
    
    <label>Transferir para:</label>
    <input type="text" name="destino" required><br><br>
    
    <label>Valor:</label>
    <input type="number" name="valor" required><br><br>
    
    <button type="submit">Transferir</button>
</form>

<hr>
<p><strong>✅ PROTEGIDO:</strong> Apenas formulários deste site têm o token correto!</p>
<p><small>Token atual: <?php echo substr($_SESSION['token'], 0, 20); ?>...</small></p>
