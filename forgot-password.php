<?php


session_start();
require 'connection.php'; // ensure DB connection

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'mail.thankyounepaltrip.com'; // cPanel mail host
    $mail->SMTPAuth = true;
    $mail->Username = 'info@thankyounepaltrip.com'; // full cPanel email
    $mail->Password = 'T@1234@!@#FGahNep'; // your cPanel email password
    $mail->SMTPSecure = 'ssl'; // or 'ssl'
    $mail->Port = 465; 

    $mail->setFrom('info@thankyounepaltrip.com', 'TripWeb');
    $mail->addAddress($email);
    $mail->Subject = 'Password Reset Request';
    $mail->Body = "Click here to reset your password: $resetLink";

    $mail->send();
    $successMsg = "A password reset link has been sent to your email.";
} catch (Exception $e) {
    $errorMsg = "Email could not be sent. Error: {$mail->ErrorInfo}";
}


$successMsg = '';
$errorMsg = '';

// process form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {
        $errorMsg = "Email is required.";
    } else {
        $sql = "SELECT userid FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                // generate token
                $token = bin2hex(random_bytes(32));
                $expires = date("Y-m-d H:i:s", strtotime("+1 hour"));

                // save token
                $update = $conn->prepare("UPDATE users SET reset_token=?, reset_expires=? WHERE email=?");
                $update->bind_param("sss", $token, $expires, $email);
                $update->execute();
                $update->close();

                // send email (simple text link; replace with actual mailer)
                $resetLink = "http://" . $_SERVER['HTTP_HOST'] . "/reset-password.php?token=$token";
                mail($email, "Password Reset Request", "Click here to reset your password: $resetLink");

                $successMsg = "A password reset link has been sent to your email.";
            } else {
                $errorMsg = "No account found with that email.";
            }
            $stmt->close();
        } else {
            $errorMsg = "Database error. Try again later.";
        }
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="index.css">
    <style>
        .login-container {
            margin: 50px auto;
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .login-container h2 {
            margin-bottom: 20px;
            font-weight: 500;
            font-size: 24px;
            text-align: center;
        }

        .login-container label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .login-container input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .login-container .login-button {
            width: 100%;
            padding: 10px;
            background-color: #00bfa5;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .login-container .login-button:hover {
            background-color: #008c7a;
        }

        .login-container .back-login {
            text-align: center;
            margin-top: 20px;
        }

        .login-container .back-login a {
            color: #00bfa5;
            text-decoration: none;
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 15px;
            text-align: center;
        }

        .success-message {
            color: green;
            font-size: 14px;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>

<body>
    <?php include("frontend/header.php"); ?>

    <div class="login-container">
        <h2>Forgot Password</h2>

        <?php if ($errorMsg): ?>
            <div class="error-message"><?php echo htmlspecialchars($errorMsg); ?></div>
        <?php endif; ?>

        <?php if ($successMsg): ?>
            <div class="success-message"><?php echo htmlspecialchars($successMsg); ?></div>
        <?php endif; ?>

        <form id="forgotForm" method="post">
            <label for="email">Enter your registered email <span style="color:red;">*</span></label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>

            <button type="submit" class="login-button">Send Reset Link</button>
        </form>

        <div class="back-login">
            <a href="login.php">Back to Login</a>
        </div>
    </div>

    <?php include("frontend/footer.php"); ?>
    <?php include("frontend/scrollup.html"); ?>

    <script>
        document.getElementById('forgotForm').addEventListener('submit', function (event) {
            const email = document.getElementById('email').value.trim();
            let valid = true;
            document.querySelectorAll('.error-message').forEach(e => e.style.display = 'none');

            if (!email) {
                showError('email', 'Email is required.');
                valid = false;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                showError('email', 'Enter a valid email.');
                valid = false;
            }

            if (!valid) event.preventDefault();
        });

        function showError(id, msg) {
            const field = document.getElementById(id);
            let err = document.getElementById(id + '-error');
            if (!err) {
                err = document.createElement('div');
                err.id = id + '-error';
                err.className = 'error-message';
                field.parentNode.insertBefore(err, field.nextSibling);
            }
            err.textContent = msg;
            err.style.display = 'block';
            err.style.textAlign = 'left';
        }
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
