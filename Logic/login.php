<?php
// Import required classes
include_once(__DIR__ . '/../Data/repositories/UserRepository.php');
include_once(__DIR__ . '/../Data/services/UserService.php');

use services\UserService;

// Initialize variables for user feedback
$feedback = "";

// Create an instance of UserService
$userService = new UserService();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from the form
    $usernameOrEmail = $_POST['email_username'];
    $password = $_POST['password'];

    // Use UserService for login
    $result = $userService->loginUser($usernameOrEmail, $password);

    // Set the feedback message and type
    $feedback = $result['message'];
    $feedbackType = 'login';
    $feedbackStatus = $result['status'];

    // Include the feedback template for styling specific response
    include 'feedback_template.php';
}
