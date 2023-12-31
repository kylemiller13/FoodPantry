<?php

function sendEmail($to, $subject, $body, &$success) {
    $sender = "From: kyle.miller18@pcc.edu";

    // Validate the email address
    if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
        $success = false;
        return;
    }

    $headers = [
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=iso-8859-1',
        $sender,
    ];

    // Send the email without the Bcc header
    if (mail($to, $subject, $body, implode("\r\n", $headers))) {
        // Log the email details
        $logMessage = "To: $to, Subject: $subject, Body: $body";
        error_log($logMessage);

        // Email sent successfully
    } else {
        // If the email fails, set success to false
        $success = false;
    }
}





