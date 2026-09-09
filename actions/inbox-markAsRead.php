<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db/messages.php';
require_once __DIR__ . '/../includes/csrf.php';
session_start();

require_login('../index.php');

if (!verify_csrf_token()) {
    http_response_code(403);
    exit('Invalid or expired form submission. Please refresh the page and try again.');
}

if (isset($_POST['markReadBtn']) || isset($_POST['markUnreadBtn'])) {
    if (isset($_POST['rowId']) && !empty($_POST['rowId'])) {
        $id = (int) $_POST['rowId'];
        $status = isset($_POST['markReadBtn']) ? 'read' : 'unread';

        $result = set_message_status($db, $id, $status);

        if ($result === true) {

            $redirectPage = isset($_POST['markReadBtn']) ? '../admin/inbox.php' : '../admin/inbox-read.php';
            header("Location: $redirectPage");
            exit();
        } else {
            echo "Error updating record: " . $result;
        }
    } else {
        echo "Invalid row ID.";
    }
} else {
    echo "No action specified.";
}

$db->close();
?>
