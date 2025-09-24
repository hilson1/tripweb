<?php

// Check if the form was submitted via POST method.
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Define the recipient email address.
    $to_email = "info@thankyounepaltrip.com";

    // Sanitize and validate form inputs.
    $name = htmlspecialchars(trim($_POST['full-name']));
    $from_email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Simple validation to ensure required fields are not empty and email is valid.
    if (empty($name) || empty($from_email) || empty($subject) || empty($message) || !filter_var($from_email, FILTER_VALIDATE_EMAIL)) {
        // Redirect back to the contact page with an error status.
        header("Location: contact.php?status=error&message=Invalid%20form%20submission.");
        exit;
    }

    // Construct the email body.
    $email_body = "
    <h2>New Contact Form Submission</h2>
    <p><strong>Name:</strong> {$name}</p>
    <p><strong>Email:</strong> {$from_email}</p>
    <p><strong>Subject:</strong> {$subject}</p>
    <p><strong>Message:</strong><br>{$message}</p>
    ";

    // Set email headers to ensure proper formatting and reply-to functionality.
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: Thank You Nepal Trip <noreply@thankyounepaltrip.com>" . "\r\n";
    $headers .= "Reply-To: {$name} <{$from_email}>" . "\r\n";

    // Attempt to send the email.
    if (mail($to_email, $subject, $email_body, $headers)) {
        // If successful, redirect with a success status.
        header("Location: contact.php?status=success&message=Your%20message%20has%20been%20sent%20successfully!");
    } else {
        // If mail() fails, redirect with an error status.
        header("Location: contact.php?status=error&message=Failed%20to%20send%20message.");
    }
    exit;

} else {
    // If someone tries to access this page directly without submitting the form, redirect them.
    header("Location: contact.php");
    exit;
}
?>