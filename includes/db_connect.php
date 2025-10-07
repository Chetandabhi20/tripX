<?php
// Include the configuration file
require_once __DIR__ . '/config/config.php';

// Create a new mysqli object to establish a database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check if the connection was successful
if ($conn->connect_error) {
    // If connection fails, terminate the script and display an error message.
    // This is a critical error, so we stop everything.
    die("Connection failed: " . $conn->connect_error);
}

// Set the character set to utf8mb4 for full Unicode support
$conn->set_charset("utf8mb4");
?>
