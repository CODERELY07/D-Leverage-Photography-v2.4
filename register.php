<?php


require 'connection.php';


function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

// Function to register a new user
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

// Example usage
$username = 'administrators';
$password = '123456790';

registerUser($db, $username, $password);

// Close the dbection
$db->close();
?>
