<?php
session_start();
require_once __DIR__ . '/../includes/auth.php';

require_ajax_login();

session_write_close();

require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../includes/db/images.php';

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

$result_array = $filter === 'all'
    ? get_all_images($db)
    : get_images_by_category($db, $filter);

if (!empty($result_array)) {
    header("Content-Type: application/json");
    echo json_encode($result_array);
} else {
    echo "no";
}

$db->close();
?>
