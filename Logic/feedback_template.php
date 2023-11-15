<?php
// Ensure that $feedback, $feedbackType, and $feedbackStatus are set
$feedback = $feedback ?? 'No feedback provided.';
$feedbackType = $feedbackType ?? '';
$feedbackStatus = $feedbackStatus ?? '';

// Define URLs for redirection or navigation
$loginUrl = '../Presentation/login.html'; // Adjust if necessary
$homeUrl = 'future_page.php'; // URL of the homepage or dashboard after login

// Start of HTML structure
echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Status</title>
    <link href='../Presentation/style.css' rel='stylesheet' type='text/css'/>
</head>
<body>
<main>
    <h1>Status</h1>";

// Display the feedback message
echo "<p class='" . ($feedbackStatus === 'success' ? 'success-message' : 'error-message') . "'>$feedback</p>";

// Conditional feedback based on the type and status
if ($feedbackType === 'register' && $feedbackStatus === 'success') {
    echo "<p><a href='$loginUrl'>Login to your account</a></p>";
} elseif ($feedbackType === 'login' && $feedbackStatus === 'success') {
    echo "<p>Redirecting to your account...</p>";
    echo "<script>setTimeout(function() { window.location.href = '$homeUrl'; }, 3000);</script>";
}

// Link to go back (useful for error scenarios)
if ($feedbackStatus !== 'success') {
    echo "<p><a href='$loginUrl'>Go Back</a></p>";
}

echo "</main>
</body>
</html>";
