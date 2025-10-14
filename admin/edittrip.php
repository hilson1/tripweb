<?php
require 'frontend/connection.php';

// Fetch dropdown data
$tripTypesResult = $conn->query("SELECT triptype FROM triptypes");
$locationsResult = $conn->query("SELECT distination FROM destinations");
$activitiesResult = $conn->query("SELECT activity FROM activities");

// Validate trip ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Trip ID not provided.");
}
$tripId = (int)$_GET['id'];

// Fetch existing trip
$stmt = $conn->prepare("SELECT * FROM trips WHERE tripid = ?");
$stmt->bind_param("i", $tripId);
$stmt->execute();
$trip = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$trip) {
    die("Trip not found.");
}

// Handle update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $transportation = trim($_POST['Transportation']);
    $accomodation = trim($_POST['Accomodation']);
    $maximumaltitude = trim($_POST['Maximum']);
    $departure = trim($_POST['Departure']);
    $season = trim($_POST['season']);
    $triptype = trim($_POST['triptype']);
    $meals = trim($_POST['meals']);
    $language = trim($_POST['language']);
    $fitnesslevel = trim($_POST['fitnesslevel']);
    $groupsize = trim($_POST['groupsize']);
    $minimumage = intval($_POST['minimumage']);
    $maximumage = intval($_POST['maximumage']);
    $location = trim($_POST['location']);
    $activity = trim($_POST['activity']);
    $duration = trim($_POST['duration']);

    $sql = "UPDATE trips SET 
        title=?, price=?, transportation=?, accomodation=?, maximumaltitude=?, 
        departurefrom=?, bestseason=?, triptype=?, meals=?, language=?, fitnesslevel=?, 
        groupsize=?, minimumage=?, maximumage=?, description=?, location=?, duration=?, activity=? 
        WHERE tripid=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sdssssssssssiissssi",
        $title,
        $price,
        $transportation,
        $accomodation,
        $maximumaltitude,
        $departure,
        $season,
        $triptype,
        $meals,
        $language,
        $fitnesslevel,
        $groupsize,
        $minimumage,
        $maximumage,
        $description,
        $location,
        $duration,
        $activity,
        $tripId
    );

    if ($stmt->execute()) {
        echo "<script>alert('Trip updated successfully!');window.location.href='alltrip.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit Trip</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="frontend/sidebar.css" />
</head>
<body class="bg-gray-50 font-sans">
  <?php include 'frontend/header.php'; ?>
  <?php include("frontend/sidebar.php"); ?>

  <div class="ml-64 mt-16 p-6">
    <div class="bg-white shadow-md rounded-lg p-6">
      <h1 class="text-3xl font-bold text-gray-800 mb-6">Edit Trip</h1>

      <form method="POST" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
            <input type="text" name="title" value="<?= htmlspecialchars($trip['title']) ?>" class="w-full border-gray-300 rounded p-2" required>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
            <input type="number" name="price" value="<?= htmlspecialchars($trip['price']) ?>" class="w-full border-gray-300 rounded p-2" required>
          </div>

          <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" rows="3" class="w-full border-gray-300 rounded p-2" required><?= htmlspecialchars($trip['description']) ?></textarea>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Transportation</label>
            <select name="Transportation" class="w-full border-gray-300 rounded p-2" required>
              <option value="Bus" <?= $trip['transportation'] == 'Bus' ? 'selected' : '' ?>>Bus</option>
              <option value="Car" <?= $trip['transportation'] == 'Car' ? 'selected' : '' ?>>Car</option>
              <option value="Helicopter" <?= $trip['transportation'] == 'Helicopter' ? 'selected' : '' ?>>Helicopter</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Accommodation</label>
            <input type="text" name="Accomodation" value="<?= htmlspecialchars($trip['accomodation']) ?>" class="w-full border-gray-300 rounded p-2" required>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Maximum Altitude</label>
            <input type="text" name="Maximum" value="<?= htmlspecialchars($trip['maximumaltitude']) ?>" class="w-full border-gray-300 rounded p-2" required>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Departure From</label>
            <select name="Departure" class="w-full border-gray-300 rounded p-2" required>
              <option value="Kathmandu" <?= $trip['departurefrom'] == 'Kathmandu' ? 'selected' : '' ?>>Kathmandu</option>
              <option value="Lalitpur" <?= $trip['departurefrom'] == 'Lalitpur' ? 'selected' : '' ?>>Lalitpur</option>
              <option value="Chitwan" <?= $trip['departurefrom'] == 'Chitwan' ? 'selected' : '' ?>>Chitwan</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Best Season</label>
            <select name="season" class="w-full border-gray-300 rounded p-2" required>
              <option value="Winter" <?= $trip['bestseason'] == 'Winter' ? 'selected' : '' ?>>Winter</option>
              <option value="Summer" <?= $trip['bestseason'] == 'Summer' ? 'selected' : '' ?>>Summer</option>
              <option value="Spring" <?= $trip['bestseason'] == 'Spring' ? 'selected' : '' ?>>Spring</option>
              <option value="Autumn" <?= $trip['bestseason'] == 'Autumn' ? 'selected' : '' ?>>Autumn</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Trip Type</label>
            <select name="triptype" class="w-full border-gray-300 rounded p-2" required>
              <?php while ($row = $tripTypesResult->fetch_assoc()): ?>
                <option value="<?= $row['triptype'] ?>" <?= $row['triptype'] == $trip['triptype'] ? 'selected' : '' ?>><?= $row['triptype'] ?></option>
              <?php endwhile; ?>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Meals</label>
            <input type="text" name="meals" value="<?= htmlspecialchars($trip['meals']) ?>" class="w-full border-gray-300 rounded p-2" required>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Language</label>
            <input type="text" name="language" value="<?= htmlspecialchars($trip['language']) ?>" class="w-full border-gray-300 rounded p-2" required>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Fitness Level</label>
            <select name="fitnesslevel" class="w-full border-gray-300 rounded p-2" required>
              <option value="Beginner" <?= $trip['fitnesslevel'] == 'Beginner' ? 'selected' : '' ?>>Beginner</option>
              <option value="Medium" <?= $trip['fitnesslevel'] == 'Medium' ? 'selected' : '' ?>>Medium</option>
              <option value="High" <?= $trip['fitnesslevel'] == 'High' ? 'selected' : '' ?>>High</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Group Size</label>
            <select name="groupsize" class="w-full border-gray-300 rounded p-2" required>
              <option value="2" <?= $trip['groupsize'] == '2' ? 'selected' : '' ?>>2</option>
              <option value="2-6" <?= $trip['groupsize'] == '2-6' ? 'selected' : '' ?>>2-6</option>
              <option value="6-14" <?= $trip['groupsize'] == '6-14' ? 'selected' : '' ?>>6-14</option>
              <option value="14-More" <?= $trip['groupsize'] == '14-More' ? 'selected' : '' ?>>14-More</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Age</label>
            <input type="number" name="minimumage" value="<?= htmlspecialchars($trip['minimumage']) ?>" class="w-full border-gray-300 rounded p-2" required>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Maximum Age</label>
            <input type="number" name="maximumage" value="<?= htmlspecialchars($trip['maximumage']) ?>" class="w-full border-gray-300 rounded p-2" required>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
            <select name="location" class="w-full border-gray-300 rounded p-2" required>
              <?php while ($row = $locationsResult->fetch_assoc()): ?>
                <option value="<?= $row['distination'] ?>" <?= $row['distination'] == $trip['location'] ? 'selected' : '' ?>><?= $row['distination'] ?></option>
              <?php endwhile; ?>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Activity</label>
            <select name="activity" class="w-full border-gray-300 rounded p-2" required>
              <?php while ($row = $activitiesResult->fetch_assoc()): ?>
                <option value="<?= $row['activity'] ?>" <?= $row['activity'] == $trip['activity'] ? 'selected' : '' ?>><?= $row['activity'] ?></option>
              <?php endwhile; ?>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Duration</label>
            <input type="text" name="duration" value="<?= htmlspecialchars($trip['duration']) ?>" class="w-full border-gray-300 rounded p-2" required>
          </div>
        </div>

        <div class="flex justify-end space-x-4 pt-6">
          <a href="alltrip.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">Cancel</a>
          <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Update Trip</button>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
