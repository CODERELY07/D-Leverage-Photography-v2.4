<?php

if (!function_exists('get_messages_by_status')) {

    function get_messages_by_status(mysqli $db, string $status): array {
        $stmt = $db->prepare("SELECT * FROM contactData WHERE status = ?");
        $stmt->bind_param('s', $status);
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

if (!function_exists('count_messages_by_status')) {

    function count_messages_by_status(mysqli $db, string $status): int {
        $stmt = $db->prepare("SELECT COUNT(*) AS cnt FROM contactData WHERE status = ?");
        $stmt->bind_param('s', $status);
        $stmt->execute();
        $count = (int) $stmt->get_result()->fetch_assoc()['cnt'];
        $stmt->close();
        return $count;
    }
}

if (!function_exists('message_email_exists')) {
    function message_email_exists(mysqli $db, string $email): bool {
        $stmt = $db->prepare("SELECT COUNT(*) AS cnt FROM contactData WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $count = (int) $stmt->get_result()->fetch_assoc()['cnt'];
        $stmt->close();
        return $count > 0;
    }
}

if (!function_exists('insert_message')) {

    function insert_message(mysqli $db, array $data) {
        $stmt = $db->prepare(
            "INSERT INTO contactData (fullname, email, phonenumber, shootdate, location, service, session, message) VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        if ($stmt === false) {
            return $db->error;
        }
        $stmt->bind_param(
            "ssssssss",
            $data['fullname'],
            $data['email'],
            $data['phonenumber'],
            $data['date'],
            $data['location'],
            $data['services'],
            $data['session'],
            $data['message']
        );
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        }
        $error = $stmt->error;
        $stmt->close();
        return $error;
    }
}

if (!function_exists('set_message_status')) {
    function set_message_status(mysqli $db, int $id, string $status) {
        $stmt = $db->prepare("UPDATE contactData SET status = ? WHERE id = ?");
        if ($stmt === false) {
            return $db->error;
        }
        $stmt->bind_param('si', $status, $id);
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        }
        $error = $stmt->error;
        $stmt->close();
        return $error;
    }
}

if (!function_exists('delete_message')) {
    function delete_message(mysqli $db, int $id) {
        $stmt = $db->prepare("DELETE FROM contactdata WHERE id = ?");
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        }
        $error = $stmt->error;
        $stmt->close();
        return $error;
    }
}
