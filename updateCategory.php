<?php
require './connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['img_id']) && isset($_POST['new_category'])) {
    $img_id = $_POST['img_id'];
    $new_category = $_POST['new_category'];

    $stmt = $db->prepare("UPDATE image SET category = ? WHERE id = ?");
    $stmt->execute([$new_category, $img_id]);

    echo "Category updated";
}
?>
