<?php
session_start();
require_once 'connection.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $image_id = intval($_POST['image_id']);
    $image_path = $_POST['image_path'];

    // Delete image file from server
    if (file_exists($image_path)) {
        unlink($image_path);
    }

    // Delete image record from DB
    $stmt = $db->prepare("DELETE FROM album_img WHERE id = ?");
    $stmt->bind_param("i", $image_id);

    if ($stmt->execute()) {
        // Optional: redirect back to referring page
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    } else {
        echo "Failed to delete image.";
    }
}
