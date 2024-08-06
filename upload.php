<?php
session_start();
// Redirect if already logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php'); 
    exit();
}
require_once 'connection.php';
error_reporting(E_ALL); // Show all errors for debugging purposes

$msg = "";

if (isset($_POST['upload'])) {
    $category = $_POST['category'];
    if ($category != "add" || $category != "+") {
        $total = count($_FILES['uploadImg']['name']);

        for ($i = 0; $i < $total; $i++) {
            // Retrieve file details
            $filename = $_FILES["uploadImg"]["name"][$i];
            $tempname = $_FILES["uploadImg"]["tmp_name"][$i];

            // Check if file already exists in database
            $stmt_check = $db->prepare("SELECT id FROM image WHERE filename = ?");
            $stmt_check->bind_param("s", $filename);
            $stmt_check->execute();
            $stmt_check->store_result();

            if ($stmt_check->num_rows == 0) { // File does not exist in database
                // Prepare and execute insert statement
                $stmt = $db->prepare("INSERT INTO image (filename, category) VALUES (?, ?)");
                $stmt->bind_param("ss", $filename, $category);

                // Move uploaded file to desired location
                $folder = "./image/" . $filename;

                if (move_uploaded_file($tempname, $folder)) {
                    // Execute prepared statement to insert into database
                    if ($stmt->execute()) {
                        $_SESSION['status'] = "Image uploaded successfully!";
                    } else {
                        $_SESSION['status'] = "Failed to insert image into database!";
                    }
                } else {
                    $_SESSION['status'] = "Failed to upload image!";
                }
                
                // Close statement
                $stmt->close();
            } else {
                $_SESSION['status'] = "File '$filename' already exists in database. Skipped.";
            }

            // Close check statement
            $stmt_check->close();
        }
    }
    // Close database connection
    $db->close();
    echo header("Location: upload-portfolio.php");
    exit();
}
?>
