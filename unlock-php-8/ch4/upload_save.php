<form action="upload_save.php" method="post" enctype="multipart/form-data">
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload File" name="submit">
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['error'] == 0) {
        $uploadedFile = $_FILES['fileToUpload'];

        $uploadDir = __DIR__ . DIRECTORY_SEPARATOR . "uploads";
        $destinationPath = $uploadDir . DIRECTORY_SEPARATOR . basename($uploadedFile['name']);

        echo "destinationPath: " . $destinationPath . "<br>";

        // Create upload directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            $permissions = 0755; // rwxr-xr-x: owner can read/write/execute, group and others can read/execute
            if (!mkdir($uploadDir, $permissions, true)) {
                echo 'Failed to create upload directory';
                exit;
            }
            echo 'Upload directory created successfully<br>';
        }

        // Move the file to the destination location
        if (move_uploaded_file($uploadedFile['tmp_name'], $destinationPath)) {
            echo 'File successfully moved to ' . $destinationPath;
        } else {
            echo 'Failed to move the file';
        }
    } else {
        echo 'No files were uploaded or an error occurred during upload.';
    }
}
