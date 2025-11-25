<?php
include __DIR__ . '/auth-check.php';
require "frontend/connection.php";
session_start();

$id = intval($_GET['id'] ?? 0);

// Fetch cost entry
$stmt = $conn->prepare("SELECT * FROM trip_costs WHERE costid=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$cost = $stmt->get_result()->fetch_assoc();

if (!$cost) {
    die("Invalid cost ID.");
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tripid = $_POST['tripid'];
    $cost_title = trim($_POST['cost_title']);
    $includes = [];
    $excludes = [];

    for ($i = 1; $i <= 6; $i++) {
        $includes[$i] = trim($_POST["include_title$i"]);
        $excludes[$i] = trim($_POST["exclude_title$i"]);
    }

    $stmt = $conn->prepare("
        UPDATE trip_costs SET
            tripid=?, cost_title=?,
            include_title1=?, include_title2=?, include_title3=?, include_title4=?, include_title5=?, include_title6=?,
            exclude_title1=?, exclude_title2=?, exclude_title3=?, exclude_title4=?, exclude_title5=?, exclude_title6=?
        WHERE costid=?
    ");
    $stmt->bind_param(
        "isssssssssssssi",
        $tripid, $cost_title,
        $includes[1], $includes[2], $includes[3], $includes[4], $includes[5], $includes[6],
        $excludes[1], $excludes[2], $excludes[3], $excludes[4], $excludes[5], $excludes[6],
        $id
    );
    $stmt->execute();

    header("Location: allcost");
    exit;
}

$trips = $conn->query("SELECT tripid, title FROM trips ORDER BY title ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Cost | ThankYouNepalTrip</title>
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
        <h1 class="text-3xl font-bold"><i class="fas fa-pen-to-square mr-2"></i>Edit Trip Cost</h1>
        <p class="text-green-100">Modify includes and excludes for this trip</p>
      </div>
      <a href="allcost" class="bg-white bg-opacity-20 px-4 py-2 rounded-lg hover:bg-opacity-30 transition">
        <i class="fas fa-arrow-left mr-2"></i>Back
      </a>
    </div>

    <!-- Edit Form -->
    <form method="POST" 
          class="bg-white p-8 rounded-2xl shadow-xl max-w-3xl mx-auto space-y-6"
          x-on:submit="saving = true; setTimeout(() => showSuccess = true, 400)">

      <label class="block">
        <span class="text-gray-700 font-medium">Trip</span>
        <select name="tripid" required 
                class="w-full border-gray-300 rounded-lg mt-1 p-2 focus:ring-2 focus:ring-blue-500">
          <option value="">Select Trip</option>
          <?php while ($trip = $trips->fetch_assoc()): ?>
            <option value="<?= $trip['tripid'] ?>" <?= $trip['tripid'] == $cost['tripid'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($trip['title']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </label>

      <label class="block">
        <span class="text-gray-700 font-medium">Cost Title</span>
        <input type="text" name="cost_title" required
               value="<?= htmlspecialchars($cost['cost_title']) ?>"
               class="w-full border-gray-300 rounded-lg mt-1 p-2 focus:ring-2 focus:ring-blue-500">
      </label>

      <!-- Includes / Excludes -->
      <div class="grid grid-cols-2 gap-6">
        <div>
          <h2 class="text-lg font-semibold mb-2 text-gray-800">Includes</h2>
          <?php for ($i = 1; $i <= 6; $i++): ?>
            <input type="text" name="include_title<?= $i ?>" 
                   placeholder="Include Title <?= $i ?>"
                   value="<?= htmlspecialchars($cost["include_title$i"]) ?>"
                   class="w-full mb-2 border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-green-500">
          <?php endfor; ?>
        </div>

        <div>
          <h2 class="text-lg font-semibold mb-2 text-gray-800">Excludes</h2>
          <?php for ($i = 1; $i <= 6; $i++): ?>
            <input type="text" name="exclude_title<?= $i ?>" 
                   placeholder="Exclude Title <?= $i ?>"
                   value="<?= htmlspecialchars($cost["exclude_title$i"]) ?>"
                   class="w-full mb-2 border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-red-500">
          <?php endfor; ?>
        </div>
      </div>

      <div class="flex items-center space-x-4">
        <button type="submit" 
                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition flex items-center"
                :disabled="saving">
          <i class="fas fa-save mr-2"></i>
          <span x-text="saving ? 'Updating...' : 'Update Cost'"></span>
        </button>

        <div x-show="showSuccess" x-transition class="text-green-600 text-sm font-semibold">
          Cost updated successfully!
        </div>
      </div>
    </form>
  </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('input[type="text"]').forEach(el => {
    el.addEventListener('input', e => {
      e.target.style.height = 'auto';
      e.target.style.height = (e.target.scrollHeight) + 'px';
    });
  });
});
</script>
</body>
</html>
