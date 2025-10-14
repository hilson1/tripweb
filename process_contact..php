<?php
include("connection.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = htmlspecialchars($_POST['full-name']);
    $email = htmlspecialchars($_POST['email']);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);

    // Web3Mail API endpoint
    $url = 'https://api.web3mail.dev/send';
    
    // Your Web3Mail API key (keep this secure)
    $api_key = 'YOUR_WEB3MAIL_API_KEY';

    $data = [
        'to' => 'info@thankyounepaltrip.com',
        'subject' => "New Contact Form Message: $subject",
        'html' => "
            <h3>Contact Form Submission</h3>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Message:</strong><br>$message</p>
        ",
        'fromName' => 'ThankYouNepalTrip Contact Form',
        'fromAddress' => 'no-reply@thankyounepaltrip.com'
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'x-api-key: ' . $api_key
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpcode == 200) {
        echo "<script>alert('Message sent successfully.'); window.location.href='contact.php';</script>";
    } else {
        echo "<script>alert('Message sending failed. Try again later.'); window.location.href='contact.php';</script>";
    }
}
?>
