<?php
// 1. Start the session to access it.
session_start();

// 2. Unset all of the session variables.
$_SESSION = [];

// 3. Destroy the session itself.
session_destroy();

// 4. Redirect the user to the login page with a success message.
header('Location: login.php?logout=success');
exit();
?>
