<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'connection.php';
session_start();

// Redirect if not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php');
    exit();
}

if (isset($_POST['markReadBtn']) || isset($_POST['markUnreadBtn'])) {
    if (isset($_POST['rowId']) && !empty($_POST['rowId'])) {
        $id = $_POST['rowId'];

        // Prepare and bind
        if ($stmt = $db->prepare("UPDATE contactData SET status = ? WHERE id = ?")) {
            $status = isset($_POST['markReadBtn']) ? 'read' : 'unread';
            $stmt->bind_param('si', $status, $id);

            if ($stmt->execute()) {
                echo "Record updated successfully";
                $redirectPage = isset($_POST['markReadBtn']) ? 'inbox.php' : 'inbox-read.php';
                header("Location: $redirectPage");
                exit();
            } else {
                echo "Error updating record: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Prepare failed: " . $db->error;
        }
    } else {
        echo "Invalid row ID.";
    }
} else {
    echo "No action specified.";
}

$db->close();
?>
