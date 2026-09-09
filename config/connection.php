<?php

$host = "127.0.0.1";
$username = "root";
$password = "nonoy12345";
$database = "dleverage";
$port = 3307;

$db = new mysqli($host, $username, $password, $database, $port);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

?>