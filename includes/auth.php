<?php

if (!function_exists('require_login')) {
    function require_login($redirectTo = '../index.php') {
        if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
            header('Location: ' . $redirectTo);
            exit();
        }
    }
}

if (!function_exists('require_ajax_login')) {

    function require_ajax_login() {
        if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
            http_response_code(403);
            exit('Unauthorized');
        }
    }
}
