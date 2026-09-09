<?php

if (!function_exists('get_album_by_id')) {
    function get_album_by_id(mysqli $db, int $id): ?array {
        $stmt = $db->prepare("SELECT * FROM album WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $album = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $album ?: null;
    }
}

if (!function_exists('get_album_images')) {
    function get_album_images(mysqli $db, int $albumId): array {
        $stmt = $db->prepare("SELECT * FROM album_img WHERE album_id = ?");
        $stmt->bind_param('i', $albumId);
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

if (!function_exists('get_album_count')) {

    function get_album_count(mysqli $db): int {
        $result = $db->query("SELECT COUNT(*) AS cnt FROM album");
        return (int) $result->fetch_assoc()['cnt'];
    }
}
