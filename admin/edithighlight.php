<?php
require "frontend/connection.php";
session_start();

// Get ID
$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: allhighlight.php");
    exit();
}

// Fetch highlight record
$stmt = $conn->prepare("SELECT * FROM highlights WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$highlight = $result->fetch_assoc();
if (!$highlight) {
    $_SESSION['message'] = "Error: Highlight not found.";
    header("Location: allhighlight.php");
    exit();
}

// Fetch trips for dropdown
$trips = $conn->query("SELECT tripid, title FROM trips");

// Update logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tripid = intval($_POST['tripid']);
    $titles = [];
    for ($i = 1; $i <= 6; $i++) {
        $titles["title$i"] = trim($_POST["title_$i"] ?? '');
    }

    $stmt = $conn->prepare("
        UPDATE highlights 
        SET tripid=?, title1=?, title2=?, title3=?, title4=?, title5=?, title6=? 
        WHERE id=?
    ");
    $stmt->bind_param(
        "issssssi",
        $tripid,
        $titles['title1'],
        $titles['title2'],
        $titles['title3'],
        $titles['title4'],
        $titles['title5'],
        $titles['title6'],
        $id
    );

    if ($stmt->execute()) {
        $_SESSION['message'] = "Success: Highlight updated successfully!";
    } else {
        $_SESSION['message'] = "Error: Failed to update highlight.";
    }

    header("Location: allhighlight.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Highlight - ThankYouNepalTrip</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="frontend/sidebar.css" />
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
          <h1 class="text-3xl font-bold mb-2"><i class="fas fa-edit mr-3"></i>Edit Trip Highlight</h1>
          <p class="text-blue-100">Modify up to six key highlights for this trip</p>
        </div>
        <a href="allhighlight.php" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition">
          <i class="fas fa-arrow-left mr-2"></i>Back to List
        </a>
      </div>

      <!-- Form -->
      <div class="mt-8 bg-white rounded-2xl shadow-xl p-8">
        <form method="post" class="space-y-8">
          <div>
            <label class="block text-sm font-semibold mb-2">Select Trip</label>
            <select name="tripid" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
              <option value="">-- Choose Trip --</option>
              <?php while ($trip = $trips->fetch_assoc()): ?>
                <option value="<?= $trip['tripid'] ?>" <?= $trip['tripid'] == $highlight['tripid'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($trip['title']) ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>

          <?php for ($i = 1; $i <= 6; $i++): 
            $field = "title$i";
          ?>
            <div class="border rounded-xl p-5 bg-gray-50">
              <h3 class="text-lg font-semibold mb-3 text-blue-700">
                <i class="fas fa-check-circle mr-2"></i>Highlight <?= $i ?>
              </h3>
              <input type="text" name="title_<?= $i ?>" 
                     value="<?= htmlspecialchars($highlight[$field] ?? '') ?>" 
                     placeholder="Enter highlight title <?= $i ?>" 
                     class="w-full border px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
          <?php endfor; ?>

          <div class="flex justify-end space-x-4">
            <a href="allhighlight.php" class="px-6 py-3 border border-gray-300 rounded-lg text-sm text-gray-700 bg-white hover:bg-gray-50">
              <i class="fas fa-times mr-2"></i>Cancel
            </a>
            <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
              <i class="fas fa-save mr-2"></i>Update Highlights
            </button>
          </div>
        </form>
      </div>
    </div>
  </main>
</div>
</body>
</html>
