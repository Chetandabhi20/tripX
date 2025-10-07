<?php
session_start();
require_once 'db_connect.php';

$error_message = '';

// Check for error messages from redirects
if (isset($_GET['error'])) {
    if ($_GET['error'] === 'invalid') {
        $error_message = 'Invalid username or password.';
    } elseif ($_GET['error'] === 'empty') {
        $error_message = 'Please fill in all fields.';
    }
}

if (isset($_GET['logout']) && $_GET['logout'] === 'success') {
    $error_message = 'You have been logged out successfully.';
}

// --- LOGIN LOGIC ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        header('Location: login.php?error=empty');
        exit;
    }

    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
            // Success: Set session and redirect
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['logged_in'] = true;

            // Redirect admins to admin dashboard, regular users to home
            if ($user['role'] === 'admin') {
                header('Location: admin.php');
            } else {
                header('Location: home.html');
            }
            exit;
        } else {
            header('Location: login.php?error=invalid');
            exit;
        }
    // If we get here, the credentials were invalid
    header('Location: login.php?error=invalid');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TripX</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f4f4; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .login-container { background: white; padding: 40px; border-radius: 12px; box-shadow: 0 8px 24px rgba(0,0,0,0.1); max-width: 400px; width: 100%; text-align: center; }
        h1 { margin-bottom: 20px; }
        .user, .pass { text-align: left; margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; }
        input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        button { background: #43c6ac; color: white; border: none; padding: 12px; border-radius: 5px; width: 100%; cursor: pointer; font-size: 16px; }
        .error-message { color: #D8000C; background-color: #FFD2D2; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
        .success-message { color: #4F8A10; background-color: #DFF2BF; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="login-container">
        <header>
            <h1>Welcome Back!</h1>
        </header>

        <?php if (!empty($error_message)): ?>
            <div class="<?php echo (isset($_GET['logout'])) ? 'success-message' : 'error-message'; ?>">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="post">
            <div class="user">
                <label for="username">Username or Email</label>
                <input type="text" id="username" name="username" placeholder="Enter your username or email" required>
            </div>
            <div class="pass">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <div class="button">
                <button type="submit">Log In</button>
            </div>
            <p>Don't have an account? <a href="signup.html">Sign up here</a></p>
        </form>
    </div>
</body>
</html>
