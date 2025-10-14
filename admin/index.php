<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: frontend/admin-login.php");
    exit;
}

include 'frontend/connection.php'; // must define $conn

// Tables to count
$tables = [
    'users',
    'trip_bookings',
    'trips',
    'activities',
    'admins',
    'destinations',
    'highlights',
    'itinerary',
    'teams',
    'triptypes',
    'trip_costs',
    'trip_faqs',
    'trip_images',
    'trip_overviews',
    'departure'
];

// Fetch counts
$counts = [];
foreach ($tables as $table) {
    $res = $conn->query("SELECT COUNT(*) AS total FROM $table");
    $counts[$table] = ($res && $row = $res->fetch_assoc()) ? $row['total'] : 0;
}
$total_records = array_sum($counts);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Dashboard - ThankYouNepalTrip</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" />
  <link href="frontend/sidebar.css" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans leading-normal tracking-normal" x-data="{ sidebarOpen: false }">
  <?php include 'frontend/header.php'; ?>
  <?php include 'frontend/sidebar.php'; ?>

  <main class="main-content pt-16 min-h-screen transition-all duration-300">
    <div class="p-6">
      <div class="bg-white rounded-xl shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800">Welcome to Admin Dashboard</h1>
        <p class="text-gray-600 mt-2">Live statistics from your database</p>

        <div class="mt-8">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-blue-50 rounded-lg p-6 border border-blue-100">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-blue-600">Total Users</p>
                  <p class="text-2xl font-bold mt-1 text-blue-900"><?= $counts['users'] ?></p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                  <i class="fas fa-users text-blue-600"></i>
                </div>
              </div>
            </div>

            <div class="bg-purple-50 rounded-lg p-6 border border-purple-100">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-purple-600">Total Bookings</p>
                  <p class="text-2xl font-bold mt-1 text-purple-900"><?= $counts['trip_bookings'] ?></p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                  <i class="fas fa-bookmark text-purple-600"></i>
                </div>
              </div>
            </div>

            <div class="bg-green-50 rounded-lg p-6 border border-green-100">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-green-600">Active Trips</p>
                  <p class="text-2xl font-bold mt-1 text-green-900"><?= $counts['trips'] ?></p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                  <i class="fas fa-suitcase-rolling text-green-600"></i>
                </div>
              </div>
            </div>

            <div class="bg-orange-50 rounded-lg p-6 border border-orange-100">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-orange-600">Total Records (All Tables)</p>
                  <p class="text-2xl font-bold mt-1 text-orange-900"><?= $total_records ?></p>
                </div>
                <div class="bg-orange-100 p-3 rounded-full">
                  <i class="fas fa-database text-orange-600"></i>
                </div>
              </div>
            </div>
          </div>

          <div class="mt-8 bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 border-b border-gray-200">
              <h2 class="text-lg font-semibold text-gray-800">Table Summary</h2>
            </div>
            <div class="p-6">
              <table class="min-w-full text-sm text-left border border-gray-200">
                <thead class="bg-gray-100">
                  <tr>
                    <th class="px-4 py-2 border">Table Name</th>
                    <th class="px-4 py-2 border text-right">Rows</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($counts as $table => $count): ?>
                    <tr class="hover:bg-gray-50">
                      <td class="px-4 py-2 border font-medium"><?= htmlspecialchars($table) ?></td>
                      <td class="px-4 py-2 border text-right"><?= $count ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>

        </div>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
