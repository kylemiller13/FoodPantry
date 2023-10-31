<?php
require 'Database.php'; 

//Checks  if the HTTP request method is POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // track whether the email sending process is successful or not.
    $success = true;
    
    // Function to send an email to a single recipient
    function sendEmail($to, $subject, $body, &$success) {
        $sender = "From: kyle.miller18@pcc.edu";
        $headers = "Bcc: " . $to . "\r\n";

        if (mail("", $subject, $body, $headers)) {
            // Email sent successfully
        } else {
            // If the email fails, set success to false
            $success = false;
        }
    }

    // Get all user email addresses from the database
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
            //records the current date and time
            $sent_at = date('Y-m-d H:i:s'); 
            //counts the number of subscribers (email addresses) and stores in the $number_of_subscribers
            $number_of_subscribers = count($users); 

            // Call the insert_notification method to insert a notification record into the database. 
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
?>

