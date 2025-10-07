<?php
session_start();
require_once 'db_connect.php';
require_once 'check_session.php';

$success_msg = $error_msg = '';

// Handle trip actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        // Sanitize inputs
        $title = filter_var($_POST['title'] ?? '', FILTER_SANITIZE_STRING);
        $description = filter_var($_POST['description'] ?? '', FILTER_SANITIZE_STRING);
        $location = filter_var($_POST['location'] ?? '', FILTER_SANITIZE_STRING);
        $start_date = $_POST['start_date'] ?? '';
        $end_date = $_POST['end_date'] ?? '';
        $price = filter_var($_POST['price'] ?? 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $capacity = filter_var($_POST['capacity'] ?? 0, FILTER_SANITIZE_NUMBER_INT);
        
        try {
            switch ($action) {
                case 'add':
                    $stmt = $conn->prepare("INSERT INTO trips (title, description, location, start_date, end_date, price, capacity) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$title, $description, $location, $start_date, $end_date, $price, $capacity]);
                    $success_msg = "Trip added successfully!";
                    break;

                case 'update':
                    $id = filter_var($_POST['trip_id'], FILTER_SANITIZE_NUMBER_INT);
                    $stmt = $conn->prepare("UPDATE trips SET title=?, description=?, location=?, start_date=?, end_date=?, price=?, capacity=? WHERE id=?");
                    $stmt->execute([$title, $description, $location, $start_date, $end_date, $price, $capacity, $id]);
                    $success_msg = "Trip updated successfully!";
                    break;

                case 'delete':
                    $id = filter_var($_POST['trip_id'], FILTER_SANITIZE_NUMBER_INT);
                    $stmt = $conn->prepare("DELETE FROM trips WHERE id=?");
                    $stmt->execute([$id]);
                    $success_msg = "Trip deleted successfully!";
                    break;
            }
        } catch(PDOException $e) {
            $error_msg = "Error: " . $e->getMessage();
        }
    }
}

// Fetch all trips
try {
    $stmt = $conn->query("SELECT * FROM trips ORDER BY created_at DESC");
    $trips = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error_msg = "Error fetching trips";
    $trips = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Trips - TripX</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .trips-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .trip-form {
            margin-bottom: 30px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .trip-list {
            margin-top: 20px;
        }
        .trip-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        .success-message {
            padding: 10px;
            background-color: #d4edda;
            color: #155724;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .error-message {
            padding: 10px;
            background-color: #f8d7da;
            color: #721c24;
            border-radius: 4px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="nav">
        <ul>
            <li><a href="home.html">Home</a></li>
            <li><a href="admin.php">Admin Dashboard</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="trips-container">
        <h1>Manage Trips</h1>

        <?php if ($success_msg): ?>
            <div class="success-message"><?php echo htmlspecialchars($success_msg); ?></div>
        <?php endif; ?>

        <?php if ($error_msg): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_msg); ?></div>
        <?php endif; ?>

        <div class="trip-form">
            <h2>Add New Trip</h2>
            <form method="post">
                <input type="hidden" name="action" value="add">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" id="location" name="location" required>
                </div>
                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input type="date" id="start_date" name="start_date" required>
                </div>
                <div class="form-group">
                    <label for="end_date">End Date</label>
                    <input type="date" id="end_date" name="end_date" required>
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" id="price" name="price" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="capacity">Capacity</label>
                    <input type="number" id="capacity" name="capacity" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Trip</button>
            </form>
        </div>

        <div class="trip-list">
            <h2>Current Trips</h2>
            <?php foreach ($trips as $trip): ?>
                <div class="trip-item">
                    <h3><?php echo htmlspecialchars($trip['title']); ?></h3>
                    <p><?php echo htmlspecialchars($trip['description']); ?></p>
                    <p>Location: <?php echo htmlspecialchars($trip['location']); ?></p>
                    <p>Date: <?php echo htmlspecialchars($trip['start_date']); ?> to <?php echo htmlspecialchars($trip['end_date']); ?></p>
                    <p>Price: $<?php echo htmlspecialchars($trip['price']); ?></p>
                    <p>Capacity: <?php echo htmlspecialchars($trip['capacity']); ?></p>
                    
                    <form method="post" style="display: inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="trip_id" value="<?php echo $trip['id']; ?>">
                        <button type="submit" class="btn btn-delete" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>