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

	$name = htmlspecialchars($_POST['name']);
	$email = $_POST['email'];

	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		echo "Invalid email format.";
		die();
	} elseif ($name === '') {
		echo 'name is required.';
		die();
	} else {
		echo "Valid email." . "<br/>";
		echo "name: " . htmlspecialchars($name) . "<br>";
		echo "Email: " . htmlspecialchars($email) . "<br>";
	}
}
?>