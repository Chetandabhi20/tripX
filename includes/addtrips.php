<?php
session_start();
header('Content-Type: application/json');
require_once 'db_connect.php';

// 1. Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to add a trip.']);
    exit;
}

// 2. Ensure request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// 3. Get and validate form data
$trip_name = trim($_POST['trip_name'] ?? '');
$source = trim($_POST['source'] ?? '');
$destination = trim($_POST['destination'] ?? '');
$start_date = $_POST['start_date'] ?? '';
$end_date = $_POST['end_date'] ?? '';
$description = trim($_POST['description'] ?? '');
$user_id = $_SESSION['user_id'];

if (empty($trip_name) || empty($source) || empty($destination) || empty($start_date) || empty($end_date)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
    exit;
}

// 4. Insert data into the database
try {
    $stmt = $conn->prepare("INSERT INTO trips (user_id, trip_name, source, destination, start_date, end_date, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssss", $user_id, $trip_name, $source, $destination, $start_date, $end_date, $description);

    if ($stmt->execute()) {
        
        // --- START: SAVE TO CSV FILE (with error handling) ---
        
        $csvFile = 'trips.csv';
        $tripData = [$user_id, $trip_name, $source, $destination, $start_date, $end_date, $description];
        $csvErrorMessage = '';

        // Open the file in append mode. The '@' suppresses default PHP warnings to allow our custom JSON error.
        $fileHandle = @fopen($csvFile, 'a');
        
        if ($fileHandle === false) {
            // This is the most likely error: folder is not writable.
            $csvErrorMessage = 'CSV Error: Could not open file. Please check folder permissions.';
        } else {
            // If this is a new file, add the header row
            if (filesize($csvFile) == 0) {
                $headers = ['user_id', 'trip_name', 'source', 'destination', 'start_date', 'end_date', 'description'];
                fputcsv($fileHandle, $headers);
            }
            
            // Write the trip data and check for failure
            if (fputcsv($fileHandle, $tripData) === false) {
                $csvErrorMessage = 'CSV Error: Failed to write data to the file.';
            }
            
            fclose($fileHandle);
        }
        
        // --- END: SAVE TO CSV FILE ---

        if (!empty($csvErrorMessage)) {
            // If there was a CSV error, send it back but confirm DB was successful.
            echo json_encode([
                'success' => false, // Set to false to show an error message on the front end
                'message' => 'Trip saved to database, but failed to save to CSV. ' . $csvErrorMessage
            ]);
        } else {
            // Everything was successful
            echo json_encode(['success' => true, 'message' => 'Trip added successfully to database and CSV!']);
        }

    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add trip to database.']);
    }

    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

$conn->close();
?>