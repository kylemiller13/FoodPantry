<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $receiver = "millerkyle85@gmail.com";
    $subject = $_POST["subject"];
    $body = $_POST["body"];
    $sender = "From: kyle.miller18@pcc.edu";

    if (mail($receiver, $subject, $body, $sender)) {
        echo "success";
    } else {
        echo "error";
        $error_message = error_get_last();
        error_log("Email sending error: " . print_r($error_message, true));
    }
}
?>

