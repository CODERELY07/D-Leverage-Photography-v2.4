<?php
session_start();
require_once 'connection.php';
require_once 'includes/functions.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    http_response_code(403);
    exit('Unauthorized');
}
// Nothing below reads or writes $_SESSION again — release the lock so this
// request (which moves a file) doesn't block other requests on the same
// session.
session_write_close();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['img'])) {
    $album_id = intval($_POST['album_id']);
    $img = $_FILES['img'];

    // Basic validation
    if ($img['error'] === 0) {
        $imgName = sanitize_upload_filename($img['name']);
        $targetDir = "image/uploads/";
        $targetFile = $targetDir . time() . "_" . $imgName;

        if (move_uploaded_file($img['tmp_name'], $targetFile)) {
            // Save the path in DB
            $stmt = $db->prepare("INSERT INTO album_img (album_id, img) VALUES (?, ?)");
            $stmt->bind_param("is", $album_id, $targetFile);

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
