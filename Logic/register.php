<?php
// Import required classes
include_once(__DIR__ . '/../Data/Database.php');
include_once(__DIR__ . '/../Data/services/UserService.php');
include_once(__DIR__ . '/../Data/services/PasswordValidator.php');

use services\UserService;
use services\PasswordValidator;
use repositories\UserRepository;

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Initialize variables for user feedback
$feedback = "";
$feedbackType = '';
$feedbackStatus = '';

// Create instances of UserRepository and PasswordValidator
$userRepository = new UserRepository();
$passwordValidator = new PasswordValidator();

// Create an instance of UserService with dependencies
$userService = new UserService($userRepository, $passwordValidator);

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
