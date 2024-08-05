<?php
session_start();


require_once 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send'])) {
    
    $fullname = isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : false;
    $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : false;
    $phonenumber = isset($_POST['phonenumber']) ? htmlspecialchars($_POST['phonenumber']) : false;
    $date = isset($_POST['date']) ? htmlspecialchars($_POST['date']) : false;
    $location = isset($_POST['location']) ? htmlspecialchars($_POST['location']) : false;
    $session = isset($_POST['session']) ? htmlspecialchars($_POST['session']) : false;
    $services = isset($_POST['services']) ? htmlspecialchars($_POST['services']) : false;
    $message = isset($_POST['message']) ? htmlspecialchars($_POST['message']) : false;

    if ($fullname == false || $email == false || $phonenumber == false || $location == false || $session == false || $services == false || $message == false || $date == false) {
        echo 0;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo -1;
    } else {
        $stmt = $db->prepare("INSERT INTO contactData (fullname, email, phonenumber, shootdate, location, service, session, message) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        if ($stmt === false) {
            die("Prepare failed: " . $db->error);
        }

        $stmt->bind_param("ssssssss", $fullname, $email, $phonenumber, $date, $location, $services, $session, $message);

        if ($stmt->execute()) {
            echo 1;
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $db->close();
}
?>
