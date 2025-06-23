<?php
session_start();
require_once 'connection.php';

function get_size($size) {
    return number_format($size / 1024, 2);
}

$albumName = htmlspecialchars($_POST['album-name']);
$albumLink = htmlspecialchars($_POST['album-link']);
$albumCategory = htmlspecialchars($_POST['album-category']);
$albumId = isset($_POST['album-id']) ? intval($_POST['album-id']) : 0;

$uploadDir = 'image/upload-album/';
$albumImg = $_FILES['upload-album']['name'];
$tempFile = $_FILES['upload-album']['tmp_name'];

if ($albumId > 0) {
    // UPDATE logic
    if ($albumImg) {
        // Upload new image
        $newPath = $uploadDir . basename($albumImg);
        if (!move_uploaded_file($tempFile, $newPath)) {
            $_SESSION['status'] = "Failed to upload new image";
            header("Location: albumImages.php");
            exit();
        }

        // Get old image to delete
        $stmt = $db->prepare("SELECT album_img FROM album WHERE id = ?");
        $stmt->bind_param("i", $albumId);
        $stmt->execute();
        $stmt->bind_result($oldImg);
        $stmt->fetch();
        $stmt->close();

        if ($oldImg && file_exists($uploadDir . $oldImg)) {
            unlink($uploadDir . $oldImg);
        }

        // Update with new image
        $stmt = $db->prepare("UPDATE album SET album_name=?, album_link=?, album_img=?, album_category=? WHERE id=?");
        $stmt->bind_param("ssssi", $albumName, $albumLink, $albumImg, $albumCategory, $albumId);
    } else {
        // Update without changing image
        $stmt = $db->prepare("UPDATE album SET album_name=?, album_link=?, album_category=? WHERE id=?");
        $stmt->bind_param("sssi", $albumName, $albumLink, $albumCategory, $albumId);
    }

    if ($stmt->execute()) {
        $_SESSION['status'] = "Album updated successfully";
    } else {
        $_SESSION['status'] = "Update failed: " . $stmt->error;
    }
    $stmt->close();
} else {
    // INSERT logic
    if (!$albumImg || !$tempFile) {
        $_SESSION['status'] = "Please select an image";
        header("Location: albumImages.php");
        exit();
    }

    $newPath = $uploadDir . basename($albumImg);
    if (file_exists($newPath)) {
        $_SESSION['status'] = "IMAGE ALREADY EXISTS: " . $albumImg;
        header("Location: albumImages.php");
        exit();
    }

    if (move_uploaded_file($tempFile, $newPath)) {
        $stmt = $db->prepare("INSERT INTO album (album_name, album_link, album_img, album_category) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $albumName, $albumLink, $albumImg, $albumCategory);

        if ($stmt->execute()) {
            $_SESSION['status'] = "Album uploaded successfully";
        } else {
            $_SESSION['status'] = "Upload failed: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $_SESSION['status'] = "Upload failed";
    }
}

$db->close();
header("Location: albumImages.php");
exit();
?>
