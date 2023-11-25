<?php

include_once(__DIR__ . '/../Data/Database.php');
include_once(__DIR__ . '/../Data/services/UserService.php');
include_once(__DIR__ . '/../Data/services/PasswordValidator.php'); // Include PasswordValidator

use services\UserService;
use services\PasswordValidator;

// Initialize variables for user feedback
$feedback = "";
$feedbackType = '';
$feedbackStatus = '';

// Create instances of PasswordValidator

$passwordValidator = new PasswordValidator();

// Create an instance of UserService with dependencies
$userService = new UserService($userRepository, $passwordValidator);

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
