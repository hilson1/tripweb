<?php
include __DIR__ . '/auth-check.php';
require "frontend/connection.php";
session_start();

// Fetch highlights with trip title
$query = "SELECT h.*, t.title AS trip_title 
          FROM highlights h
          JOIN trips t ON h.tripid = t.tripid
          ORDER BY h.id DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Highlights Management - ThankYouNepalTrip</title>
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
<div class="flex h-screen">
  <?php include("frontend/header.php"); ?>
  <?php include("frontend/sidebar.php"); ?>

  <main class="main-content pt-16 min-h-screen transition-all duration-300">
    <div class="p-6" x-data="{ showConfirm: false, deleteId: null }">
      
      <!-- Header -->
      <div class="gradient-bg rounded-2xl p-6 text-white flex justify-between items-center">
        <div>
          <h1 class="text-3xl font-bold mb-2"><i class="fas fa-list mr-3"></i>All Trip Highlights</h1>
          <p class="text-blue-100">View, edit, or delete trip highlights</p>
        </div>
        <a href="createhighlight" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition">
          <i class="fas fa-plus mr-2"></i>Add Highlights
        </a>
      </div>

      <!-- Table + Search -->
      <div class="mt-8 bg-white rounded-2xl shadow-xl p-6 overflow-x-auto" 
           x-data="{ search: '' }">

        <!-- Search Bar -->
        <div class="flex justify-end mb-4">
          <input 
            type="text" 
            placeholder="Search trip or highlight..." 
            x-model="search"
            class="border border-gray-300 rounded-lg px-4 py-2 w-64 focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
        </div>

        <?php if ($result && $result->num_rows > 0): ?>
        <table class="min-w-full border-collapse">
          <thead>
            <tr class="bg-gray-100 text-gray-700 text-left">
              <th class="px-4 py-3 border-b">#</th>
              <th class="px-4 py-3 border-b">Trip</th>
              <th class="px-4 py-3 border-b">Highlights</th>
              <th class="px-4 py-3 border-b text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1; while ($row = $result->fetch_assoc()): 
              $tripTitle = htmlspecialchars($row['trip_title']);
              ob_start();
              echo "<ul class='list-disc list-inside text-gray-800 space-y-1'>";
              for ($j = 1; $j <= 6; $j++) {
                $field = "title$j";
                if (!empty($row[$field])) echo "<li>" . htmlspecialchars($row[$field]) . "</li>";
              }
              echo "</ul>";
              $highlightList = ob_get_clean();
            ?>
            <tr class="hover:bg-gray-50" 
                x-show="
                  search === '' || 
                  '<?= strtolower($tripTitle) ?>'.includes(search.toLowerCase()) || 
                  '<?= strtolower(strip_tags($highlightList)) ?>'.includes(search.toLowerCase())
                ">
              <td class="px-4 py-3 border-b"><?= $i++ ?></td>
              <td class="px-4 py-3 border-b font-semibold text-blue-700"><?= $tripTitle ?></td>
              <td class="px-4 py-3 border-b"><?= $highlightList ?></td>
              <td class="px-4 py-3 border-b text-center space-x-3">
                <a href="edithighlight?id=<?= $row['id'] ?>" 
                   class="text-blue-600 hover:text-blue-800 transition">
                  <i class="fas fa-edit"></i>
                </a>
                <button 
                  @click="showConfirm = true; deleteId = <?= $row['id'] ?>;" 
                  class="text-red-600 hover:text-red-800 transition"
                >
                  <i class="fas fa-trash-alt"></i>
                </button>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
        <?php else: ?>
          <p class="text-center text-gray-600 py-10">No highlights found.</p>
        <?php endif; ?>
      </div>

      <!-- Delete Confirmation Modal -->
      <div 
        x-show="showConfirm"
        class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50"
      >
        <div class="bg-white rounded-2xl p-6 shadow-lg max-w-sm w-full text-center">
          <h3 class="text-xl font-semibold mb-4 text-gray-800">
            <i class="fas fa-exclamation-triangle text-yellow-500 mr-2"></i>Confirm Delete
          </h3>
          <p class="text-gray-600 mb-6">Are you sure you want to delete this highlight record?</p>
          <div class="flex justify-center space-x-4">
            <form method="post" action="delete_highlight">
              <input type="hidden" name="id" :value="deleteId">
              <button type="submit" class="bg-red-600 text-white px-5 py-2 rounded-lg hover:bg-red-700">
                Yes, Delete
              </button>
            </form>
            <button @click="showConfirm = false" class="bg-gray-300 px-5 py-2 rounded-lg hover:bg-gray-400">
              Cancel
            </button>
          </div>
        </div>
      </div>

    </div>
  </main>
</div>
</body>
</html>
