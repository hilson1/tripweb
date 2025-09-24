<?php
// Make sure to include the Composer autoloader or the PHPMailer files directly
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // If using Composer

function sendOtpEmail($email, $otp) {
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'mail.thankyounepaltrip.com'; // Use your SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'info@thankyounepaltrip.com'; // Your SMTP username
        $mail->Password   = '';   // Your SMTP password (use an app password for security)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 465;

        // Recipients
        $mail->setFrom('no-reply@thankyounepaltrip.com', 'thankyounepaltrip.com');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP for Signup';
        $mail->Body    = "Your One-Time Password (OTP) is: <b>$otp</b>. This OTP is valid for 5 minutes.";
        $mail->AltBody = "Your One-Time Password (OTP) is: $otp.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        // You can log the error for debugging
        // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        return false;
    }
}
?>