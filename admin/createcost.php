<?php
include __DIR__ . '/auth-check.php';
require "frontend/connection.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $tripid = $_POST['tripid'];
    $cost_title = trim($_POST['cost_title']);
    $cost_includes = trim($_POST['cost_includes']);
    $cost_excludes = trim($_POST['cost_excludes']);

    if ($tripid && $cost_title && $cost_includes && $cost_excludes) {
        $stmt = $conn->prepare("INSERT INTO trip_costs (tripid, cost_title, cost_includes, cost_excludes) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $tripid, $cost_title, $cost_includes, $cost_excludes);
        $stmt->execute();
        header("Location: allcost");
        exit;
    }
}

$trips = $conn->query("SELECT tripid, title FROM trips ORDER BY title ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create Cost - ThankYouNepalTrip</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="frontend/sidebar.css">
</head>

<body class="bg-gray-50">
<div class="flex h-screen">
  <?php include("frontend/header.php"); ?>
  <?php include("frontend/sidebar.php"); ?>

  <main class="main-content pt-16 min-h-screen p-6 w-full mt-16" x-data="{ saving: false, showSuccess: false }">

    <!-- Header -->
    <div class="gradient-bg rounded-2xl p-6 text-white flex justify-between items-center mb-8 shadow-md">
      <div>
        <h1 class="text-3xl font-bold"><i class="fas fa-money-bill-wave mr-2"></i>Add Cost</h1>
        <p class="text-green-100">Define cost details for a specific trip</p>
      </div>
      <a href="allcost" class="bg-white bg-opacity-20 px-4 py-2 rounded-lg hover:bg-opacity-30 transition">
        <i class="fas fa-list mr-2"></i>View All
      </a>
    </div>

    <!-- Form -->
    <form method="POST" 
          class="bg-white p-8 rounded-2xl shadow-xl max-w-2xl mx-auto space-y-6"
          x-on:submit="saving = true; setTimeout(() => showSuccess = true, 400)">

      <label class="block">
        <span class="text-gray-700 font-medium">Trip</span>
        <select name="tripid" required 
                class="w-full border-gray-300 rounded-lg mt-1 p-2 focus:ring-2 focus:ring-blue-500">
          <option value="">Select Trip</option>
          <?php while ($trip = $trips->fetch_assoc()): ?>
            <option value="<?= $trip['tripid'] ?>"><?= htmlspecialchars($trip['title']) ?></option>
          <?php endwhile; ?>
        </select>
      </label>

      <label class="block">
        <span class="text-gray-700 font-medium">Cost Title</span>
        <input type="text" name="cost_title" required
               class="w-full border-gray-300 rounded-lg mt-1 p-2 focus:ring-2 focus:ring-blue-500">
      </label>

      <label class="block">
        <span class="text-gray-700 font-medium">Cost Includes</span>
        <textarea name="cost_includes" rows="4" required
                  class="w-full border-gray-300 rounded-lg mt-1 p-2 focus:ring-2 focus:ring-blue-500"></textarea>
      </label>

      <label class="block">
        <span class="text-gray-700 font-medium">Cost Excludes</span>
        <textarea name="cost_excludes" rows="4" required
                  class="w-full border-gray-300 rounded-lg mt-1 p-2 focus:ring-2 focus:ring-blue-500"></textarea>
      </label>

      <div class="flex items-center space-x-4">
        <button type="submit" 
                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition flex items-center"
                :disabled="saving">
          <i class="fas fa-save mr-2"></i>
          <span x-text="saving ? 'Saving...' : 'Save Cost'"></span>
        </button>

        <!-- Success Message -->
        <div x-show="showSuccess" x-transition class="text-green-600 text-sm font-semibold">
          Cost entry saved successfully!
        </div>
      </div>
    </form>

  </main>
</div>

<!-- Optional smooth scroll + fade effect -->
<script>
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('textarea').forEach(el => {
    el.addEventListener('input', e => {
      e.target.style.height = 'auto';
      e.target.style.height = (e.target.scrollHeight) + 'px';
    });
  });
});
</script>

</body>
</html>
