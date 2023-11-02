<?php
// Import required classes
include_once(__DIR__ . '/../Data/repositories/UserRepository.php');
include_once(__DIR__ . '/../Data/services/UserService.php');

use services\UserService;

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Initialize variables for user feedback
$feedback = "";
$feedbackType = '';
$feedbackStatus = '';

// Create an instance of UserService
$userService = new UserService();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from the form
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Use UserService for registration
    $result = $userService->registerUser($username, $password, $email);

    // Set the feedback message
    $feedback = $result['message'];
    $feedbackType = 'register';
    $feedbackStatus = $result['status'];

    // Include the feedback template for unsuccessful registration attempts
    include 'feedback_template.php';
}
