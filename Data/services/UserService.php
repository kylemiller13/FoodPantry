<?php

namespace services;

use repositories\UserRepository;

include_once(__DIR__ . '/../repositories/UserRepository.php');

class UserService
{
    private UserRepository $userRepository;  // Added type declaration here

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function registerUser(string $username, string $password, string $email): array
    {
        // Input Validation (simplified)
        if (empty($username) || empty($password) || empty($email)) {
            return ["status" => "error", "message" => "All fields are required"];
        }

        // Check for existing user with the same username or email
        $existingUser = $this->userRepository->getUserByUsernameOrEmail($username, $email);


        if ($existingUser) {
            return ["status" => "error", "message" => "Username or email already exists"];
        }

        // Password Hashing
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Create User
        $result = $this->userRepository->createUser($username, $hashedPassword, $email);

        // Response
        if ($result === false) {
            return ["status" => "error", "message" => "Something went wrong"];
        }
        return ["status" => "success", "message" => "Account created"];
    }



    public function loginUser(string $email_username, string $password): array
    {
        // Input Validation
        if (empty($email_username) || empty($password)) {
            return ["status" => "error", "message" => "All fields are required"];
        }

        // Fetch User and Verify Password
        // Assuming that $email_username could be both an email or a username, pass it twice
        $user = $this->userRepository->getUserByUsernameOrEmail($email_username, $email_username);

        if ($user && password_verify($password, $user['hashed_password'])) {
            // Successful login
            // Perform any additional actions like setting session variables
            return ["status" => "success", "message" => "Logged in"];
        } else {
            // Failed login
            return ["status" => "error", "message" => "Invalid username or password"];
        }
    }

}
