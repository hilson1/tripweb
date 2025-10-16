<?php
include __DIR__ . '/auth-check.php';
require "frontend/connection.php";
session_start();

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM trip_costs WHERE costid = $id");
    header("Location: allcost");
    exit;
}

$sql = "SELECT c.*, t.title AS trip_name 
        FROM trip_costs c 
        JOIN trips t ON c.tripid = t.tripid 
        ORDER BY c.costid DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Costs Management - ThankYouNepalTrip</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="frontend/sidebar.css">
</head>

<body class="bg-gray-50">
<div class="flex h-screen">
  <?php include("frontend/header.php"); ?>
  <?php include("frontend/sidebar.php"); ?>

  <main class="main-content pt-16 min-h-screen p-6 w-full mt-16">
    <div class="gradient-bg rounded-2xl p-6 text-white flex justify-between items-center mb-8 shadow-md">
      <div>
        <h1 class="text-3xl font-bold"><i class="fas fa-money-bill-wave mr-2"></i>All Trip Costs</h1>
      </div>
      <a href="createcost" class="bg-white bg-opacity-20 px-4 py-2 rounded-lg hover:bg-opacity-30 transition">
        <i class="fas fa-plus mr-2"></i>Add New
      </a>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-xl overflow-x-auto">
      <table class="min-w-full border border-gray-200 rounded-lg text-sm">
        <thead class="bg-gray-100 text-gray-700">
          <tr>
            <th class="p-3 border">#</th>
            <th class="p-3 border">Trip</th>
            <th class="p-3 border">Cost Title</th>
            <th class="p-3 border">Includes</th>
            <th class="p-3 border">Excludes</th>
            <th class="p-3 border">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result->num_rows > 0): 
            $i = 1;
            while ($row = $result->fetch_assoc()): ?>
              <tr class="hover:bg-gray-50">
                <td class="border p-3 text-center"><?= $i++ ?></td>
                <td class="border p-3"><?= htmlspecialchars($row['trip_name']) ?></td>
                <td class="border p-3 font-semibold"><?= htmlspecialchars($row['cost_title']) ?></td>
                <td class="border p-3">
                  <ul class="list-disc list-inside text-green-700">
                    <?php for ($j=1; $j<=6; $j++):
                      if (!empty($row["include_title$j"])) echo "<li>".htmlspecialchars($row["include_title$j"])."</li>";
                    endfor; ?>
                  </ul>
                </td>
                <td class="border p-3">
                  <ul class="list-disc list-inside text-red-700">
                    <?php for ($j=1; $j<=6; $j++):
                      if (!empty($row["exclude_title$j"])) echo "<li>".htmlspecialchars($row["exclude_title$j"])."</li>";
                    endfor; ?>
                  </ul>
                </td>
                <td class="border p-3 text-center">
                  <a href="editcost?id=<?= $row['costid'] ?>" class="text-blue-600 hover:text-blue-800 mr-3"><i class="fas fa-edit"></i></a>
                  <a href="?delete=<?= $row['costid'] ?>" class="text-red-600 hover:text-red-800" onclick="return confirm('Delete this cost?');"><i class="fas fa-trash"></i></a>
                </td>
              </tr>
            <?php endwhile;
          else: ?>
            <tr><td colspan="6" class="text-center text-gray-500 p-4">No trip costs found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>
</body>
</html>
