<?php

require __DIR__ . '/../config/connection.php';

function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

function registerUser($db, $username, $password) {

    $stmt = $db->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");

    if (!$stmt) {
        die("Prepare failed: " . $db->error);
    }

    $passwordHash = hashPassword($password);
    $stmt->bind_param("ss", $username, $passwordHash);

    if ($stmt->execute()) {
        echo "User registered successfully!";
    } else {
        echo "User registration failed: " . $stmt->error;
    }
    $stmt->close();
}

    $username = 'admin';
    $password = 'dl3v3rag3';

    registerUser($db, $username, $password);

    $db->close();
    header("location:../adminLogin.php");
?>
