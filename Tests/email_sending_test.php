<?php

require_once './Data/send_email.php';
require_once './Data/Database_Connection.php';

use PHPUnit\Framework\TestCase;


class email_sending_test extends TestCase
{
    public function testSendEmailSuccess()
    {
        $to = 'test@example.com';
        $subject = 'Test Subject';
        $body = 'Test Body';
        $success = true;

        sendEmail($to, $subject, $body, $success);

        $this->assertTrue($success, 'Email sending should be successful.');
    }

    public function testSendEmailFailure()
    {
        $to = 'test@example.com';
        $subject = 'Test Subject';
        $body = 'Test Body';
        $success = false;

        sendEmail($to, $subject, $body, $success);

        $this->assertFalse($success, 'Email sending should fail.');
    }

    public function testSendEmailWithEmptyReceiver
    ()
    {
        $to = '';
        $subject = 'Test Subject';
        $body = 'Test Body';
        $success = true;

        sendEmail($to, $subject, $body, $success);

        $this->assertFalse($success, 'Email sending should fail with an empty Receiver.');
    }

    public function testSendEmailWithInvalidEmail()
    {
        $to = 'invalid_email';
        $subject = 'Test Subject';
        $body = 'Test Body';
        $success = true;

        sendEmail($to, $subject, $body, $success);

        $this->assertFalse($success, 'Email sending should fail with an invalid email.');
    }

    public function testSendEmailWithValidRecipient()
    {
        $to = 'valid@example.com';
        $subject = 'Test Subject';
        $body = 'Test Body';
        $success = true;

        sendEmail($to, $subject, $body, $success);

        $this->assertTrue($success, 'Email sending should be successful with a valid recipient.');
    }




}