<?php
session_start();
require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../includes/auth.php';

require_login('../index.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $image_id = intval($_POST['image_id']);

    $uploadDir = __DIR__ . '/../image/uploads/';
    $image_path = $uploadDir . basename($_POST['image_path']);

    if (file_exists($image_path)) {
        unlink($image_path);
    }

    $stmt = $db->prepare("DELETE FROM album_img WHERE id = ?");
    $stmt->bind_param("i", $image_id);

    if ($stmt->execute()) {

        header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '../admin/albums.php'));
        exit();
    } else {
        echo "Failed to delete image.";
    }
}
