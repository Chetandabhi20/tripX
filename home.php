<?php
// Start the session to access session variables.
session_start();

// Check if the user is logged in. If not, redirect to the login page.
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - TripX</title>
    <style>
        /* --- Existing Styles from your home.html --- */
        body { 
            font-family: 'Segoe UI', Arial, sans-serif; 
            text-align: center; 
            background: #f4f4f4; 
            margin: 0;
            padding-bottom: 80px; /* Add padding to prevent footer from overlapping content */
        }
        .nav ul { 
            list-style: none; 
            background: #222; 
            padding: 10px 0; 
            display: flex; 
            justify-content: center; 
            gap: 30px; 
            margin: 0; 
        }
        .nav ul li a { 
            color: #fff; 
            text-decoration: none; 
            padding: 8px 16px;
        }
        .greet { 
            margin-top: 50px; 
        }
        .greet h1 { 
            font-size: 2.5em; 
        }
        .logout-button { 
            background-color: #e74c3c; 
            color: white; 
            border: none; 
            padding: 10px 20px; 
            border-radius: 5px; 
            text-decoration: none; 
            font-size: 16px; 
            margin-top: 20px; 
            display: inline-block; 
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .logout-button:hover {
            background-color: #c0392b;
        }

        /* --- NEW: Cookie Consent Banner Styles --- */
        #cookie-consent-banner {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #2c3e50; /* Dark blue-grey background */
            color: white;
            padding: 15px 25px;
            box-sizing: border-box;
            display: none; /* Hidden by default, shown with JS */
            justify-content: space-between;
            align-items: center;
            z-index: 1001; /* Ensure it's on top of other elements */
            box-shadow: 0 -2px 10px rgba(0,0,0,0.2);
        }

        #cookie-consent-banner p {
            margin: 0;
            font-size: 14px;
        }

        #cookie-consent-banner button {
            background-color: #43c6ac;
            color: #222;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            flex-shrink: 0; /* Prevents button from shrinking on small screens */
            margin-left: 20px;
        }
    </style>
</head>
<body>
    <div class="nav">
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="trips.html">Trips</a></li>
            <li><a href="profile.html">Account</a></li>
        </ul>
    </div>
    
    <div class="content-overlay">
        <div class="greet">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
            <p>You have successfully logged in.</p>
            <a href="logout.php" class="logout-button">Logout</a>
        </div>
    </div>

    <!-- NEW: HTML for the Cookie Consent Banner -->
    <div id="cookie-consent-banner">
        <p>This website uses cookies to ensure you get the best experience.</p>
        <button id="accept-cookies-btn">Accept All Cookies</button>
    </div>

    <!-- NEW: JavaScript to manage the cookie banner -->
    <script>
        // This script should run after the whole page is loaded to ensure all elements are available.
        document.addEventListener('DOMContentLoaded', function() {
            const consentBanner = document.getElementById('cookie-consent-banner');
            const acceptBtn = document.getElementById('accept-cookies-btn');

            // Helper function to easily get a cookie by its name.
            function getCookie(name) {
                const value = `; ${document.cookie}`;
                const parts = value.split(`; ${name}=`);
                if (parts.length === 2) return parts.pop().split(';').shift();
            }

            // Check if the user has already accepted the cookies.
            if (!getCookie('cookie_consent_accepted')) {
                // If the cookie does NOT exist, show the banner.
                consentBanner.style.display = 'flex';
            }

            // Add an event listener for when the user clicks the "Accept" button.
            acceptBtn.addEventListener('click', function() {
                // Set a cookie named 'cookie_consent_accepted' that will expire in 1 year.
                const d = new Date();
                d.setTime(d.getTime() + (365 * 24 * 60 * 60 * 1000));
                let expires = "expires=" + d.toUTCString();
                document.cookie = "cookie_consent_accepted=true;" + expires + ";path=/";
                
                // Hide the banner after the cookie is set.
                consentBanner.style.display = 'none';
            });
        });
    </script>
</body>
</html>

