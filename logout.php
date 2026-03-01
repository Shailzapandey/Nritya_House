<?php
// logout.php
session_start();
session_unset();     // Remove all session variables
session_destroy();   // Destroy the session completely

// Redirect back to the home page or login page
header("Location: login.php");
exit;
?>