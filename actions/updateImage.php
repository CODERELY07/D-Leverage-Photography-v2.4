<?php
session_start();
require __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../includes/auth.php';

require_ajax_login();

session_write_close();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['new_image']) && isset($_POST['img_id'])) {
    $img_id = intval($_POST['img_id']);
    $newImage = $_FILES['new_image'];

    $stmt = $db->prepare("SELECT filename FROM image WHERE id = ?");
    $stmt->bind_param("i", $img_id);
    $stmt->execute();
    $stmt->bind_result($oldImage);
    $stmt->fetch();
    $stmt->close();

    if ($oldImage && file_exists(__DIR__ . "/../image/$oldImage")) {
        unlink(__DIR__ . "/../image/$oldImage");
    }

    $newName = uniqid() . "_" . basename($newImage['name']);
    move_uploaded_file($newImage['tmp_name'], __DIR__ . "/../image/$newName");

    $stmt = $db->prepare("UPDATE image SET filename = ? WHERE id = ?");
    $stmt->bind_param("si", $newName, $img_id);
    $stmt->execute();
    $stmt->close();

    echo "Image updated successfully.";
} else {
    echo "Invalid request.";
}
