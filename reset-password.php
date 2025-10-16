<?php
require 'connection.php';
session_start();

$token = $_GET['token'] ?? '';
$errorMsg = '';
$successMsg = '';
$valid = false;

// --- Verify token ---
if (!empty($token)) {
    $sql = "SELECT email, reset_expires FROM users WHERE reset_token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (strtotime($row['reset_expires']) > time()) {
            $valid = true;
            $email = $row['email'];
        } else {
            $errorMsg = "This reset link has expired. Please request a new one.";
        }
    } else {
        $errorMsg = "Invalid reset link.";
    }
    $stmt->close();
} else {
    $errorMsg = "Missing token.";
}

// --- Handle form submission ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['token'])) {
    $token = $_POST['token'];
    $password = trim($_POST['password'] ?? '');
    $confirm = trim($_POST['confirm'] ?? '');

    if ($password === '' || $confirm === '') {
        $errorMsg = "Both password fields are required.";
    } elseif ($password !== $confirm) {
        $errorMsg = "Passwords do not match.";
    } elseif (strlen($password) < 8) {
        $errorMsg = "Password must be at least 8 characters long.";
    } else {
        $stmt = $conn->prepare("SELECT email FROM users WHERE reset_token = ? AND reset_expires > NOW()");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $email = $row['email'];

            $update = $conn->prepare("UPDATE users SET password=?, reset_token=NULL, reset_expires=NULL WHERE email=?");
            $update->bind_param("ss", $hashed, $email);
            $update->execute();
            $update->close();

            $successMsg = "Password reset successful. You can now <a href='login'>log in</a>.";
            $valid = false; // hide form after success
        } else {
            $errorMsg = "Invalid or expired reset token.";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | TripWeb</title>
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

        .login-container input[type="password"] {
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
        <h2>Reset Password</h2>

        <?php if ($errorMsg): ?>
            <div class="error-message"><?php echo htmlspecialchars($errorMsg); ?></div>
        <?php endif; ?>

        <?php if ($successMsg): ?>
            <div class="success-message"><?php echo $successMsg; ?></div>
        <?php endif; ?>

        <?php if ($valid): ?>
            <form id="resetForm" method="post">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

                <label for="password">New Password <span style="color:red;">*</span></label>
                <input type="password" id="password" name="password" placeholder="Enter new password" required>

                <label for="confirm">Confirm Password <span style="color:red;">*</span></label>
                <input type="password" id="confirm" name="confirm" placeholder="Re-enter new password" required>

                <button type="submit" class="login-button">Reset Password</button>
            </form>
        <?php endif; ?>

        <div class="back-login">
            <a href="login">Back to Login</a>
        </div>
    </div>

    <?php include("frontend/footer.php"); ?>
    <?php include("frontend/scrollup.html"); ?>

    <script>
        document.getElementById('resetForm')?.addEventListener('submit', function (event) {
            const pass = document.getElementById('password').value.trim();
            const confirm = document.getElementById('confirm').value.trim();
            document.querySelectorAll('.error-message').forEach(e => e.style.display = 'none');

            if (pass.length < 8) {
                showError('password', 'Password must be at least 8 characters long.');
                event.preventDefault();
            } else if (pass !== confirm) {
                showError('confirm', 'Passwords do not match.');
                event.preventDefault();
            }
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
