<?php

namespace services;

class PasswordValidator {

    /**
     * Validates the minimum length of the password.
     *
     * @param string $password
     * @param int $minLength
     * @return bool
     */
    public function validateMinLength(string $password, int $minLength): bool {
        return strlen($password) >= $minLength;
    }

    /**
     * Validates the maximum length of the password.
     *
     * @param string $password
     * @param int $maxLength
     * @return bool
     */
    public function validateMaxLength(string $password, int $maxLength): bool {
        return strlen($password) <= $maxLength;
    }

    /**
     * Validates the character types of the password.
     *
     * @param string $password
     * @return bool
     */
    public function validateCharacterTypes(string $password): bool {
        $containsUpper = preg_match('/[A-Z]/', $password);
        $containsLower = preg_match('/[a-z]/', $password);
        $containsDigit = preg_match('/\d/', $password);
        $containsSpecial = preg_match('/[^a-zA-Z\d]/', $password);

        return $containsUpper && $containsLower && $containsDigit && $containsSpecial;
    }

    /**
     * Validates against common passwords.
     *
     * @param string $password
     * @return bool
     */
    public function validateAgainstCommonPasswords(string $password): bool {
        $commonPasswords = ['password', '123456', 'qwerty']; // add to this
        return !in_array($password, $commonPasswords, true);
    }

    /**
     * Validates for sequential and repeated characters.
     *
     * @param string $password
     * @return bool
     */
    public function validateSequentialAndRepeatedCharacters(string $password): bool {
        return !preg_match('/(\d)\1{2,}/', $password); // Checks for 3 or more repeated digits
    }

    /**
     * Provides user feedback based on password strength.
     *
     * @param string $password
     * @return string
     */
    public function getUserFeedback(string $password): string {
        if (!$this->validateMinLength($password, 8)) {
            return 'Password is too short.';
        }
        if (!$this->validateCharacterTypes($password)) {
            return 'Password must include uppercase, lowercase, numbers, and special characters.';
        }
        // Add more conditions based on other validation checks
        return 'Password is strong.';
    }

    // Add methods
}
