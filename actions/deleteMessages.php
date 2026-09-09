<?php
    require_once __DIR__ . '/../config/connection.php';
    require_once __DIR__ . '/../includes/auth.php';
    require_once __DIR__ . '/../includes/db/messages.php';
    require_once __DIR__ . '/../includes/csrf.php';
    session_start();

    require_login('../index.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!verify_csrf_token()) {
            http_response_code(403);
            exit('Invalid or expired request.');
        }

        $id = (int) $_POST['id'];

        $result = delete_message($db, $id);

        echo $result === true ? 1 : 0;

        $db->close();
    }
?>
