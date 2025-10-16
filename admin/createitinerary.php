<?php
include __DIR__ . '/auth-check.php';
require "frontend/connection.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tripid = intval($_POST['tripid']);
    $itinerary_title = trim($_POST['itinerary_title']);

    $titles = [];
    $descs = [];
    for ($i = 1; $i <= 6; $i++) {
        $titles[$i] = trim($_POST["title_$i"] ?? '');
        $descs[$i] = trim($_POST["desc_$i"] ?? '');
    }

    $stmt = $conn->prepare("
        INSERT INTO itinerary (
            tripid, itinerary_title,
            title1, title2, title3, title4, title5, title6,
            desc1, desc2, desc3, desc4, desc5, desc6
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
        "isssssssssssss",
        $tripid,
        $itinerary_title,
        $titles[1], $titles[2], $titles[3], $titles[4], $titles[5], $titles[6],
        $descs[1], $descs[2], $descs[3], $descs[4], $descs[5], $descs[6]
    );

    if ($stmt->execute()) {
        $_SESSION['message'] = "Success: Itinerary added successfully!";
    } else {
        $_SESSION['message'] = "Error: Database insert failed.";
    }

    header("Location: createitinerary.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Create Itinerary - ThankYouNepalTrip</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="frontend/sidebar.css" />
</head>

<body class="bg-gray-50 font-sans leading-normal tracking-normal" x-data="{ sidebarOpen: false }">

  <div class="overlay" :class="{ 'open': sidebarOpen }" @click="sidebarOpen = false"></div>

  <?php include("frontend/header.php"); ?>
  <?php include("frontend/sidebar.php"); ?>

  <main class="main-content pt-16 min-h-screen transition-all duration-300">
    <div class="p-6">
      <div class="bg-white rounded-xl shadow-md p-6">
        <div class="mb-8">
          <div class="gradient-bg rounded-2xl p-6 text-white flex justify-between items-center">
            <div>
              <h1 class="text-3xl font-bold"><i class="fas fa-route mr-3"></i>Add Trip Itinerary</h1>
              <p class="text-green-100">Define daily activities and trip highlights</p>
            </div>
            <a href="allitinerary.php" class="bg-white bg-opacity-20 px-4 py-2 rounded-lg hover:bg-opacity-30">
              <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
          </div>
        </div>

        <?php if (isset($_SESSION['message'])): ?>
          <div class="<?php echo (strpos($_SESSION['message'], 'Error') !== false) ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'; ?> border rounded-lg px-4 py-3 mb-6">
            <?= htmlspecialchars($_SESSION['message']); ?>
          </div>
          <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <form method="post" class="glass-effect rounded-2xl shadow-xl p-8 space-y-8">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-gray-700 mb-2 font-medium">Select Trip *</label>
              <select name="tripid" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                <option value="">-- Choose Trip --</option>
                <?php
                $res = $conn->query("SELECT tripid, title FROM trips");
                while ($row = $res->fetch_assoc()) {
                    echo "<option value='{$row['tripid']}'>" . htmlspecialchars($row['title']) . "</option>";
                }
                ?>
              </select>
            </div>

            <div>
              <label class="block text-gray-700 mb-2 font-medium">Itinerary Title *</label>
              <input type="text" name="itinerary_title" placeholder="e.g. Everest Base Camp Trek Itinerary"
                     class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php for ($i = 1; $i <= 6; $i++): ?>
              <div class="border rounded-xl p-5 bg-gray-50 hover:bg-gray-100 transition">
                <h3 class="text-lg font-semibold mb-3 text-blue-700">
                  <i class="fas fa-calendar-day mr-2"></i>Day <?= $i ?>
                </h3>
                <input type="text" name="title_<?= $i ?>" placeholder="Title for day <?= $i ?>"
                      class="w-full mb-3 border px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <textarea name="desc_<?= $i ?>" rows="3" placeholder="Description for day <?= $i ?>"
                          class="w-full border px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
              </div>
            <?php endfor; ?>
          </div>


          <div class="flex justify-end space-x-4">
            <a href="allitinerary" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
              Cancel
            </a>
            <button type="submit" class="gradient-bg text-white px-6 py-2 rounded-lg hover:opacity-90 transition-colors">
              <i class="fas fa-save mr-2"></i>Save Itinerary
            </button>
          </div>
        </form>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  <script>
    document.addEventListener('alpine:init', () => {
      Alpine.data('main', () => ({
        sidebarOpen: window.innerWidth >= 1024,
        init() {
          if (window.innerWidth < 1024) this.sidebarOpen = false;
          window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) this.sidebarOpen = true;
          });
        }
      }));
    });
  </script>
</body>
</html>
