<?php
session_start();

// Inicializa tentativas com timestamp
if (!isset($_SESSION['attempts'])) {
    $_SESSION['attempts'] = [
        'count' => 0,
        'reset_time' => time() + 300 // 5 minutos (300 segundos)
    ];
}

// Reset automático se o tempo expirou
if (time() > $_SESSION['attempts']['reset_time']) {
    $_SESSION['attempts'] = [
        'count' => 0,
        'reset_time' => time() + 300
    ];
}

if ($_SESSION['attempts']['count'] >= 5) {
    $tempo_restante = $_SESSION['attempts']['reset_time'] - time();
    $minutos = ceil($tempo_restante / 60);
    echo "You have made too many attempts. Please try again in $minutos minutes.";
} else {
    // Process the form
    $_SESSION['attempts']['count']++;
    echo "Form processed. Attempts: {$_SESSION['attempts']['count']}/5";
}
