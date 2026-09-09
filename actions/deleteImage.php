<?php
require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../includes/auth.php';
session_start();

require_login('../index.php');

session_write_close();

if (isset($_POST['click_delete_btn']) && isset($_POST['img_id']) && isset($_POST['filename'])) {

    $img_id = intval($_POST['img_id']);
    $filename = basename($_POST['filename']);

    $stmt = $db->prepare("DELETE FROM image WHERE id = ?");
    $stmt->bind_param("i", $img_id);

    if ($stmt->execute()) {

        $file_path = __DIR__ . "/../image/" . $filename;
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

    $stmt->close();
    $db->close();
} else {
    echo "Required parameters are missing.";
}
?>
