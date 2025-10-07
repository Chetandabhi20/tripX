<?php
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Result - TripX</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            min-height: 100vh;
            position: relative;
            background: linear-gradient(135deg, #43c6ac 0%, #2c8c7c 100%);
        }

        .result-container {
            background: rgba(255, 255, 255, 0.95);
            max-width: 400px;
            margin: 50px auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            text-align: center;
        }

        h2 {
            color: #222;
            font-size: 2.2em;
            margin-bottom: 20px;
        }

        p {
            color: #666;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .error {
            color: #dc3545;
            margin: 10px 0;
        }

        .success {
            color: #28a745;
        }

        a {
            display: inline-block;
            background: linear-gradient(135deg, #43c6ac 0%, #2c8c7c 100%);
            color: white;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 6px;
            font-size: 16px;
            margin-top: 20px;
        }

        a:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="result-container">
<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // 1. Sanitize input
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));
    $confirm_password = htmlspecialchars(trim($_POST['confirm_password']));
    $mobile = htmlspecialchars(trim($_POST['mobile']));

    // 2. Validation
    $errors = array();

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password) || empty($mobile)) {
        $errors[] = "All fields are required.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    if (strlen($password) < 8 || strlen($password) > 16) {
        $errors[] = "Password must be between 8 and 16 characters.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (!preg_match("/^[6-9][0-9]{9}$/", $mobile)) {
        $errors[] = "Invalid mobile number. Must be 10 digits starting with 6-9.";
    }

    // Display errors if any
    if (!empty($errors)) {
        echo "<h2>Registration Failed</h2>";
        foreach ($errors as $error) {
            echo "<p class='error'>$error</p>";
        }
        echo "<a href='javascript:history.back()'>Go Back</a>";
    } else {
        // 3. Check if username already exists
        $file = @fopen("users.txt", "r");
        $username_exists = false;
        
        if ($file) {
            while (($line = fgets($file)) !== false) {
                list($storedUser) = explode("|", trim($line));
                if ($username === $storedUser) {
                    $username_exists = true;
                    break;
                }
            }
            fclose($file);
        }

        if ($username_exists) {
            echo "<h2>Registration Failed</h2>";
            echo "<p class='error'>Username already exists. Please choose a different username.</p>";
            echo "<a href='javascript:history.back()'>Go Back</a>";
        } else {
            // 4. Save user data
            $file = @fopen("users.txt", "a");
            if ($file) {
                fwrite($file, "$username|$password|$email|$mobile\n");
                fclose($file);
                
                // 5. Show success message
                echo "<h2 class='success'>Registration Successful!</h2>";
                echo "<p>Welcome, $username!</p>";
                echo "<p>Your account has been created successfully.</p>";
                echo "<a href='login.html'>Click here to login</a>";
            } else {
                echo "<h2>Registration Failed</h2>";
                echo "<p class='error'>There was an error creating your account. Please try again later.</p>";
                echo "<a href='signup.html'>Go back to Sign Up</a>";
            }
        }
    }
} else {
    echo "<h2>Invalid Request</h2>";
    echo "<p class='error'>Invalid request method.</p>";
    echo "<a href='signup.html'>Go to Sign Up</a>";
}
?>
    </div>
</body>
</html>
