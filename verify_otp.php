<?php
session_start();
require 'connection.php'; // your DB connection
require 'vendor/autoload.php'; // PHPMailer autoload

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$FailMsg = "";

// Step 1: Handle first visit (send OTP)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['email']) && !isset($_POST['otp'])) {
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Generate OTP and store signup data in session
    $otp = rand(100000, 999999);
    $_SESSION['signup_data'] = [
        'email' => $email,
        'phone' => $phone,
        'username' => $username,
        'password' => $password,
        'otp' => $otp
    ];

    // Send OTP email via cPanel SMTP
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'mail.thankyounepaltrip.com'; // cPanel mail host
        $mail->SMTPAuth   = true;
        $mail->Username   = 'info@thankyounepaltrip.com';
        $mail->Password   = 'T@1234@!@#FGahNep';
        $mail->SMTPSecure = 'ssl'; // or 'tls' if port 587
        $mail->Port       = 465;   // or 587

        $mail->setFrom('info@thankyounepaltrip.com', 'Thank You Nepal Trip');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code - Thank You Nepal Trip';
        $mail->Body    = "<p>Dear user,</p>
        <p>Your One-Time Password (OTP) is: <strong>$otp</strong>.</p>
        <p>This code will expire in 10 minutes. Please do not share it with anyone.</p>
        <p>Regards,<br>Thank You Nepal Trip Team</p>";
        $mail->AltBody = "Your OTP code is $otp. It expires in 10 minutes.";


        $mail->send();
    } catch (Exception $e) {
        $FailMsg = "Error sending OTP: " . $mail->ErrorInfo;
    }
}

// Step 2: Handle OTP verification
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['otp'])) {
    if (!isset($_SESSION['signup_data'])) {
        header('location: signup');
        exit();
    }

    $user_otp = trim($_POST['otp']);
    $stored_otp = $_SESSION['signup_data']['otp'];

    if ($user_otp == $stored_otp) {
        // OTP correct → create user
        $userid = uniqid('user_');
        $phone = $_SESSION['signup_data']['phone'];
        $email = $_SESSION['signup_data']['email'];
        $username = $_SESSION['signup_data']['username'];
        $hashed_password = $_SESSION['signup_data']['password'];

        $sql = "INSERT INTO users (userid, phone_number, email, user_name, password)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $userid, $phone, $email, $username, $hashed_password);

        if ($stmt->execute()) {
            unset($_SESSION['signup_data']);
            header('location: login?msg=success');
            exit();
        } else {
            $FailMsg = "Account creation failed.";
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
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
<style>
.login-container {margin:40px auto;padding:40px;max-width:400px;background:#fff;border-radius:8px;box-shadow:0 0 10px rgba(0,0,0,0.1);}
.login-container h2 {text-align:center;margin-bottom:20px;}
</style>
</head>
<body>
<?php include("frontend/header.php"); ?>

<div class="login-container">
    <h2>Verify OTP</h2>
    <?php if (isset($_SESSION['signup_data'])): ?>
        <form method="post">
            <p>OTP sent to: <strong><?php echo htmlspecialchars($_SESSION['signup_data']['email']); ?></strong></p>
            <p style="color:red;"><?php echo $FailMsg; ?></p>
            <label for="otp">Enter OTP</label>
            <input type="text" id="otp" name="otp" class="form-control mb-3" placeholder="Enter OTP" required>
            <button type="submit" class="btn btn-success w-100">Verify OTP</button>
        </form>
    <?php else: ?>
        <p>No signup session found.</p>
    <?php endif; ?>
</div>

<?php include("frontend/footer.php"); ?>
</body>
</html>
