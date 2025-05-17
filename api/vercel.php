<?php

// Handle static files
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$static_file = __DIR__ . '/../public' . $path;

if (file_exists($static_file) && is_file($static_file)) {
    $mime_type = mime_content_type($static_file);
    header('Content-Type: ' . $mime_type);
    readfile($static_file);
    exit;
}

// Handle PHP files
require 'index.php'; 