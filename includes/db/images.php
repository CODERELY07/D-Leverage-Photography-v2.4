<?php

if (!function_exists('get_all_images')) {
    function get_all_images(mysqli $db): array {
        $result = $db->query("SELECT * FROM image");
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }
}

if (!function_exists('get_images_by_category')) {
    function get_images_by_category(mysqli $db, string $category): array {
        $stmt = $db->prepare("SELECT * FROM image WHERE category = ?");
        $stmt->bind_param('s', $category);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        $stmt->close();
        return $rows;
    }
}

if (!function_exists('get_image_count')) {

    function get_image_count(mysqli $db): int {
        $result = $db->query("SELECT COUNT(*) AS cnt FROM image");
        return (int) $result->fetch_assoc()['cnt'];
    }
}
