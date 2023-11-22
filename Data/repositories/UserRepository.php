<?php
namespace repositories;

use PDO;
use Exception;

include_once(__DIR__ . '/../db_config.php');

class UserRepository
{
    public function createUser($username, $hashedPassword, $email, $role = 'subscriber'): bool
    {
        $conn = connectDatabase();
        if ($conn === null) {
            return false;
        }

        try {
            $stmt = $conn->prepare("INSERT INTO [User] (username, hashed_password, email_address, role) VALUES (:username, :hashedPassword, :email, :role)");
            if ($stmt === false) {
                throw new Exception('Failed to prepare the statement');
            }

            return $stmt->execute([
                ':username' => $username,
                ':hashedPassword' => $hashedPassword,
                ':email' => $email,
                ':role' => $role
                // No need to manually insert 'created_at', SQL Server will set it automatically
            ]);
        } catch (Exception $e) {
            error_log('An error occurred: ' . $e->getMessage());
            return false;
        }
    }


    public function getUserByUsernameOrEmail($username, $email)
    {
        $conn = connectDatabase();
        if ($conn === null) {
            return null;
        }

        $stmt = $conn->prepare("SELECT * FROM [User] WHERE username = :username OR email_address = :email");
        $stmt->execute([':username' => $username, ':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
