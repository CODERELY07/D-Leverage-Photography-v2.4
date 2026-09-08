<?php
/**
 * Shared helpers used across the upload scripts.
 */

if (!function_exists('sanitize_upload_filename')) {
    // Uploaded filenames get used unencoded in <img src="..."> across the site,
    // so raw spaces or other reserved URL characters (e.g. "KEYCHAIN ORDERS.png")
    // silently break the image instead of just looking untidy. Keep only safe,
    // URL-friendly characters.
    function sanitize_upload_filename($filename) {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $name = pathinfo($filename, PATHINFO_FILENAME);
        $name = preg_replace('/[^A-Za-z0-9_-]+/', '-', $name);
        $name = trim($name, '-') ?: 'image';
        return $ext !== '' ? "$name.$ext" : $name;
    }
}

if (!function_exists('url_encode_path')) {
    // For rows stored before sanitize_upload_filename() existed (or any other
    // relative path built from a raw filename): URL-encode only the last path
    // segment, so the "image/uploads/" directory part of the path is left alone.
    function url_encode_path($path) {
        $parts = explode('/', $path);
        $parts[count($parts) - 1] = rawurlencode(end($parts));
        return implode('/', $parts);
    }
}

if (!function_exists('category_label')) {
    // Single source of truth for how each album_category slug is shown to
    // visitors, so photos-category.php, view-album.php, etc. never drift
    // apart (e.g. one saying "Wedding" and another saying "Wedding / Prenuptial").
    function category_label($slug) {
        $labels = [
            'wedding' => 'Wedding / Prenuptial',
            'birthday' => 'Birthday',
            'others' => 'Others',
        ];
        return $labels[$slug] ?? ucfirst($slug);
    }
}
