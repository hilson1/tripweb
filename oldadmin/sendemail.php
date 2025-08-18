<?php
// sendEmail.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure PHPMailer is installed using Composer

function sendVerificationEmail($toEmail, $verificationCode) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'mail.thankyounepaltrip.com'; // Custom mail server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'info@thankyounepaltrip.com'; // Full email as username
        $mail->Password   = 'Ta1234@!@#FGahNep'; // Email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Or ENCRYPTION_SMTPS for SSL
        $mail->Port       = 993;

        // Sender's email
        $mail->setFrom('info@thankyounepaltrip.com', 'Thank You Nepal Trip');
        $mail->addAddress($toEmail);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Email Verification Code';
        $mail->Body    = "
            <h3>Your verification code is: <strong>$verificationCode</strong></h3>
            <p>Please enter this code on the verification page to activate your account.</p>
        ";

        // Send the email
        return $mail->send();
    } catch (Exception $e) {
        echo "Mailer Error: {$mail->ErrorInfo}";
        return false;
    }
}

?>
