<?php
$receiver = "millerkyle85@gmail.com";
$subject = "Email Test via PHP using Localhost";
$body = "Hi, there...This is a test email sent from Localhost.";
$sender = "From: kyle.miller18@pcc.edu";

if (mail($receiver, $subject, $body, $sender)) {
    echo "Email sent successfully to $receiver";
} else {
    echo "Sorry, failed while sending mail!";
}
?>
