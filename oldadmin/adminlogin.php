<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <style>
        .login-container {
            margin: 0 auto;
            background-color: #fff;
            padding: 40px ;
            margin-top:40px;
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
        }

        .login-container .signup {
            text-align: center;
            margin-top: 20px;
        }

        .login-container .signup a {
            color: #00bfa5;
            text-decoration: none;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .close-button {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            font-size: 20px;
            color: #333;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .close-button:hover {
            color: #f44336;
        }

        @media (max-width: 576px) {
            .login-container {
                padding: 20px;
            }
        }
    </style>
</head>
    <?php
    session_start();
    include('connection.php'); // Include the database connection

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the input values
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Sanitize input to prevent SQL injection
        $username = $conn->real_escape_string($username);
        $password = $conn->real_escape_string($password);

        // Query to check if the user exists
        $sql = "SELECT * FROM admin WHERE email = '$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            
            // Use password_verify() to check if the password matches the hashed password
            if (password_verify($password, $row['password'])) {
                // Password matches, set session and redirect to dashboard
                $_SESSION['admin'] = $username;
                header('Location: dashboard.php'); // Redirect to your admin dashboard (dash.php)
                exit(); // Ensure no further code is executed
            } else {
                // Invalid password
                echo "<script>alert('Invalid username or password');</script>";
            }
        } else {
            // User not found
            echo "<script>alert('Invalid username or password');</script>";
        }
    }

    $conn->close();
    ?>

    <div class="login-container">
        <h2>Admin Login</h2>
        <form id="loginForm" method="POST" action="adminlogin.php">
            <label for="username">Email <span style="color:red;">*</span></label>
            <input type="text" id="username" name="username" placeholder="Email" required>
            <div id="usernameError" class="error"></div>

            <label for="password">Password <span style="color:red;">*</span></label>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <div id="passwordError" class="error"></div>

            <div class="remember-me">
                <input type="checkbox" id="remember-me" name="remember-me">
                <label for="remember-me">Remember me</label>
            </div>

            <div class="forgot-password">
                <a href="forgotpass.php">Forgot Password?</a>
            </div>

            <button type="submit" class="login-button">LOG IN</button>
        </form>
        <div class="signup">
            Don't have an account? <a href="adminsignup">Sign up</a>
        </div>
    </div>

    <script>
    document.getElementById('loginForm').addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent form submission if validation fails

        // Clear previous errors
        document.getElementById('usernameError').textContent = '';
        document.getElementById('passwordError').textContent = '';

        let isValid = true;

        // Username validation
        const username = document.getElementById('username').value;
        if (!username) {
            document.getElementById('usernameError').textContent = 'Username is required.';
            isValid = false;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(username)) {
            document.getElementById('usernameError').textContent = 'Enter a valid email address.';
            isValid = false;
        }

        // Password validation
        const password = document.getElementById('password').value;
        if (!password) {
            document.getElementById('passwordError').textContent = 'Password is required.';
            isValid = false;
        } else if (password.length < 6) {
            document.getElementById('passwordError').textContent = 'Password must be at least 6 characters.';
            isValid = false;
        }

        if (isValid) {
            this.submit(); // Submit the form if valid
        }
    });

    const closeButton = document.getElementById('closeButton');
    const loginContainer = document.getElementById('loginContainer');

    closeButton.addEventListener('click', () => {
        loginContainer.style.display = 'none';
    });
    </script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>