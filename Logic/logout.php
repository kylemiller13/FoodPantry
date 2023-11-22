<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start the session
session_start();

// Clear session data
session_unset();
session_destroy();

// Redirect to login page
// Ensure the path is relative to the root of your server
header('Location: ../UI/login.html');
exit();
