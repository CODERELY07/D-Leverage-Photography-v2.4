<?php
require_once 'connection.php'; // Ensure this file connects to your database
session_start();

// Redirect if already logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php'); 
    exit();
}

if (isset($_POST['click_delete_btn']) && isset($_POST['img_id']) && isset($_POST['filename'])) {
    // Sanitize inputs
    $img_id = intval($_POST['img_id']); // Assuming img_id is an integer
    $filename = basename($_POST['filename']); // Use basename() to prevent directory traversal attacks

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $db->prepare("DELETE FROM image WHERE id = ?");
    $stmt->bind_param("i", $img_id);

    if ($stmt->execute()) {
        // Delete the image file
        $file_path = "image/" . $filename;
        if (file_exists($file_path)) {
            if (unlink($file_path)) {
                echo "Data and file successfully deleted.";
            } else {
                echo "File could not be deleted.";
            }
        } else {
            echo "File does not exist.";
        }
    } else {
        echo "Error deleting record from database: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $db->close();
} else {
    echo "Required parameters are missing.";
}
?>
