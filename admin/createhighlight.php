<?php
include __DIR__ . '/auth-check.php';
require "frontend/connection.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tripid = intval($_POST['tripid']);

    // Collect all title fields
    $titles = [];
    for ($i = 1; $i <= 6; $i++) {
        $titles[$i] = trim($_POST["title_$i"] ?? '');
    }

    // Prepare and execute insert
    $stmt = $conn->prepare("
        INSERT INTO highlights (tripid, title1, title2, title3, title4, title5, title6)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
        "issssss",
        $tripid,
        $titles[1],
        $titles[2],
        $titles[3],
        $titles[4],
        $titles[5],
        $titles[6]
    );

    if ($stmt->execute()) {
        $_SESSION['message'] = "Success: Highlights added successfully!";
    } else {
        $_SESSION['message'] = "Error: Database insert failed.";
    }

    header("Location: createhighlight.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Create Highlights - ThankYouNepalTrip</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="frontend/sidebar.css" />
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
<div class="flex h-screen">
  <?php include("frontend/header.php"); ?>
  <?php include("frontend/sidebar.php"); ?>

  <main class="main-content pt-16 min-h-screen transition-all duration-300">
    <div class="p-6">

      <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl p-6 text-white flex justify-between items-center">
        <div>
          <h1 class="text-3xl font-bold mb-2"><i class="fas fa-star mr-3"></i>Add Trip Highlights</h1>
          <p class="text-blue-100">Add up to six key highlights for a selected trip</p>
        </div>
        <a href="allhighlight.php" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition">
          <i class="fas fa-arrow-left mr-2"></i>Back to List
        </a>
      </div>

      <?php if (isset($_SESSION['message'])): ?>
        <div class="mt-6">
          <div class="<?php echo (strpos($_SESSION['message'], 'Error') !== false) ? 'bg-red-100 text-red-700 border-red-400' : 'bg-green-100 text-green-700 border-green-400'; ?> border rounded-lg px-4 py-3 shadow-sm">
            <?php echo htmlspecialchars($_SESSION['message']); ?>
          </div>
        </div>
        <?php unset($_SESSION['message']); ?>
      <?php endif; ?>

      <div class="mt-8 bg-white rounded-2xl shadow-xl p-8">
        <form method="post" class="space-y-8">
          <div>
            <label class="block text-sm font-semibold mb-2">Select Trip</label>
            <select name="tripid" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
              <option value="">-- Choose Trip --</option>
              <?php
              $res = $conn->query("SELECT tripid, title FROM trips");
              while ($row = $res->fetch_assoc()) {
                  echo "<option value='{$row['tripid']}'>" . htmlspecialchars($row['title']) . "</option>";
              }
              ?>
            </select>
          </div>

          <?php for ($i = 1; $i <= 6; $i++): ?>
            <div class="border rounded-xl p-5 bg-gray-50">
              <h3 class="text-lg font-semibold mb-3 text-blue-700">
                <i class="fas fa-check-circle mr-2"></i>Highlight <?= $i ?>
              </h3>
              <input type="text" name="title_<?= $i ?>" placeholder="Enter highlight title <?= $i ?>"
                     class="w-full border px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
          <?php endfor; ?>

          <div class="flex justify-end space-x-4">
            <a href="allhighlight" class="px-6 py-3 border border-gray-300 rounded-lg text-sm text-gray-700 bg-white hover:bg-gray-50">
              <i class="fas fa-times mr-2"></i>Cancel
            </a>
            <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
              <i class="fas fa-save mr-2"></i>Save Highlights
            </button>
          </div>
        </form>
      </div>
    </div>
  </main>
</div>
</body>
</html>
