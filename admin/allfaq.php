<?php
include __DIR__ . '/auth-check.php';
require "frontend/connection.php";
session_start();

$query = "SELECT f.*, t.title AS trip_title FROM trip_faqs f 
          JOIN trips t ON f.tripid = t.tripid 
          ORDER BY f.faqid DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>FAQs Management- ThankYouNepalTrip</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="frontend/sidebar.css">
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="bg-gray-50">
<div class="flex h-screen">
  <?php include("frontend/header.php"); ?>
  <?php include("frontend/sidebar.php"); ?>

  <main class="main-content pt-16 min-h-screen p-6 w-full mt-16"
        x-data="{ showConfirm: false, deleteId: null, search: '' }">

    <div class="gradient-bg rounded-2xl p-6 text-white flex justify-between items-center mb-8 shadow-md">
      <div>
        <h1 class="text-3xl font-bold"><i class="fas fa-question-circle mr-2"></i>All FAQs</h1>
        <p class="text-green-100">Manage all frequently asked questions</p>
      </div>
      <a href="createfaq" class="bg-white bg-opacity-20 px-4 py-2 rounded-lg hover:bg-opacity-30 transition">
        <i class="fas fa-plus mr-2"></i>Add New
      </a>
    </div>

    <div class="flex justify-end mb-4">
      <input type="text" x-model="search"
             placeholder="Search trip or question..."
             class="border border-gray-300 rounded-lg px-4 py-2 w-72 focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <div class="bg-white rounded-2xl shadow-xl p-6 overflow-x-auto">
      <?php if ($result && $result->num_rows > 0): ?>
      <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden">
        <thead class="bg-gray-100 text-gray-700 uppercase text-sm">
          <tr>
            <th class="px-4 py-3 text-left border-b">#</th>
            <th class="px-4 py-3 text-left border-b">Trip</th>
            <th class="px-4 py-3 text-left border-b">Question</th>
            <th class="px-4 py-3 text-left border-b">Answer</th>
            <th class="px-4 py-3 text-center border-b">Actions</th>
          </tr>
        </thead>
        <tbody class="text-gray-700">
          <?php $i = 1; while ($row = $result->fetch_assoc()): ?>
          <tr class="hover:bg-gray-50 transition"
              x-show="search === '' ||
                      '<?= strtolower($row['trip_title']) ?>'.includes(search.toLowerCase()) ||
                      '<?= strtolower($row['question']) ?>'.includes(search.toLowerCase())">
            <td class="px-4 py-3 border-b"><?= $i++ ?></td>
            <td class="px-4 py-3 border-b font-semibold text-green-700"><?= htmlspecialchars($row['trip_title']) ?></td>
            <td class="px-4 py-3 border-b"><?= htmlspecialchars($row['question']) ?></td>
            <td class="px-4 py-3 border-b text-gray-600"><?= htmlspecialchars($row['answer']) ?></td>
            <td class="px-4 py-3 border-b text-center space-x-4">
              <a href="editfaq?id=<?= $row['faqid'] ?>" class="text-blue-600 hover:text-blue-800">
                <i class="fas fa-edit"></i>
              </a>
              <button @click="showConfirm = true; deleteId = <?= $row['faqid'] ?>;"
                      class="text-red-600 hover:text-red-800">
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
      <?php else: ?>
        <p class="text-center text-gray-600 py-10">No FAQs found.</p>
      <?php endif; ?>
    </div>

    <!-- Delete Modal -->
    <div x-show="showConfirm"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-2xl p-8 w-96 text-center shadow-lg">
        <i class="fas fa-exclamation-triangle text-yellow-500 text-3xl mb-3"></i>
        <h3 class="text-lg font-semibold mb-4">Confirm Delete</h3>
        <p class="text-gray-600 mb-6">Are you sure you want to delete this FAQ?</p>
        <div class="flex justify-center space-x-4">
          <form method="post" action="deletefaq">
            <input type="hidden" name="id" :value="deleteId">
            <button type="submit" class="bg-red-600 text-white px-5 py-2 rounded-lg hover:bg-red-700 transition">
              Delete
            </button>
          </form>
          <button @click="showConfirm = false"
                  class="bg-gray-300 px-5 py-2 rounded-lg hover:bg-gray-400 transition">
            Cancel
          </button>
        </div>
      </div>
    </div>

  </main>
</div>
</body>
</html>
