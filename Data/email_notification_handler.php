<?php
require_once 'Database.php';
require_once 'send_email.php';

$requestMethod = isset($_SERVER["REQUEST_METHOD"]) ? $_SERVER["REQUEST_METHOD"] : null;

function processPostRequest() {
    $success = true;

    $users = Database::get_all_email_users();

    if ($users) {
        $subject = $_POST["subject"];
        $body = $_POST["body"];
        $senderId = 42;

        foreach ($users as $user) {
            $email = $user['email_address'];
            sendEmail($email, $subject, $body, $success);
        }

        if ($success) {
            $sent_at = date('Y-m-d H:i:s');
            $number_of_subscribers = count($users);

            if (Database::insert_notification($senderId, $sent_at, $number_of_subscribers, $subject, $body)) {
                echo "Email sent successfully!";
            } else {
                echo "Failed to insert notification data.";
            }
        } else {
            echo "Failed to send email.";
        }
    } else {
        echo "No users found in the database.";
    }
}

if ($requestMethod === "POST") {
    processPostRequest();
}