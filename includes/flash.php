<?php

if (!function_exists('set_flash')) {
    function set_flash(string $message): void {
        $_SESSION['status'] = $message;
    }
}

if (!function_exists('display_flash')) {
    function display_flash(): void {
        if (empty($_SESSION['status'])) {
            return;
        }
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">'
           . '<strong>' . htmlspecialchars($_SESSION['status']) . '</strong>'
           . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>'
           . '</div>';
        unset($_SESSION['status']);
    }
}
