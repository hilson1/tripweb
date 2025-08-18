<?php
session_start();  // Start the session at the very beginning

include('connection.php'); // Include the database connection

// Fetch the admin details from the database using the session username (or ID)
if (isset($_SESSION['admin'])) {
    $username = $_SESSION['admin'];  // This should be the logged-in admin's email or username stored in the session

    // Sanitize input to prevent SQL injection
    $username = $conn->real_escape_string($username);

    // Query to get admin details from the database
    $sql = "SELECT * FROM admin WHERE email = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $adminName = $row['name'];  // Replace with actual column name in the database for the name
        $adminRole = $row['role'];  // Replace with actual column name in the database for the role (if any)
        $adminAvatar = $row['avatar'];  // Assuming there's a column for the avatar image URL in the database
        $adminPassword = $row['password']; // Assuming 'password' is the column in the database
    } else {
        // Handle the case if the admin doesn't exist
        echo "<script>alert('Admin data not found');</script>";
    }
} else {
    // If not logged in, redirect to login page
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validate current password
    if (password_verify($currentPassword, $adminPassword)) {
        // Check if new password and confirm password match
        if ($newPassword == $confirmPassword) {
            // Hash the new password
            $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update the password in the database
            $updateSql = "UPDATE admin SET password = '$newPasswordHash' WHERE email = '$username'";
            if ($conn->query($updateSql) === TRUE) {
                echo "<script>alert('Password updated successfully');</script>";
            } else {
                echo "<script>alert('Error updating password');</script>";
            }
        } else {
            echo "<script>alert('New password and confirm password do not match');</script>";
        }
    } else {
        echo "<script>alert('Current password is incorrect');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile - Travel Monster</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        .profile-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            text-align: center;
        }

        .profile-card img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
        }

        .stats-card {
            background-color: #ecf0f1;
            border-radius: 10px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .stats-card h3 {
            font-size: 1.25rem;
        }
         .back-button {
            width: 100%;
            padding: 10px;
            background-color: #f44336; /* Red color */
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }
        .back-button:hover {
            background-color: #d32f2f; /* Darker red on hover */
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
            dropdownToggles.forEach(toggle => {
                toggle.addEventListener('click', function () {
                    const dropdownMenu = this.nextElementSibling;
                    dropdownMenu.classList.toggle('hidden');
                });
            });
        });
    </script>
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <?php
        include("frontend/asidebar.php");
        ?>

        <!-- Main Content -->
        <div class="w-3/4 p-6 ml-64 mt-16">
            
            <!-- Change Password Form -->
            <div class="w-1/2 mx-auto bg-white p-6 rounded shadow-lg">
                <h2 class="text-2xl font-semibold mb-4">Change Password</h2>
                <form action="" method="POST">
                    <div class="mb-4">
                        <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                        <input type="password" id="current_password" name="current_password" class="mt-2 block w-full p-2 border rounded" required>
                    </div>
                    <div class="mb-4">
                        <label for="new_password" class="block text-sm font-medium text-gray-700">New Password</label>
                        <input type="password" id="new_password" name="new_password" class="mt-2 block w-full p-2 border rounded" required>
                    </div>
                    <div class="mb-4">
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="mt-2 block w-full p-2 border rounded" required>
                    </div>
                    <button type="submit" name="change_password" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600 w-full">Change Password</button>
                     <button class="back-button" onclick="window.location.href='profileview.php'">Cancel</button>
                </form>
            </div>

            <!-- Stats Section -->
            <div class="grid grid-cols-3 gap-6 mt-8">
                <div class="stats-card">
                    <h3>Total Users</h3>
                    <p class="text-2xl font-bold">1,245</p>
                </div>
                <div class="stats-card">
                    <h3>Active Users</h3>
                    <p class="text-2xl font-bold">1,120</p>
                </div>
                <div class="stats-card">
                    <h3>Pending Requests</h3>
                    <p class="text-2xl font-bold">52</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
