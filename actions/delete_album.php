<?php
require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/flash.php';
session_start();

require_login('../index.php');

if (isset($_POST['delete_id']) && isset($_POST['delete_img'])) {
    $id = (int) $_POST['delete_id'];

    $name = basename($_POST['delete_img']);

    $stmt = $db->prepare("DELETE FROM album WHERE id = ?");
    $stmt->bind_param('i', $id);
    $result = $stmt->execute();
    $stmt->close();

    if ($result) {

        $image_path = __DIR__ . '/../image/upload-album/' . $name;
        if (file_exists($image_path)) {
            unlink($image_path);
        } else {
            set_flash("Image file not found.");
        }
        set_flash("Deleted successfully");
    } else {
        set_flash("Delete Failed");
    }
    header("Location: ../admin/albums.php");
}
?>
