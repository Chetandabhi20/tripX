<?php
// Initialize the application
require_once __DIR__ . '/config/config.php';

// Start session
session_start();

// Get the requested path
$request_uri = $_SERVER['REQUEST_URI'];
$script_name = dirname($_SERVER['SCRIPT_NAME']);
$base_path = rtrim($script_name, '/');
$path = substr(urldecode($request_uri), strlen($base_path));
$path = trim($path, '/');

// Default route
if (empty($path)) {
    $path = 'home';
}

// Route the request
$file = __DIR__ . '/public/' . $path . '.html';
if (file_exists($file)) {
    // For static HTML files
    readfile($file);
} else {
    // For PHP files
    $php_file = __DIR__ . '/includes/' . $path . '.php';
    if (file_exists($php_file)) {
        require_once $php_file;
    } else {
        // 404 error
        header('HTTP/1.0 404 Not Found');
        echo '404 - Page not found';
    }
}
?>