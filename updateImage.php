<?php
session_start();
require 'connection.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    http_response_code(403);
    exit('Unauthorized');
}
// Nothing below reads or writes $_SESSION again — release the lock so this
// request (which moves a file) doesn't block other requests on the same
// session, like the page's own polling/upload calls.
session_write_close();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['new_image']) && isset($_POST['img_id'])) {
    $img_id = intval($_POST['img_id']);
    $newImage = $_FILES['new_image'];

    // Get current image filename from DB
    $stmt = $db->prepare("SELECT filename FROM image WHERE id = ?");
    $stmt->bind_param("i", $img_id);
    $stmt->execute();
    $stmt->bind_result($oldImage);
    $stmt->fetch();
    $stmt->close();

    // Delete old image
    if ($oldImage && file_exists("image/$oldImage")) {
        unlink("image/$oldImage");
    }

    // Save new image
    $newName = uniqid() . "_" . basename($newImage['name']);
    move_uploaded_file($newImage['tmp_name'], "image/$newName");

    // Update DB with new filename
    $stmt = $db->prepare("UPDATE image SET filename = ? WHERE id = ?");
    $stmt->bind_param("si", $newName, $img_id);
    $stmt->execute();
    $stmt->close();

    echo "Image updated successfully.";
} else {
    echo "Invalid request.";
}
