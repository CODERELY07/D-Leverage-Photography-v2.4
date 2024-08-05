<?php
session_start();


require_once 'connection.php';

// Get filter parameter
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

// Build the SQL query
if ($filter === 'all') {
    $query = "SELECT * FROM image";
} else {
    $query = "SELECT * FROM image WHERE category = ?";
}

// Prepare and execute the statement
$stmt = $db->prepare($query);
if ($filter !== 'all') {
    $stmt->bind_param('s', $filter);
}
$stmt->execute();
$result = $stmt->get_result();

$result_array = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $result_array[] = $row;
    }
    header("Content-Type: application/json");
    echo json_encode($result_array);
} else {
    echo "no";
}

$stmt->close();
$db->close();
?>
