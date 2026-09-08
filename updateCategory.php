<?php
session_start();
require './connection.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    http_response_code(403);
    exit('Unauthorized');
}
// Nothing below reads or writes $_SESSION again — release the lock so this
// request doesn't block other requests on the same session.
session_write_close();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['img_id']) && isset($_POST['new_category'])) {
    $img_id = $_POST['img_id'];
    $new_category = $_POST['new_category'];

    $stmt = $db->prepare("UPDATE image SET category = ? WHERE id = ?");
    $stmt->execute([$new_category, $img_id]);

    echo "Category updated";
}
?>
