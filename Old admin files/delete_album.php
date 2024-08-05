<?php
require_once 'connection.php';

if(isset($_POST['delete_id']) && isset($_POST['delete_img'])){
    $id = $_POST['delete_id'];
    $name = $_POST['delete_img'];

    $query = "DELETE FROM album WHERE id = '$id'";
    $result = $db->query($query);

    if($result){
        // Delete the corresponding image file from the server
        $image_path = 'image/upload-album/' . $name;
        if(file_exists($image_path)){
            unlink($image_path); // Delete the file
        } else {
            echo "Image file not found.";
        }
        echo "Deleted successfully";
    } else {
        echo "Delete failed";
    }
}
?>