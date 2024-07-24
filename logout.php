<?php

session_start();
 

$_SESSION = array();
 
// Destroy the session.
session_destroy();
 

header("location: adminLogin.php");
exit;
?>