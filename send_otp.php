<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Composer autoload

function sendOtpEmail($email, $otp) {
    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host       = 'mail.thankyounepaltrip.com'; // cPanel mail host
        $mail->SMTPAuth   = true;
        $mail->Username   = 'info@thankyounepaltrip.com'; // full email address
        $mail->Password   = 'T@1234@!@#FGahNep';  // replace with correct password
        $mail->SMTPSecure = 'ssl'; // use 'ssl' for port 465, or 'tls' for 587
        $mail->Port       = 465;   // use 465 for SSL, 587 for TLS

        // Sender and recipient
        $mail->setFrom('info@thankyounepaltrip.com', 'Thank You Nepal Trip');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP for Signup';
        $mail->Body    = "Your One-Time Password (OTP) is: <b>$otp</b>. It is valid for 5 minutes.";
        $mail->AltBody = "Your One-Time Password (OTP) is: $otp.";

        $mail->send();
        return true;

    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
        return false;
    }
}
?>
