<?php
session_start();
include('connection.php'); // Include the database connection

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email'])) {
        // Handle forgot password
        $email = $_POST['email'];

        // Sanitize the email to prevent SQL injection
        $email = $conn->real_escape_string($email);

        // Check if the email exists in the database
        $sql = "SELECT * FROM admin WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Email exists, generate a password reset token
            $token = bin2hex(random_bytes(50)); // Generate a random token
            $expiry = date("Y-m-d H:i:s", strtotime('+1 hour')); // Token expiration time

            // Update the token and expiry in the database
            $updateSql = "UPDATE admin SET reset_token = '$token', reset_token_expiry = '$expiry' WHERE email = '$email'";
            if ($conn->query($updateSql) === TRUE) {
                // Send password reset email with the reset link
                $resetLink = "http://yourdomain.com/reset_password.php?token=$token"; // Modify with your domain

                // Email subject and body
                $subject = "Password Reset Request";
                $message = "To reset your password, click the link below:\n\n$resetLink";
                $headers = "From: no-reply@yourdomain.com";

                if (mail($email, $subject, $message, $headers)) {
                    echo "<script>alert('Password reset link has been sent to your email.');</script>";
                } else {
                    echo "<script>alert('Failed to send the email. Please try again later.');</script>";
                }
            } else {
                echo "<script>alert('Error updating the reset token.');</script>";
            }
        } else {
            // If email doesn't exist
            echo "<script>alert('No account found with that email address.');</script>";
        }
    }
}

// If user clicks the link with a token, handle the password reset process
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $sql = "SELECT * FROM admin WHERE reset_token = '$token' AND reset_token_expiry > NOW()";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Valid token, show password reset form
        if (isset($_POST['new_password'])) {
            $newPassword = $_POST['new_password'];
            $newPasswordHash = password_hash($newPassword, PASSWORD_BCRYPT);

            // Update the password and clear the reset token
            $updateSql = "UPDATE admin SET password = '$newPasswordHash', reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = '$token'";
            if ($conn->query($updateSql) === TRUE) {
                echo "<script>alert('Password successfully updated. You can now log in.'); window.location.href = 'login.php';</script>";
            } else {
                echo "<script>alert('Error updating the password.');</script>";
            }
        }
    } else {
        // Invalid or expired token
        echo "<script>alert('Invalid or expired token.');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
        .forgot-password-container {
            margin: 0 auto;
            background-color: #fff;
            padding: 40px;
            margin-top: 40px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .forgot-password-container h2 {
            margin-bottom: 20px;
            font-weight: 500;
            font-size: 24px;
            text-align: center;
        }
        .forgot-password-container label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        .forgot-password-container input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .forgot-password-container button {
            width: 100%;
            padding: 10px;
            background-color: #00bfa5;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        .forgot-password-container .back-button {
            width: 100%;
            padding: 10px;
            background-color: #f44336; /* Red color */
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }
        .forgot-password-container .back-button:hover {
            background-color: #d32f2f; /* Darker red on hover */
        }
        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <div class="forgot-password-container">
        <?php if (!isset($_GET['token'])): ?>
            <h2>Forgot Password</h2>
            <form method="POST" action="">
                <label for="email">Enter your email address:<span style="color:red;">*</span></label>
                <input type="email" id="email" name="email" placeholder="Email" required>
                <button type="submit">Reset Password</button>
            </form>
            <button class="back-button" onclick="window.location.href='adminlogin'">Back to Login</button>
        <?php else: ?>
            <h2>Reset Password</h2>
            <form method="POST" action="">
                <label for="new_password">New Password:<span style="color:red;">*</span></label>
                <input type="password" id="new_password" name="new_password" placeholder="New Password" required>
                <button type="submit">Submit New Password</button>
            </form>
            <button class="back-button" onclick="window.location.href='/admin-login'">Back to Login</button>
        <?php endif; ?>
    </div>

</body>
</html>
