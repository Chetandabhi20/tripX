<?php
header('Content-Type: application/json');
require_once 'db_connect.php'; // Use the new database connection file

// 1. ENSURE THE REQUEST METHOD IS POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// 2. RECEIVE AND SANITIZE USER INPUTS
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$mobile = trim($_POST['mobile'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// 3. SERVER-SIDE VALIDATION (remains the same)
if (empty($username) || empty($email) || empty($mobile) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
    exit;
}
if (!preg_match('/^[6-9][0-9]{9}$/', $mobile)) {
    echo json_encode(['success' => false, 'message' => 'Invalid mobile number format.']);
    exit;
}
if ($password !== $confirm_password) {
    echo json_encode(['success' => false, 'message' => 'Passwords do not match.']);
    exit;
}
if (strlen($password) < 8) {
    echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters.']);
    exit;
}

// 4. CHECK FOR DUPLICATE USERNAME OR EMAIL using prepared statements
try {
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Username or email already exists.']);
        $stmt->close();
        $conn->close();
        exit;
    }
    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    $conn->close();
    exit;
}


// 5. HASH THE PASSWORD FOR SECURITY
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// 6. INSERT NEW USER INTO THE DATABASE using prepared statements
try {
    $stmt = $conn->prepare("INSERT INTO users (username, email, mobile, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $mobile, $hashed_password);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Registration successful!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error during registration. Please try again.']);
    }

    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'An unexpected error occurred: ' . $e->getMessage()]);
}

// 7. CLOSE THE CONNECTION
$conn->close();
?>
