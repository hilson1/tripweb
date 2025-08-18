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
      <?php 
        include("frontend/asidebar.php");
        ?>

        <!-- Main Content -->
        <div class="w-3/4 p-6 ml-64"><br><br>
            <!-- Profile Section -->
            <div class="profile-card mb-6">
                <img src="https://via.placeholder.com/150" alt="Admin Avatar">
                <h2 class="text-2xl font-semibold mt-4">John Doe</h2>
                <p class="text-gray-500">Admin</p>
                <div class="flex justify-center mt-4">
                    <button class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600 mx-2">Edit Profile</button>
                    <button class="bg-red-500 text-white p-2 rounded hover:bg-red-600 mx-2">Change Password</button>
                </div>
            </div>

            <!-- Stats Section -->
            <div class="grid grid-cols-3 gap-6">
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
