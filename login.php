<?php
session_start();
require 'connection.php'; // Ensure this file correctly connects to the database

// Initialize error variables
$FailMsg = '';
$invaliduser = '';

// Ensure form submission is via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if email and password are provided
    if (empty($_POST['email']) || empty($_POST['password'])) {
        $FailMsg = "Both email and password are required.";
    } else {
        // Sanitize input
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        // Check if user exists
        $sql = "SELECT userid, email, password FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    // Store session data
                    $_SESSION['user_id'] = $user['userid'];
                    $_SESSION['email'] = $user['email'];

                    // Set remember me cookie if checked
                    if (isset($_POST['remember-me'])) {
                        setcookie('remember_email', $email, time() + (30 * 24 * 60 * 60), '/');
                    }

                    // Redirect to profile page
                    header("Location: my-account.php");
                    exit();
                } else {
                    $FailMsg = "Invalid password.";
                }
            } else {
                $invaliduser = "User not found.";
            }
            // Close statement
            $stmt->close();
        } else {
            $FailMsg = "Database error. Please try again later.";
        }
    }
    // Close connection
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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

        .login-container input[type="email"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .login-container .remember-me {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .login-container .remember-me input {
            margin-right: 10px;
        }

        .login-container .forgot-password {
            text-align: right;
            margin-bottom: 20px;
        }

        .login-container .forgot-password a {
            color: #00bfa5;
            text-decoration: none;
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

        .login-container .signup {
            text-align: center;
            margin-top: 20px;
        }

        .login-container .signup a {
            color: #00bfa5;
            text-decoration: none;
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 15px;
            text-align: center;
        }

        @media (max-width: 576px) {
            .login-container {
                padding: 20px;
                margin: 20px auto;
            }
        }
    </style>
</head>

<body>
    <?php include("frontend/header.php"); ?>

    <div class="login-container">
        <h2>Login</h2>

        <?php if ($FailMsg): ?>
            <div class="error-message"><?php echo htmlspecialchars($FailMsg); ?></div>
        <?php endif; ?>

        <?php if ($invaliduser): ?>
            <div class="error-message"><?php echo htmlspecialchars($invaliduser); ?></div>
        <?php endif; ?>

        <form id="loginForm" method="post">
            <label for="email">Email <span style="color:red;">*</span></label>
            <input type="email" id="email" name="email" placeholder="Enter your email"
                value="<?php echo isset($_COOKIE['remember_email']) ? htmlspecialchars($_COOKIE['remember_email']) : ''; ?>"
                required>

            <label for="password">Password <span style="color:red;">*</span></label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>

            <div class="remember-me">
                <input type="checkbox" id="remember-me" name="remember-me" <?php echo isset($_COOKIE['remember_email']) ? 'checked' : ''; ?>>
                <label for="remember-me">Remember me</label>
            </div>

            <div class="forgot-password">
                <a href="forgot-password.php">Forgot Password?</a>
            </div>

            <button type="submit" class="login-button">Login</button>
        </form>

        <div class="signup">
            Don't have an account? <a href="signup.php">Sign up</a>
        </div>
    </div>

    <?php include("frontend/footer.php"); ?>
    <?php include("frontend/scrollup.html"); ?>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function (event) {
            // Client-side validation
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            let isValid = true;

            // Clear previous errors
            document.querySelectorAll('.error-message').forEach(el => el.style.display = 'none');

            // Email validation
            if (!email) {
                showError('email', 'Email is required.');
                isValid = false;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                showError('email', 'Please enter a valid email address.');
                isValid = false;
            }

            // Password validation
            if (!password) {
                showError('password', 'Password is required.');
                isValid = false;
            } else if (password.length < 6) {
                showError('password', 'Password must be at least 6 characters.');
                isValid = false;
            }

            if (!isValid) {
                event.preventDefault();
            }
        });

        function showError(fieldId, message) {
            const field = document.getElementById(fieldId);
            let errorElement = document.getElementById(fieldId + '-error');

            if (!errorElement) {
                errorElement = document.createElement('div');
                errorElement.id = fieldId + '-error';
                errorElement.className = 'error-message';
                field.parentNode.insertBefore(errorElement, field.nextSibling);
            }

            errorElement.textContent = message;
            errorElement.style.display = 'block';
            errorElement.style.textAlign = 'left';
            errorElement.style.marginBottom = '10px';
        }
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>