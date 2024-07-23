<?php

$db = new mysqli("localhost", "root", "", "d'leverage");

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

