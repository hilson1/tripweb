<?php
include("frontend/session_start.php");
?>
<?php
require 'connection.php'; // Include database connection
$SuccessMsg = "";
$FailMsg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data

    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate inputs
    // Check if email already exists
    $checkEmail = "SELECT email FROM users WHERE email = ?";
    $stmt = $conn->prepare($checkEmail);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $FailMsg = "Email already exists ! Use different email";
        $stmt->close();
        $conn->close();
    } else {

        // Generate a unique user ID
        $userid = uniqid('user_');

        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insert user data into the database
        $sql = "INSERT INTO users (userid, phone_number, email, user_name, password) VALUES (?,?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $userid, $phone, $email, $username, $hashed_password);

        if ($stmt->execute()) {
            $SuccessMsg = "Signup successful!";
            header('location:login.php');
        } else {
            $FailMsg = "Signup unsuccessful!";
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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

        .error {
            color: red;
            font-size: 0.9em;
        }

        @media (max-width: 576px) {
            .login-container {
                padding: 20px;
            }
        }
    </style>
</head>

<?php
include("frontend/header.php");
?>
<div class="login-container">
    <h2>Signup</h2>
    <form id="loginForm" method="post">
        <span style="color: green; font-size: 20px;"><?php echo $SuccessMsg; ?></span>
        <span style="color: red; font-size: 20px;"><?php echo $FailMsg; ?></span>
        <label for="email">Email <span style="color:red;">*</span></label>
        <input type="text" id="email" name="email" placeholder="Email" required>
        <label for="phone">Phone Number <span style="color:red;">*</span></label>
        <input type="text" id="phone" name="phone" placeholder="phone number" required>
        <div id="emailError" class="error"></div>
        <label for="username">Username <span style="color:red;">*</span></label>
        <input type="text" id="username" name="username" placeholder="Username" required>
        <div id="usernameError" class="error"></div>
        <label for="password">Password <span style="color:red;">*</span></label>
        <input type="password" id="password" name="password" placeholder="Password" required>
        <div id="passwordError" class="error"></div>
        <label for="password">Confirm Password <span style="color:red;">*</span></label>
        <input type="password" id="cpassword" name="cpassword" placeholder="Confirm Password" required>
        <div id="passwordError" class="error"></div>
        <button type="submit" class="login-button">SIGN UP</button>
    </form>
    <div class="signup">
        Have An account? <a href="login">Login</a>
    </div>
</div>



<?php
include("frontend/footer.php");
?>
<?php
include("frontend/scrollup.html");
?>
<script>
    document.getElementById('loginForm').addEventListener('submit', function (event) {
        let isValid = true;

        // Clear previous error messages
        document.getElementById('emailError').textContent = '';
        document.getElementById('usernameError').textContent = '';
        document.getElementById('passwordError').textContent = '';

        // Validate Email
        const email = document.getElementById('email').value.trim();
        if (!email) {
            document.getElementById('emailError').textContent = 'Email is required.';
            isValid = false;
        } else if (!/\S+@\S+\.\S+/.test(email)) {
            document.getElementById('emailError').textContent = 'Email is not valid.';
            isValid = false;
        }

        // Validate Username
        const username = document.getElementById('username').value.trim();
        if (!username) {
            document.getElementById('usernameError').textContent = 'Username is required.';
            isValid = false;
        } else if (username.length < 3) {
            document.getElementById('usernameError').textContent = 'Username must be at least 3 characters long.';
            isValid = false;
        }

        // Validate Password
        const password = document.getElementById('password').value.trim();
        const cpassword = document.getElementById('cpassword').value.trim();
        if (!password) {
            document.getElementById('passwordError').textContent = 'Password is required.';
            isValid = false;
        } else if (password.length < 6) {
            document.getElementById('passwordError').textContent = 'Password must be at least 6 characters long.';
            isValid = false;
        } else if (cpassword != password) {
            document.getElementById('passwordError').textContent = 'Password does not match !';
            isValid = false;
        }
        // Prevent form submission if validation fails
        if (!isValid) {
            event.preventDefault();
        }
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>