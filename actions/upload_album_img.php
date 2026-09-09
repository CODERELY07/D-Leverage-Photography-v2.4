<?php
session_start();
require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

require_ajax_login();

session_write_close();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['img'])) {
    $album_id = intval($_POST['album_id']);
    $img = $_FILES['img'];

    if ($img['error'] === 0) {
        $imgName = sanitize_upload_filename($img['name']);

        $relativeDir = "image/uploads/";
        $relativePath = $relativeDir . time() . "_" . $imgName;
        $absoluteTarget = __DIR__ . '/../' . $relativePath;

        if (move_uploaded_file($img['tmp_name'], $absoluteTarget)) {

            $stmt = $db->prepare("INSERT INTO album_img (album_id, img) VALUES (?, ?)");
            $stmt->bind_param("is", $album_id, $relativePath);

            if ($stmt->execute()) {
                echo "Image uploaded and linked to album successfully.";
            } else {
                echo "Database error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Failed to move uploaded file.";
        }
    } else {
        echo "Upload error: " . $img['error'];
    }
} else {
    echo "No file uploaded.";
}

$db->close();
?>
