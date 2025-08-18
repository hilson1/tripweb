<?php
require "../connection.php";
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    // Get form data
    $firstname = trim($_POST['firstName']);
    $lastname = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $username = trim($_POST['userName']);
    $password = trim($_POST['password']);
    // Validate inputs
    // Generate a unique user ID
    $userid = uniqid('user_');
    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    // Insert user data into the database
    $sql = "INSERT INTO users (userid, phone_number, email, user_name,first_name, last_name, password) VALUES (?,?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $userid, $phone, $email, $username, $firstname, $lastname, $hashed_password);
    if ($stmt->execute()) {
        echo "<script> alert('Registration Successful'); </script>";
    } else {
        echo "<script> alert('Registration Failed'); </script>";
    }
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.addEventListener('click', function (event) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    if (!menu.parentElement.contains(event.target)) {
                        menu.classList.add('hidden');
                    }
                });
            });

            document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
                toggle.addEventListener('click', function (event) {
                    event.stopPropagation();
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
        <div class="ml-64 p-6 w-full  mt-16">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-4"><br></h1>
                <div class="bg-gray-100 p-4 rounded-lg mb-6">
                    <h2 class="text-xl font-semibold mb-4">Create Users</h2>
                    <form class="grid grid-cols-1 md:grid-cols-2 gap-4" method="post"
                        onsubmit="return validateForm(event)">
                        <div>
                            <label class="block text-gray-700">First Name:</label>
                            <input type="text" name="firstName" id="firstName"
                                class="w-full p-2 border border-gray-300 rounded" required>
                        </div>
                        <div>
                            <label class="block text-gray-700">Last Name:</label>
                            <input type="text" name="lastName" id="lastName"
                                class="w-full p-2 border border-gray-300 rounded" required>
                        </div>
                        <div>
                            <label class="block text-gray-700">User Name:</label>
                            <input type="text" name="userName" id="userName"
                                class="w-full p-2 border border-gray-300 rounded" required>
                        </div>
                        <div>
                            <label class="block text-gray-700">Email:</label>
                            <input type="email" name="email" id="email"
                                class="w-full p-2 border border-gray-300 rounded" required>
                        </div>
                        <div>
                            <label class="block text-gray-700">Password:</label>
                            <input type="password" name="password" id="password"
                                class="w-full p-2 border border-gray-300 rounded" required>
                        </div>
                        <div>
                            <label class="block text-gray-700">Confirm Password:</label>
                            <input type="password" name="repeatPassword" id="repeatPassword"
                                class="w-full p-2 border border-gray-300 rounded" required>
                        </div>
                        <div>
                            <label class="block text-gray-700">Phone #:</label>
                            <input type="text" name="phone" id="phone" class="w-full p-2 border border-gray-300 rounded"
                                required>
                        </div>
                        <div class="md:col-span-2 flex justify-end space-x-4">
                            <input type="submit" class="bg-[#008080] text-white px-4 py-2 rounded" value="Create">
                            <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function validateForm(event) {
            const firstName = document.getElementById('firstName').value.trim();
            const lastName = document.getElementById('lastName').value.trim();
            const userName = document.getElementById('userName').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const repeatPassword = document.getElementById('repeatPassword').value;
            const phone = document.getElementById('phone').value.trim();

            if (!firstName || !lastName || !userName || !email || !password || !repeatPassword || !phone) {
                alert('All fields are required.');
                event.preventDefault();
                return false;
            }

            if (password !== repeatPassword) {
                alert('Passwords do not match.');
                event.preventDefault();
                return false;
            }

            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                alert('Please enter a valid email address.');
                event.preventDefault();
                return false;
            }

            const phonePattern = /^\d{10}$/;
            if (!phonePattern.test(phone)) {
                alert('Please enter a valid 10-digit phone number.');
                event.preventDefault();
                return false;
            }

            // All good: let the form submit
            return true;
        }

    </script>
</body>

</html>