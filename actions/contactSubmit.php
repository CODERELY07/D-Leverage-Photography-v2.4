<?php
session_start();

require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../includes/logic/messages.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send'])) {

    $result = submit_contact_message($db, $_POST);

    switch ($result) {
        case 'missing_fields':
            echo 0;
            break;
        case 'invalid_email':
            echo -1;
            break;
        case 'duplicate_email':
            echo -2;
            break;
        case 'success':
            echo 1;
            break;
        default:

            echo "Error: " . $result;
    }

    $db->close();
}
?>
