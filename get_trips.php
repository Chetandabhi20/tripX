<?php
session_start();
header('Content-Type: application/json');
require_once 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$trips = [];

// Fetch trips for the logged-in user from the database
try {
    $stmt = $conn->prepare("SELECT id, trip_name, destination, start_date, end_date, description, status FROM trips WHERE user_id = ? ORDER BY start_date DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $trips[] = $row;
    }

    echo json_encode(['success' => true, 'trips' => $trips]);
    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

$conn->close();
?>
