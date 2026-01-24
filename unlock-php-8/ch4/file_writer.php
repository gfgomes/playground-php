<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Example form</title>
</head>

<body>
    <form method="post" action="file_writer.php">
        <label for="content">content:</label>
        <input type="text" id="content" name="content"><br>
        <input type="submit" value="send">
    </form>
</body>
</html>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $file_path = __DIR__ . DIRECTORY_SEPARATOR . "sample.txt";
    $content = $_POST['content'] ?? "This is a sample text file.\n";

    if(!str_ends_with($content, "\n")) {
        $content .= "\n";
    }
    
    $result = file_put_contents($file_path, $content, FILE_APPEND | LOCK_EX);
    
    if ($result !== false) {
        echo "File created/updated successfully at: " . $file_path;
    } else {
        echo "Failed to create/update the file.";
    }
}