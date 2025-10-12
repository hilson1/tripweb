<?php
require 'frontend/connection.php';
session_start();

// Ensure admin is logged in (example: use your session variable)
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $currentPassword = trim($_POST['current_password']);
        $newPassword = trim($_POST['new_password']);
        $confirmPassword = trim($_POST['confirm_password']);
        $adminId = $_SESSION['admin_id'];

        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            throw new Exception("Error: All fields are required.");
        }

        if ($newPassword !== $confirmPassword) {
            throw new Exception("Error: New passwords do not match.");
        }

        if (strlen($newPassword) < 6) {
            throw new Exception("Error: Password must be at least 6 characters long.");
        }

        // ✅ Fetch current password hash
        $stmt = $conn->prepare("SELECT password FROM admins WHERE id = ?");
        $stmt->bind_param("i", $adminId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            throw new Exception("Error: Admin account not found.");
        }

        $row = $result->fetch_assoc();
        $hashedPassword = $row['password'];

        // ✅ Verify current password
        if (!password_verify($currentPassword, $hashedPassword)) {
            throw new Exception("Error: Current password is incorrect.");
        }

        // ✅ Hash and update new password
        $newHashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE admins SET password = ? WHERE id = ?");
        $update->bind_param("si", $newHashed, $adminId);

        if (!$update->execute()) {
            throw new Exception("Error: Failed to update password. Please try again.");
        }

        $_SESSION['message'] = "Success: Password changed successfully!";
        header("Location: changepassword.php");
        exit();

    } catch (Exception $e) {
        $_SESSION['message'] = $e->getMessage();
        header("Location: changepassword.php");
        exit();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Change Password - ThankYouNepalTrip</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="frontend/sidebar.css" />
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">
  <div class="flex h-screen">
    <?php include("frontend/header.php"); ?>
    <?php include("frontend/sidebar.php"); ?>

    <main class="main-content pt-16 min-h-screen transition-all duration-300">
      <div class="p-6">

        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl p-6 text-white flex justify-between items-center">
          <div>
            <h1 class="text-3xl font-bold mb-2">
              <i class="fas fa-key mr-3"></i>Change Password
            </h1>
            <p class="text-blue-100">Update your admin account password securely</p>
          </div>
          <a href="profileview.php" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition">
            <i class="fas fa-arrow-left mr-2"></i>Back to Profile
          </a>
        </div>

        <!-- Alert -->
        <?php if (isset($_SESSION['message'])): ?>
          <div class="mt-6">
            <div class="<?php echo (strpos($_SESSION['message'], 'Error') !== false) ? 'bg-red-100 text-red-700 border-red-400' : 'bg-green-100 text-green-700 border-green-400'; ?> border rounded-lg px-4 py-3 shadow-sm">
              <?php echo htmlspecialchars($_SESSION['message']); ?>
            </div>
          </div>
          <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <!-- Form -->
        <div class="mt-8 bg-white rounded-2xl shadow-xl p-8">
          <form method="POST" class="space-y-8">
            <div>
              <label class="block text-sm font-semibold mb-2">Current Password</label>
              <input type="password" name="current_password" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Enter your current password">
            </div>
            <div>
              <label class="block text-sm font-semibold mb-2">New Password</label>
              <input type="password" name="new_password" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Enter your new password">
            </div>
            <div>
              <label class="block text-sm font-semibold mb-2">Confirm New Password</label>
              <input type="password" name="confirm_password" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Re-enter your new password">
            </div>

            <div class="flex justify-end space-x-4">
              <a href="profileview.php" class="px-6 py-3 border border-gray-300 rounded-lg text-sm text-gray-700 bg-white hover:bg-gray-50">
                <i class="fas fa-times mr-2"></i>Cancel
              </a>
              <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-save mr-2"></i>Change Password
              </button>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
