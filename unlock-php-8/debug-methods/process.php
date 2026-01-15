<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Example form</title>
</head>

<body>
	<form method="post" action="process.php">
		<label for="name">name:</label>
		<input type="text" id="name" name="name"><br>
		<label for="email">Email:</label>
		<input type="text" id="email" name="email"><br>
		<input type="submit" value="Enviar">
	</form>
</body>

</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

	// ESTRATÉGIAS DE DEBUG SEM BREAKPOINTS
	
	// 1. LOG PERSONALIZADO
	function debugLog($message, $data = null) {
		$timestamp = date('Y-m-d H:i:s');
		$log = "[$timestamp] $message";
		if ($data !== null) {
			$log .= " - Data: " . print_r($data, true);
		}
		error_log($log . "\n", 3, "debug.log");
		echo "<div style='background:#f0f0f0; padding:10px; margin:5px;'><strong>DEBUG:</strong> $message";
		if ($data !== null) echo "<pre>" . print_r($data, true) . "</pre>";
		echo "</div>";
	}

	$data = array(
		'email' => $_POST['email']
	);
	
	// DEBUG: Acompanhar o fluxo
	debugLog("Dados recebidos do POST", $_POST);

	// Sintaxe avançada permite múltiplas configurações
	$filters = array(
		'email' => array(
			'filter' => FILTER_VALIDATE_EMAIL,
			'flags' => FILTER_FLAG_EMAIL_UNICODE,  // Permite caracteres unicode
			'options' => array(
				'default' => 'email_invalido@example.com'  // Valor padrão se inválido
			)
		)
	);
	
	debugLog("Filtros configurados", $filters);
	
	$validatedData = filter_var_array($data, $filters);
	
	debugLog("Resultado da validação", $validatedData);
	
	$email = $validatedData['email'];
	
	// 2. DEBUG CONDICIONAL
	if ($email === false) {
		debugLog("⚠️  EMAIL INVÁLIDO detectado!");
		debugLog("Input original era", $_POST['email']);
	} else {
		debugLog("✅ Email válido", $email);
	}
	
	// 3. ASSERT PARA TESTAR EXPECTATIVAS
	assert($email !== null, "Email não deveria ser null");
	
	// 4. STACK TRACE MANUAL
	if (empty($email)) {
		debugLog("STACK TRACE", debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));
	}
	
	// ============= OUTRAS TÉCNICAS DE DEBUG =============
	echo "<hr><h2>🔧 TÉCNICAS DE DEBUG:</h2>";
	
	// 5. CHECK DE TIPOS
	echo "<strong>Tipo do email:</strong> " . gettype($email) . "<br>";
	echo "<strong>É string?:</strong> " . (is_string($email) ? 'SIM' : 'NÃO') . "<br>";
	echo "<strong>É false?:</strong> " . ($email === false ? 'SIM' : 'NÃO') . "<br>";
	echo "<strong>Está vazio?:</strong> " . (empty($email) ? 'SIM' : 'NÃO') . "<br>";
	
	// 6. COMPARAÇÕES ÚTEIS
	echo "<h3>Comparações:</h3>";
	echo "email == false: " . ($email == false ? 'true' : 'false') . "<br>";
	echo "email === false: " . ($email === false ? 'true' : 'false') . "<br>";
	echo "empty(email): " . (empty($email) ? 'true' : 'false') . "<br>";
	echo "is_null(email): " . (is_null($email) ? 'true' : 'false') . "<br>";
	
	// 7. VARIÁVEIS SUPERGLOBAIS
	echo "<h3>Estado do Sistema:</h3>";
	echo "Método: " . $_SERVER['REQUEST_METHOD'] . "<br>";
	echo "IP: " . $_SERVER['REMOTE_ADDR'] . "<br>";
	echo "User Agent: " . $_SERVER['HTTP_USER_AGENT'] . "<br>";
	
	// 8. MEMORY E PERFORMANCE
	echo "<h3>Performance:</h3>";
	echo "Uso de memória: " . memory_get_usage(true) . " bytes<br>";
	echo "Pico de memória: " . memory_get_peak_usage(true) . " bytes<br>";

	//$name = htmlspecialchars($_POST['name']);


	//$email = $_POST['email'];





	// echo "name: " . htmlspecialchars($name) . "<br>";
	// echo "Email: " . htmlspecialchars($email) . "<br>";
}
?>