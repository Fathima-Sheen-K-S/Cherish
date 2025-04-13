<?php
session_start();

// Destroy the session
session_unset();  // Remove all session variables
session_destroy();  // Destroy the session

// Redirect to the login page or home page
header("Location: login.html");
exit();
?>
