<?php
    require_once 'connection.php';

    if(isset($_POST['markReadBtn']) &&isset($_POST['rowId']) && $_POST['rowId'] != ""){
        $id = mysqli_real_escape_string($db, $_POST['rowId']);

        $sql = "UPDATE contactdata SET status='read' WHERE id='$id'";
        
        if ($db->query($sql) === TRUE) {
            echo "Record updated successfully";
            header("Location: inbox.php");
        } else {
            echo "Error updating record: " . $db->error;
        }
    }

    if(isset($_POST['markUnreadBtn']) &&isset($_POST['rowId']) && $_POST['rowId'] != ""){
        $id = mysqli_real_escape_string($db, $_POST['rowId']);

        $sql = "UPDATE contactdata SET status='unread' WHERE id='$id'";
        
        if ($db->query($sql) === TRUE) {
            echo "Record updated successfully";
            header("Location: inbox-read.php");
        } else {
            echo "Error updating record: " . $db->error;
        }
    }
?>