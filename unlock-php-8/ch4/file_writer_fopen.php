<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Writer - fopen/fwrite version</title>
</head>

<body>
    <form method="post" action="file_writer_fopen.php">
        <label for="content">content:</label>
        <input type="text" id="content" name="content"><br>
        <input type="submit" value="send">
    </form>
</body>
</html>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $file_path = __DIR__ . DIRECTORY_SEPARATOR . "sample_fopen.txt";
    $content = $_POST['content'] ?? "This is a sample text file.\n";
    
    // Garantir quebra de linha
    if(!str_ends_with($content, "\n")) {
        $content .= "\n";
    }
    
    // Abrir arquivo em modo append
    $handle = fopen($file_path, 'a');
    
    if ($handle) {
        // Tentar obter lock exclusivo
        if (flock($handle, LOCK_EX)) {
            // Escrever conteúdo
            $bytes_written = fwrite($handle, $content);
            
            // Liberar lock
            flock($handle, LOCK_UN);
            
            if ($bytes_written !== false && $bytes_written > 0) {
                echo "File created/updated successfully at: " . $file_path;
                echo "<br>Bytes written: " . $bytes_written;
            } else {
                echo "Failed to write to the file.";
            }
        } else {
            echo "Failed to lock the file.";
        }
        
        // Fechar arquivo (importante!)
        fclose($handle);
    } else {
        echo "Failed to open the file.";
    }
}

?>