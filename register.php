<?php
require 'connection.php';

// Function to hash the password
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

// Function to register a new user
function registerUser($db, $username, $password) {
    // Prepare the SQL statement
    $stmt = $db->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
    
    if (!$stmt) {
        die("Prepare failed: " . $db->error);
    }

    $passwordHash = hashPassword($password);

    // Bind the parameters
    $stmt->bind_param("ss", $username, $passwordHash);

    // Execute the statement
    if ($stmt->execute()) {
        echo "User registered successfully!";
    } else {
        echo "User registration failed: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Example usage
$username = 'administrators';
$password = '123456790';

registerUser($db, $username, $password);

// Close the dbection
$db->close();
?>
