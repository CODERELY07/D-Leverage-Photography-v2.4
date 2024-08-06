<?php
require_once 'connection.php';
session_start();

// Redirect if already logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php'); 
    exit();
}

if(isset($_POST['delete_id']) && isset($_POST['delete_img'])){
    $id = $_POST['delete_id'];
    $name = $_POST['delete_img'];

    $query = "DELETE FROM album WHERE id = '$id'";
    $result = $db->query($query);

    if($result){
        // Delete the corresponding image file from the server
        $image_path = 'image/upload-album/' . $name;
        if(file_exists($image_path)){
            unlink($image_path); // Delete the file
        } else {
            $_SESSION['status'] = "Image file not found.";
        }
        $_SESSION['status'] = "Deleted successfully";
    } else {
        $_SESSION['status'] = "Delete Failed";
    }
    header("Location: albumImages.php");
}
?>