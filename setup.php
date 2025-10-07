<?php
// Include the database connection file
require_once 'db_connect.php';

echo "<h1>Database Setup Script</h1>";

// --- Users Table ---
$sql_users = "
CREATE TABLE IF NOT EXISTS users (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    mobile VARCHAR(15) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql_users) === TRUE) {
    echo "<p>Table 'users' created successfully or already exists.</p>";
} else {
    echo "<p>Error creating table 'users': " . $conn->error . "</p>";
}

// --- Trips Table ---
$sql_trips = "
CREATE TABLE IF NOT EXISTS trips (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) UNSIGNED NOT NULL,
    trip_name VARCHAR(100) NOT NULL,
    source VARCHAR(100) NOT NULL,
    destination VARCHAR(100) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    description TEXT,
    status ENUM('upcoming', 'ongoing', 'completed') NOT NULL DEFAULT 'upcoming',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

if ($conn->query($sql_trips) === TRUE) {
    echo "<p>Table 'trips' created successfully or already exists.</p>";
} else {
    echo "<p>Error creating table 'trips': " . $conn->error . "</p>";
}

// Create admin user if it doesn't exist
$admin_username = 'admin';
$admin_email = 'admin@tripx.com';
$admin_password = password_hash('admin123', PASSWORD_DEFAULT);
$admin_mobile = '1234567890';

$check_admin = "SELECT id FROM users WHERE username = 'admin' OR email = 'admin@tripx.com'";
$result = $conn->query($check_admin);

if ($result->num_rows == 0) {
    $sql_admin = "INSERT INTO users (username, email, password, mobile, role) VALUES (?, ?, ?, ?, 'admin')";
    $stmt = $conn->prepare($sql_admin);
    $stmt->bind_param("ssss", $admin_username, $admin_email, $admin_password, $admin_mobile);
    
    if ($stmt->execute()) {
        echo "<p>Admin user created successfully.</p>";
    } else {
        echo "<p>Error creating admin user: " . $stmt->error . "</p>";
    }
    $stmt->close();
} else {
    echo "<p>Admin user already exists.</p>";
}

// Close the connection
$conn->close();
?>
