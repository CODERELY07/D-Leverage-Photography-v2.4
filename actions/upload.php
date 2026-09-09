<?php
session_start();
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/flash.php';
require_login('../index.php');

session_write_close();

require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../includes/functions.php';
error_reporting(E_ALL);

$status = "";

if (isset($_POST['upload'])) {
    $category = $_POST['category'];
    $total = count($_FILES['uploadImg']['name']);

    for ($i = 0; $i < $total; $i++) {

        $filename = sanitize_upload_filename($_FILES["uploadImg"]["name"][$i]);
        $tempname = $_FILES["uploadImg"]["tmp_name"][$i];

        $stmt_check = $db->prepare("SELECT id FROM image WHERE filename = ?");
        $stmt_check->bind_param("s", $filename);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows == 0) {

            $stmt = $db->prepare("INSERT INTO image (filename, category) VALUES (?, ?)");
            $stmt->bind_param("ss", $filename, $category);

            $folder = __DIR__ . "/../image/" . $filename;

            if (move_uploaded_file($tempname, $folder)) {

                if ($stmt->execute()) {
                    $status = "Image uploaded successfully!";
                } else {
                    $status = "Failed to insert image into database!";
                }
            } else {
                $status = "Failed to upload image!";
            }

            $stmt->close();
        } else {
            $status = "File '$filename' already exists in database. Skipped.";
        }

        $stmt_check->close();
    }

    $db->close();

    session_start();
    set_flash($status);
    session_write_close();

    header("Location: ../admin/portfolio.php");
    exit();
}
?>
