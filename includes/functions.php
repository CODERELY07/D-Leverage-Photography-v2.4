<?php

if (!function_exists('sanitize_upload_filename')) {

    function sanitize_upload_filename($filename) {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $name = pathinfo($filename, PATHINFO_FILENAME);
        $name = preg_replace('/[^A-Za-z0-9_-]+/', '-', $name);
        $name = trim($name, '-') ?: 'image';
        return $ext !== '' ? "$name.$ext" : $name;
    }
}

if (!function_exists('url_encode_path')) {

    function url_encode_path($path) {
        $parts = explode('/', $path);
        $parts[count($parts) - 1] = rawurlencode(end($parts));
        return implode('/', $parts);
    }
}

if (!function_exists('category_label')) {

    function category_label($slug) {
        $labels = [
            'wedding' => 'Wedding / Prenuptial',
            'birthday' => 'Birthday',
            'others' => 'Others',
        ];
        return $labels[$slug] ?? ucfirst($slug);
    }
}
