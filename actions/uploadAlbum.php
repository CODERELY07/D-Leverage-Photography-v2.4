<?php
session_start();
require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/flash.php';

require_login('../index.php');

$albumName = $_POST['album-name'];
$albumLink = $_POST['album-link'];
$albumCategory = $_POST['album-category'];
$albumId = isset($_POST['album-id']) ? intval($_POST['album-id']) : 0;

$uploadDir = __DIR__ . '/../image/upload-album/';
$albumImg = $_FILES['upload-album']['name'] ? sanitize_upload_filename($_FILES['upload-album']['name']) : '';
$tempFile = $_FILES['upload-album']['tmp_name'];

if ($albumId > 0) {

    if ($albumImg) {

        $newPath = $uploadDir . basename($albumImg);
        if (!move_uploaded_file($tempFile, $newPath)) {
            set_flash("Failed to upload new image");
            header("Location: ../admin/albums.php");
            exit();
        }

        $stmt = $db->prepare("SELECT album_img FROM album WHERE id = ?");
        $stmt->bind_param("i", $albumId);
        $stmt->execute();
        $stmt->bind_result($oldImg);
        $stmt->fetch();
        $stmt->close();

        if ($oldImg && file_exists($uploadDir . $oldImg)) {
            unlink($uploadDir . $oldImg);
        }

        $stmt = $db->prepare("UPDATE album SET album_name=?, album_link=?, album_img=?, album_category=? WHERE id=?");
        $stmt->bind_param("ssssi", $albumName, $albumLink, $albumImg, $albumCategory, $albumId);
    } else {

        $stmt = $db->prepare("UPDATE album SET album_name=?, album_link=?, album_category=? WHERE id=?");
        $stmt->bind_param("sssi", $albumName, $albumLink, $albumCategory, $albumId);
    }

    if ($stmt->execute()) {
        set_flash("Album updated successfully");
    } else {
        set_flash("Update failed: " . $stmt->error);
    }
    $stmt->close();
} else {

    if (!$albumImg || !$tempFile) {
        set_flash("Please select an image");
        header("Location: ../admin/albums.php");
        exit();
    }

    $newPath = $uploadDir . basename($albumImg);
    if (file_exists($newPath)) {
        set_flash("IMAGE ALREADY EXISTS: " . $albumImg);
        header("Location: ../admin/albums.php");
        exit();
    }

    if (move_uploaded_file($tempFile, $newPath)) {
        $stmt = $db->prepare("INSERT INTO album (album_name, album_link, album_img, album_category) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $albumName, $albumLink, $albumImg, $albumCategory);

        if ($stmt->execute()) {
            set_flash("Album uploaded successfully");
        } else {
            set_flash("Upload failed: " . $stmt->error);
        }
        $stmt->close();
    } else {
        set_flash("Upload failed");
    }
}

$db->close();
header("Location: ../admin/albums.php");
exit();
?>
