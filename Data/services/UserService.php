<?php

namespace services;

use repositories\UserRepository;

include_once(__DIR__ . '/../repositories/UserRepository.php');

class UserService
{
    private UserRepository $userRepository;
    private PasswordValidator $passwordValidator; // Dependency for password validation

    public function __construct(PasswordValidator $passwordValidator)
    {
        $this->userRepository = $userRepository;
        $this->passwordValidator = $passwordValidator;
    }

    public function registerUser(string $username, string $password, string $email): array
    {
        // Input Validation
        if (empty($username) || empty($password) || empty($email)) {
            return ["status" => "error", "message" => "All fields are required"];
        }

        // Password Validation
        $passwordFeedback = $this->passwordValidator->getUserFeedback($password);
        if ($passwordFeedback !== 'Password is strong.') {
            return ["status" => "error", "message" => $passwordFeedback];
        }

        // Check for existing user
        $existingUser = $this->userRepository->getUserByUsernameOrEmail($username, $email);
        if ($existingUser) {
            return ["status" => "error", "message" => "Username or email already exists"];
        }

        // Password Hashing
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Create User
        $result = $this->userRepository->createUser($username, $hashedPassword, $email);

        // Response Handling
        if ($result === false) {
            return ["status" => "error", "message" => "Something went wrong during registration"];
        }
        return ["status" => "success", "message" => "Account created successfully"];
    }

    public function loginUser(string $email_username, string $password): array
    {
        // Input Validation
        if (empty($email_username) || empty($password)) {
            return ["status" => "error", "message" => "All fields are required"];
        }

        // Fetch User and Verify Password
        $user = $this->userRepository->getUserByUsernameOrEmail($email_username, $email_username);
        if ($user && password_verify($password, $user['hashed_password'])) {
            // Successful login actions here (like setting session variables)
            return ["status" => "success", "message" => "Successfully logged in"];
        } else {
            // Failed login response
            return ["status" => "error", "message" => "Invalid username or password"];
        }
    }

    // Additional methods
}
