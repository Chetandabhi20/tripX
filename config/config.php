<?php
// Environment detection
define('ENVIRONMENT', isset($_SERVER['APP_ENV']) ? $_SERVER['APP_ENV'] : 'development');

// Base configurations
$config = [
    'development' => [
        'db_host' => 'localhost',
        'db_user' => 'root',
        'db_pass' => '',
        'db_name' => 'tripx_db',
        'base_url' => 'http://localhost/lasttry/',
        'debug' => true
    ],
    'production' => [
        'db_host' => getenv('DB_HOST'),
        'db_user' => getenv('DB_USER'),
        'db_pass' => getenv('DB_PASS'),
        'db_name' => getenv('DB_NAME'),
        'base_url' => getenv('BASE_URL'),
        'debug' => false
    ]
];

// Load configuration based on environment
$current_config = $config[ENVIRONMENT];

// Define constants
define('BASE_URL', $current_config['base_url']);
define('DEBUG_MODE', $current_config['debug']);

// Database configuration
define('DB_HOST', $current_config['db_host']);
define('DB_USER', $current_config['db_user']);
define('DB_PASS', $current_config['db_pass']);
define('DB_NAME', $current_config['db_name']);

// Session configuration
define('SESSION_NAME', 'tripx_session');
define('SESSION_LIFETIME', 7200); // 2 hours

// Security configuration
define('HASH_COST', 12); // For password hashing

// Upload configuration
define('MAX_UPLOAD_SIZE', 5242880); // 5MB
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif']);

// Error reporting based on environment
if (ENVIRONMENT === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
?>