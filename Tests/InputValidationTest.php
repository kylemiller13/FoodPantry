<?php

require 'vendor/autoload.php';



use PHPUnit\Framework\TestCase;
use Data\services\PasswordValidator;

class InputValidationTest extends TestCase
{
    public function testValidateMinLength()
    {
        $passwordValidator = new PasswordValidator();
        $this->assertTrue($passwordValidator->validateMinLength("12345678", 8), "Failed to validate minimum length.");
    }

    public function testValidateMaxLength()
    {
        $passwordValidator = new PasswordValidator();
        $this->assertTrue($passwordValidator->validateMaxLength("12345", 10), "Failed to validate maximum length.");
    }

    public function testValidateCharacterTypes()
    {
        $passwordValidator = new PasswordValidator();
        $this->assertTrue($passwordValidator->validateCharacterTypes("Aa1!"), "Failed to validate character types.");
    }

    public function testValidateAgainstCommonPasswords()
    {
        $passwordValidator = new PasswordValidator();
        $this->assertFalse($passwordValidator->validateAgainstCommonPasswords("password"), "Failed to validate against common passwords.");
    }

    public function testValidateSequentialAndRepeatedCharacters()
    {
        $passwordValidator = new PasswordValidator();
        $this->assertFalse($passwordValidator->validateSequentialAndRepeatedCharacters("111"), "Failed to validate sequential and repeated characters.");
    }
}
