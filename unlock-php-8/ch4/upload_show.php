<form action="upload_show.php" method="post" enctype="multipart/form-data">
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload File" name="submit">
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //Check if the file was uploaded
    if (isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['error'] == 0) {

        //     $phpFileUploadErrors = array(
        //     0 => 'There is no error, the file uploaded with success',
        //     1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        //     2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
        //     3 => 'The uploaded file was only partially uploaded',
        //     4 => 'No file was uploaded',
        //     6 => 'Missing a temporary folder',
        //     7 => 'Failed to write file to disk.',
        //     8 => 'A PHP extension stopped the file upload.',
        // );

        $uploadedFile = $_FILES['fileToUpload'];
        echo 'Original file name: ' . $uploadedFile['name'] . '<br>';
        echo 'File type: ' . $uploadedFile['type'] . '<br>';
        echo 'File size: ' . $uploadedFile['size'] . '<br>';
        echo 'Temporary file name on server: ' . $uploadedFile['tmp_name'] . '<br>';
    } else {
        echo 'No file was uploaded or an error occurred during upload.<br>';
    }
}
