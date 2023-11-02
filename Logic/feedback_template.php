<?php
// Ensure that $feedback, $feedbackType, and $feedbackStatus are set
$feedback = $feedback ?? 'No feedback provided.';
$feedbackType = $feedbackType ?? '';
$feedbackStatus = $feedbackStatus ?? '';

// Start of HTML structure
echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <title>Status</title>
    <link href='../Presentation/style.css' rel='stylesheet' type='text/css'/>
</head>
<body>
<main>
    <h1>Status</h1>";

// Display the feedback message (common to all conditions)
echo "<p>$feedback</p>";

// Check for the type of feedback and display the appropriate message
if ($feedbackType === 'register' && $feedbackStatus === 'success') {
    // Show link to login page for successful registration
    echo "<p><a href='../Presentation/login.html'>Login to your account</a></p>"; // Adjust the path if necessary
} elseif ($feedbackType === 'login' && $feedbackStatus === 'success') {
    // Show the "Redirecting..." message only for successful login
    echo "<p>Redirecting...</p>";
    // Uncomment the following line if you want to automatically redirect after a delay
    // echo "<script>setTimeout(function() { window.location.href = 'future_page.php'; }, 2000);</script>";
}

// Link to go back
echo "<p><a href='../Presentation/login.html'>Go Back</a></p>
</main>
</body>
</html>";

