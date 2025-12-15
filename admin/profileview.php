<?php
include __DIR__ . '/auth-check.php';
require 'frontend/connection.php';
session_start();

$stmt = $conn->prepare("SELECT name, email, role, profile_image FROM admins WHERE id = ?");
$stmt->bind_param("i", $_SESSION['admin_id']);
$stmt->execute();
$admin = $stmt->get_result()->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Profile - ThankYouNepalTrip</title>

  <!-- Tailwind CSS & Icons -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="frontend/sidebar.css" />
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
  <style>
    .gradient-bg {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .glass-effect {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
    }
  </style>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">
  <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">
    <!-- Header and Sidebar -->
    <?php include("frontend/header.php"); ?>
    <?php include("frontend/sidebar.php"); ?>

    <main class="main-content pt-16 min-h-screen transition-all duration-300">
      <div class="p-6">

        <!-- Page Header -->
        <div class="gradient-bg rounded-2xl p-6 text-white flex justify-between items-center">
          <div>
            <h1 class="text-3xl font-bold mb-2">
              <i class="fas fa-user mr-3"></i>Admin Profile
            </h1>
            <p class="text-blue-100">View and manage your admin account</p>
          </div>
          <a href="index.php" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition">
            <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
          </a>
        </div>

        <!-- Alert Messages -->
        <?php if (isset($_SESSION['message'])): ?>
          <div class="mt-6">
            <div class="<?php echo (strpos($_SESSION['message'], 'Error') !== false) ? 'bg-red-100 text-red-700 border-red-400' : 'bg-green-100 text-green-700 border-green-400'; ?> border rounded-lg px-4 py-3 shadow-sm">
              <?php echo htmlspecialchars($_SESSION['message']); ?>
            </div>
          </div>
          <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <!-- Profile Card -->
        <div class="mt-8 bg-white rounded-2xl shadow-xl p-8 text-center">
          <img src="<?php echo htmlspecialchars($admin['profile_image']); ?>" 
               alt="Profile Image" 
               class="w-32 h-32 rounded-full mx-auto mb-4 object-cover shadow-md">
          <h2 class="text-2xl font-semibold text-gray-800"><?php echo htmlspecialchars($admin['name']); ?></h2>
          <p class="text-gray-500"><?php echo htmlspecialchars($admin['role']); ?></p>
          <p class="text-gray-600 mt-1"><i class="fas fa-envelope mr-2 text-blue-500"></i><?php echo htmlspecialchars($admin['email']); ?></p>

          <div class="flex justify-center mt-6 space-x-4">
            <a href="changepassword" 
               class="bg-red-500 hover:bg-red-600 text-white px-5 py-2 rounded-lg transition">
              <i class="fas fa-key mr-2"></i>Change Password
            </a>
          </div>
        </div>

     

      </div>
    </main>
  </div>
</body>
</html>
