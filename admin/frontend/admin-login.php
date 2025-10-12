<?php
session_start();
require 'connection.php'; // adjust path as needed

$FailMsg = '';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        $FailMsg = "Both email and password are required.";
    } else {
        $sql = "SELECT id, email, password, role FROM admins WHERE email = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                if ($user['role'] !== 'admin') {
                    $FailMsg = "Access denied. Admins only.";
                } elseif (password_verify($password, $user['password'])) {
                    $_SESSION['admin_id'] = $user['id'];
                    $_SESSION['admin_email'] = $user['email'];
                    header("Location: ../index.php");
                    exit;
                } else {
                    $FailMsg = "Invalid password.";
                }
            } else {
                $FailMsg = "Admin account not found.";
            }
            $stmt->close();
        } else {
            $FailMsg = "Database error.";
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
<title>Admin Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body {background:#f0f2f5;}
    .login-container {
        margin: 80px auto;
        background: #fff;
        padding: 40px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        width: 100%;
        max-width: 400px;
    }
    .login-container h2 {
        text-align:center;
        margin-bottom:25px;
    }
    .login-container .form-control {
        margin-bottom:15px;
    }
    .login-container .btn {
        background-color:#00bfa5;
        color:white;
    }
    .login-container .btn:hover {
        background-color:#009b87;
    }
    .error-message {
        color:red;
        text-align:center;
        margin-bottom:15px;
    }
</style>
</head>
<body>
<div class="login-container">
    <h2>Admin Login</h2>

    <?php if ($FailMsg): ?>
        <div class="error-message"><?php echo htmlspecialchars($FailMsg); ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Email</label>
        <input type="email" name="email" class="form-control" placeholder="Enter email" required>

        <label>Password</label>
        <input type="password" name="password" class="form-control" placeholder="Enter password" required>

        <button type="submit" class="btn w-100">Login</button>
    </form>

    <div class="text-center mt-3">
        <a href="../forgot-password.php" style="color:#00bfa5;">Forgot Password?</a>
    </div>
</div>
</body>
</html>
