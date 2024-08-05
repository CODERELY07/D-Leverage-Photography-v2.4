<?php
    require_once 'connection.php';
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