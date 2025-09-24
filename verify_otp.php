<?php
session_start();
require 'connection.php'; // Include database connection

$FailMsg = "";

// Check if signup data exists in the session
if (!isset($_SESSION['signup_data'])) {
    header('location: signup.php'); // Redirect if no signup data is found
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_otp = trim($_POST['otp']);
    $stored_otp = $_SESSION['signup_data']['otp'];

    if ($user_otp == $stored_otp) {
        // OTP is correct, proceed with user creation
        $userid = uniqid('user_');
        $phone = $_SESSION['signup_data']['phone'];
        $email = $_SESSION['signup_data']['email'];
        $username = $_SESSION['signup_data']['username'];
        $hashed_password = $_SESSION['signup_data']['password'];

        $sql = "INSERT INTO users (userid, phone_number, email, user_name, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $userid, $phone, $email, $username, $hashed_password);

        if ($stmt->execute()) {
            // User created successfully, clear session data
            unset($_SESSION['signup_data']);
            // Redirect to login or index page
            header('location: login.php?msg=success');
            exit();
        } else {
            $FailMsg = "Account creation failed. Please try again.";
        }

        $stmt->close();
        $conn->close();

    } else {
        $FailMsg = "Invalid OTP. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="index.css">
    <style>
        .login-container {
            margin: 0 auto;
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

        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
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
        }

        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }
    </style>
</head>
<?php include("frontend/header.php"); ?>
<div class="login-container">
    <h2>Verify OTP</h2>
    <form method="post">
        <p>An OTP has been sent to your email address: **<?php echo htmlspecialchars($_SESSION['signup_data']['email']); ?>**</p>
        <span style="color: red; font-size: 20px;"><?php echo $FailMsg; ?></span>
        <label for="otp">Enter OTP</label>
        <input type="text" id="otp" name="otp" placeholder="Enter OTP" required>
        <button type="submit" class="login-button">Verify OTP</button>
    </form>
</div>
<?php
include("frontend/footer.php");
include("frontend/scrollup.html");
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>