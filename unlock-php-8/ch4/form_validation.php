<?php
session_start();

// CSRF Token
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

// RATE LIMITING - Inicializa contador
if (!isset($_SESSION['uploads'])) {
    $_SESSION['uploads'] = [
        'count' => 0,
        'reset_time' => time() + 3600 // Reseta em 1 hora
    ];
}

// Reseta contador se passou 1 hora
if (time() > $_SESSION['uploads']['reset_time']) {
    $_SESSION['uploads'] = [
        'count' => 0,
        'reset_time' => time() + 3600
    ];
}

$limite = 5;
$restantes = $limite - $_SESSION['uploads']['count'];
$tempo_restante = $_SESSION['uploads']['reset_time'] - time();

// Botão de reset (apenas para testes)
if (isset($_GET['reset'])) {
    $_SESSION['uploads']['count'] = 0;
    header('Location: form_validation.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica CSRF
    if (!hash_equals($_SESSION['token'], $_POST['token'] ?? '')) {
        die('Invalid CSRF token');
    }
    
    // Verifica RATE LIMIT
    if ($_SESSION['uploads']['count'] >= $limite) {
        $minutos = ceil($tempo_restante / 60);
        die("❌ LIMITE ATINGIDO! Você já fez $limite uploads. 
             Aguarde $minutos minutos para fazer novo upload.");
    }
    
    $file = $_FILES['fileToUpload'];
    $fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if ($file['size'] > 500000) {
        echo "Sorry, your file is too large.";
        exit;
    }
    
    if ($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg") {
        echo "Sorry, only JPG, JPEG, & PNG files are allowed.";
        exit;
    }
    
    $resultMove = move_uploaded_file(
        $file['tmp_name'],
        'uploads/' . $file['name']
    );
    
    if (!$resultMove) {
        echo "Sorry, there was an error uploading your file.";
        exit;
    }
    
    // INCREMENTA CONTADOR
    $_SESSION['uploads']['count']++;
    $restantes--;
    
    echo "✅ The file has been uploaded.<br>";
    echo "Uploads restantes: $restantes de $limite";
    exit;
}
?>

<h2>Upload de Arquivo (com Rate Limiting)</h2>
<p><strong>Uploads restantes:</strong> <?php echo $restantes; ?> de <?php echo $limite; ?></p>
<p><strong>Reseta em:</strong> <?php echo ceil($tempo_restante / 60); ?> minutos</p>

<form action="form_validation.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload File" name="submit">
</form>

<hr>
<p><small>Sua sessão: <?php echo session_id(); ?></small></p>
<p><a href="?reset=1" style="color:red">🔄 Resetar contador (apenas teste)</a></p>
