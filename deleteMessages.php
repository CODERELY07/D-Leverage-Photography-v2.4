<?php
    require_once 'connection.php';
    session_start();

    // Redirect if already logged in
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header('Location: index.php'); 
        exit();
    }
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $id = htmlspecialchars($_POST['id']);

        $stmt = $db->prepare("DELETE FROM contactdata WHERE id=?");
        $stmt->bind_param("i",$id);

        if($stmt->execute()){
            echo 1;
        }else{
            echo 0;
        }

        $stmt->close();
        $db->close();
    }
?>