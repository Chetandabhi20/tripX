<?php
// Database configuration
$db_host = 'localhost'; // Or your database host
$db_user = 'root';      // Your database username
$db_pass = '';          // Your database password
$db_name = 'tripx_db';  // Your database name

// Create a new mysqli object to establish a database connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check if the connection was successful
if ($conn->connect_error) {
    // If connection fails, terminate the script and display an error message.
    // This is a critical error, so we stop everything.
    die("Connection failed: " . $conn->connect_error);
}

// Set the character set to utf8mb4 for full Unicode support
$conn->set_charset("utf8mb4");
?>
