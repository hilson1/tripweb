<?php
require'../connection.php';

$destinations = [];
$stmt = $conn->prepare("SELECT distination FROM destinations");
if($stmt->execute()) {
    $result = $stmt->get_result();
    $destinations = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    die("Error fetching destinations: " . $conn->error);
}
// Fetch activities for dropdown
$activities= [];
$stmt = $conn->prepare("SELECT activity FROM activities");
if ($stmt->execute()) {
    $result = $stmt->get_result();
    $activities = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    die("Error fetching activities: " . $conn->error);
}
// Fetch trip types for dropdown
$triptypes = [];
$stmt = $conn->prepare("SELECT triptype FROM triptypes");
if ($stmt->execute()) {
    $result = $stmt->get_result();
    $triptypes = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    die("Error fetching trip types: " . $conn->error);
}

if (!isset($_GET['id'])) {
    header("Location: alltrip.php");
    exit();
}

$tripId = $_GET['id'];

// Fetch trip data with images
$trip = [];
$stmt = $conn->prepare("
    SELECT trips.*, trip_images.main_image, trip_images.side_image1, trip_images.side_image2 
    FROM trips 
    LEFT JOIN trip_images ON trips.tripid = trip_images.tripid 
    WHERE trips.tripid = ?
");
$stmt->bind_param("i", $tripId);
if ($stmt->execute()) {
    $result = $stmt->get_result();
    $trip = $result->fetch_assoc();
    $stmt->close();
    if (!$trip) die("Trip not found");
} else {
    die("Error fetching trip: " . $conn->error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Update main trip data - FIXED parameter binding order
        $stmt = $conn->prepare("UPDATE trips SET
            title = ?, price = ?, description = ?, transportation = ?, accomodation = ?,
            maximumaltitude = ?, departurefrom = ?, bestseason = ?, triptype = ?,
            meals = ?, language = ?, fitnesslevel = ?, groupsize = ?,
            minimumage = ?, maximumage = ?, location = ?,
            duration = ?, activity = ?
            WHERE tripid = ?
        ");

        // FIXED: Corrected parameter binding to match the query order
        $stmt->bind_param(
            "sdsssississssiisssi",
            $_POST['title'],
            $_POST['price'],
            $_POST['description'],
            $_POST['transportation'],
            $_POST['accomodation'],
            $_POST['maximumaltitude'],
            $_POST['departurefrom'],
            $_POST['bestseason'],
            $_POST['triptype'],
            $_POST['meals'],
            $_POST['language'],
            $_POST['fitnesslevel'],
            $_POST['groupsize'],
            $_POST['minimumage'],
            $_POST['maximumage'],
            $_POST['location'],
            $_POST['duration'],
            $_POST['activity'],
            $tripId
        );

        if (!$stmt->execute()) {
            throw new Exception("Update failed: " . $stmt->error);
        }
        $stmt->close();

        // Handle images
        $uploadDir = __DIR__ . '/../uploads/tripimg/'; // physical path for move_uploaded_file
        $uploadUrl = '/uploads/tripimg/'; // web URL path to store in DB

        // Check if images row exists
        $stmt = $conn->prepare("SELECT * FROM trip_images WHERE tripid = ?");
        $stmt->bind_param("i", $tripId);
        $stmt->execute();
        $result = $stmt->get_result();
        $imageRow = $result->fetch_assoc();
        $stmt->close();

        if (!$imageRow) {
            $stmt = $conn->prepare("INSERT INTO trip_images (tripid) VALUES (?)");
            $stmt->bind_param("i", $tripId);
            $stmt->execute();
            $stmt->close();
        }

        // Process image updates
        $imageFields = ['main_image', 'side_image1', 'side_image2'];
        foreach ($imageFields as $field) {
            if (!empty($_FILES[$field]['name'])) {
                $fileName = uniqid() . '_' . basename($_FILES[$field]['name']);
                $targetPath = $uploadDir . $fileName;
                $targetUrl = $uploadUrl . $fileName;
                
            if (move_uploaded_file($_FILES[$field]['tmp_name'], $targetPath)) {
                $stmt = $conn->prepare("UPDATE trip_images SET $field = ? WHERE tripid = ?");
                $stmt->bind_param("si", $targetUrl, $tripId); // store URL in DB, not file path
                $stmt->execute();
                $stmt->close();
            }
            }
            
            // Handle deletions
            if (isset($_POST['deleted_images']) && 
                in_array($field, explode(',', $_POST['deleted_images']))) {
                $stmt = $conn->prepare("UPDATE trip_images SET $field = NULL WHERE tripid = ?");
                $stmt->bind_param("i", $tripId);
                $stmt->execute();
                $stmt->close();
            }
        }

        $_SESSION['success'] = "Trip updated successfully!";
        header("Location: alltrip.php");
        exit();

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trip Management - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="frontend/sidebar.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <style>
        .table-container {
            max-height: 600px;
            overflow-y: auto;
        }
        
        .status-badge {
            @apply px-2 py-1 text-xs font-semibold rounded-full;
        }
        
        .status-active {
            @apply bg-green-100 text-green-800;
        }
        
        .status-expired {
            @apply bg-red-100 text-red-800;
        }
        
        .status-featured {
            @apply bg-blue-100 text-blue-800;
        }
        
        .btn-action {
            @apply px-3 py-1 text-sm font-medium rounded-md transition-colors duration-200;
        }
        
        .btn-edit {
            @apply bg-blue-500 text-white hover:bg-blue-600;
        }
        
        .btn-delete {
            @apply bg-red-500 text-white hover:bg-red-600;
        }

        /* Image upload styles */
        .image-upload {
            width: 150px;
            height: 150px;
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            position: relative;
            background-color: #f9fafb;
            transition: all 0.2s ease;
        }

        .image-upload:hover {
            border-color: #3b82f6;
            background-color: #eff6ff;
        }

        .image-upload-icon {
            font-size: 24px;
            color: #9ca3af;
        }

        .image-preview {
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            border-radius: 6px;
        }

        .delete-btn {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ef4444;
            color: white;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 12px;
        }

        .delete-btn:hover {
            background: #dc2626;
        }
    </style>

</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="flex h-screen">
        <!-- Header --> 
        <?php include("frontend/header.php"); ?>
        <!-- Sidebar -->
        <?php include("frontend/sidebar.php"); ?>
        
          <!-- Main Content Area -->
        <main class="main-content pt-16 min-h-screen transition-all duration-300">
            <div class="p-6">
            <!-- Success/Error Messages -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
                <span class="block sm:inline"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></span>
                </div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                <span class="block sm:inline"><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>

            <!-- Edit Trip Content -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <!-- Header Section -->
                <div class="mb-8">
                <div class="gradient-bg rounded-2xl p-6 text-white">
                    <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">
                        <i class="fas fa-edit mr-3"></i>Edit Trip
                        </h1>
                        <p class="text-blue-100">Update trip information and images</p>
                    </div>
                    <div class="text-right">
                        <a href="alltrip.php" 
                        class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition-colors duration-200 backdrop-blur-sm">
                        <i class="fas fa-arrow-left mr-2"></i>Back to List
                        </a>
                    </div>
                    </div>
                </div>
                </div>

                <!-- Form Section -->
                <div class="glass-effect rounded-2xl shadow-xl p-8">
                <form method="POST" enctype="multipart/form-data" class="space-y-8">
                    <!-- Basic Information Section -->
                    <div class="space-y-6">
                    <h3 class="text-xl font-semibold text-gray-800 border-b border-gray-200 pb-2">
                        <i class="fas fa-info-circle mr-2 text-blue-500"></i>Basic Information
                    </h3>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="space-y-3">
                        <label class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-heading mr-2 text-green-500"></i>Trip Title
                        </label>
                        <input type="text" name="title" 
                                value="<?= htmlspecialchars($trip['title']) ?>" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                required>
                        </div>
                        
                        <div class="space-y-3">
                        <label class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-dollar-sign mr-2 text-green-500"></i>Price (USD)
                        </label>
                        <input type="number" name="price" value="<?= htmlspecialchars($trip['price']) ?>" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                required>
                        </div>
                        
                        <div class="space-y-3 lg:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-align-left mr-2 text-blue-500"></i>Description
                        </label>
                        <textarea name="description" rows="4" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                    required><?= htmlspecialchars($trip['description']) ?></textarea>
                        </div>
                    </div>
                    </div>

                    <!-- Trip Details Section -->
                    <div class="space-y-6">
                    <h3 class="text-xl font-semibold text-gray-800 border-b border-gray-200 pb-2">
                        <i class="fas fa-map-marked-alt mr-2 text-purple-500"></i>Trip Details
                    </h3>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="space-y-3">
                        <label class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-tag mr-2 text-yellow-500"></i>Trip Type
                        </label>
                        <select name="triptype" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" required>
                            <?php foreach ($triptypes as $type): ?>
                                <option value="<?= $type['triptype'] ?>" <?= $trip['triptype'] == $type['triptype'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($type['triptype']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        </div>
                        
                        <div class="space-y-3">
                        <label class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>Location
                        </label>
                        <select name="location" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" required>
                            <?php foreach ($destinations as $destination): ?>
                                <option value="<?= $destination['distination'] ?>" <?= $destination['distination'] == $trip['location'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($destination['distination']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>    
                        </div>
                        
                        <div class="space-y-3">
                        <label class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-clock mr-2 text-orange-500"></i>Duration
                        </label>
                        <input type="text" name="duration" value="<?= htmlspecialchars($trip['duration']) ?>" 
                                placeholder="e.g., 2 weeks, 10 days"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                required>
                        </div>
                        
                        <div class="space-y-3">
                        <label class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-hiking mr-2 text-green-500"></i>Activity
                        </label>
                        <select name="activity" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" required>
                            <?php foreach ($activities as $activity): ?>
                                <option value="<?= $activity['activity'] ?>" <?= $trip['activity'] == $activity['activity'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($activity['activity']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        </div>
                    </div>
                    </div>

                    <!-- Logistics Section -->
                    <div class="space-y-6">
                    <h3 class="text-xl font-semibold text-gray-800 border-b border-gray-200 pb-2">
                        <i class="fas fa-cogs mr-2 text-indigo-500"></i>Logistics
                    </h3>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="space-y-3">
                        <label class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-bus mr-2 text-blue-500"></i>Transportation
                        </label>
                        <select name="transportation" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" required>
                            <option value="Bus" <?= $trip['transportation'] === 'Bus' ? 'selected' : '' ?>>Bus</option>
                            <option value="Car" <?= $trip['transportation'] === 'Car' ? 'selected' : '' ?>>Car</option>
                            <option value="Helicopter" <?= $trip['transportation'] === 'Helicopter' ? 'selected' : '' ?>>Helicopter</option>
                        </select>
                        </div>
                        
                        <div class="space-y-3">
                        <label class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-plane-departure mr-2 text-purple-500"></i>Departure From
                        </label>
                        <select name="departurefrom" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" required>
                            <option value="Kathmandu" <?= $trip['departurefrom'] === 'Kathmandu' ? 'selected' : '' ?>>Kathmandu</option>
                            <option value="Lalitpur" <?= $trip['departurefrom'] === 'Lalitpur' ? 'selected' : '' ?>>Lalitpur</option>
                            <option value="Bhaktapur" <?= $trip['departurefrom'] === 'Bhaktapur' ? 'selected' : '' ?>>Bhaktapur</option>
                        </select>
                        </div>
                        
                        <div class="space-y-3">
                        <label class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-bed mr-2 text-pink-500"></i>Accommodation
                        </label>
                        <input type="text" name="accomodation" value="<?= htmlspecialchars($trip['accomodation']) ?>" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                required>
                        </div>
                        
                        <div class="space-y-3">
                        <label class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-mountain mr-2 text-gray-500"></i>Maximum Altitude
                        </label>
                        <input type="text" name="maximumaltitude" value="<?= htmlspecialchars($trip['maximumaltitude']) ?>" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        </div>
                        
                        <div class="space-y-3">
                        <label class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-utensils mr-2 text-orange-500"></i>Meals
                        </label>
                        <input type="text" name="meals" value="<?= htmlspecialchars($trip['meals']) ?>" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                required>
                        </div>
                        
                        <div class="space-y-3">
                        <label class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-language mr-2 text-teal-500"></i>Language
                        </label>
                        <input type="text" name="language" value="<?= htmlspecialchars($trip['language']) ?>" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                required>
                        </div>
                    </div>
                    </div>

                    <!-- Requirements Section -->
                    <div class="space-y-6">
                    <h3 class="text-xl font-semibold text-gray-800 border-b border-gray-200 pb-2">
                        <i class="fas fa-user-check mr-2 text-cyan-500"></i>Requirements
                    </h3>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="space-y-3">
                        <label class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-leaf mr-2 text-green-500"></i>Best Season
                        </label>
                        <select name="bestseason" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" required>
                            <option value="Winter" <?= $trip['bestseason'] === 'Winter' ? 'selected' : '' ?>>Winter</option>
                            <option value="Summer" <?= $trip['bestseason'] === 'Summer' ? 'selected' : '' ?>>Summer</option>
                            <option value="Spring" <?= $trip['bestseason'] === 'Spring' ? 'selected' : '' ?>>Spring</option>
                            <option value="Autumn" <?= $trip['bestseason'] === 'Autumn' ? 'selected' : '' ?>>Autumn</option>
                        </select>
                        </div>
                        
                        <div class="space-y-3">
                        <label class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-dumbbell mr-2 text-red-500"></i>Fitness Level
                        </label>
                        <select name="fitnesslevel" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" required>
                            <option value="Beginner" <?= $trip['fitnesslevel'] === 'Beginner' ? 'selected' : '' ?>>Beginner</option>
                            <option value="Medium" <?= $trip['fitnesslevel'] === 'Medium' ? 'selected' : '' ?>>Medium</option>
                            <option value="High" <?= $trip['fitnesslevel'] === 'High' ? 'selected' : '' ?>>High</option>
                        </select>
                        </div>
                        
                        <div class="space-y-3">
                        <label class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-users mr-2 text-blue-500"></i>Group Size
                        </label>
                        <select name="groupsize" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" required>
                            <option value="2" <?= $trip['groupsize'] === '2' ? 'selected' : '' ?>>2</option>
                            <option value="2-6" <?= $trip['groupsize'] === '2-6' ? 'selected' : '' ?>>2-6</option>
                            <option value="6-14" <?= $trip['groupsize'] === '6-14' ? 'selected' : '' ?>>6-14</option>
                            <option value="14-More" <?= $trip['groupsize'] === '14-More' ? 'selected' : '' ?>>14-More</option>
                        </select>
                        </div>
                        
                        <div class="space-y-3">
                        <label class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-child mr-2 text-yellow-500"></i>Minimum Age
                        </label>
                        <input type="number" name="minimumage" value="<?= htmlspecialchars($trip['minimumage']) ?>" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                required>
                        </div>
                        
                        <div class="space-y-3">
                        <label class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-user mr-2 text-purple-500"></i>Maximum Age
                        </label>
                        <input type="number" name="maximumage" value="<?= htmlspecialchars($trip['maximumage']) ?>" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                required>
                        </div>
                    </div>
                    </div>

                    <!-- Image Upload Section -->
                    <div class="space-y-6">
                    <h3 class="text-xl font-semibold text-gray-800 border-b border-gray-200 pb-2">
                        <i class="fas fa-images mr-2 text-pink-500"></i>Trip Images
                    </h3>
                    
                    
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <?php foreach (['main_image', 'side_image1', 'side_image2'] as $index => $field): ?>
                        <div class="space-y-3">
                        <label class="block text-sm font-semibold text-gray-700 text-center">
                            <i class="fas fa-image mr-2 text-<?= $field === 'main_image' ? 'green' : ($field === 'side_image1' ? 'yellow' : 'purple') ?>-500"></i>
                            <?= $field === 'main_image' ? 'Main Image' : ($field === 'side_image1' ? 'Side Image 1' : 'Side Image 2') ?>
                        </label>
                        <div class="relative">
                            <div class="image-upload-container w-full h-48 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors duration-200 relative group" 
                                id="<?= $field ?>_container">
                            <?php if (!empty($trip[$field])): ?>
                                <img src="<?= htmlspecialchars($trip[$field]) ?>" 
                                    class="w-full h-full object-cover rounded-lg" 
                                    alt="<?= $field ?>">
                                <button type="button" 
                                        class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-8 h-8 flex items-center justify-center transition-colors duration-200 delete-existing-image" 
                                        data-field="<?= $field ?>">
                                    <i class="fas fa-times text-sm"></i>
                                </button>
                            <?php else: ?>
                                <div class="flex flex-col items-center justify-center h-full">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                                    <p class="text-sm text-gray-500">Click to upload image</p>
                                    <p class="text-xs text-gray-400">JPG, PNG (MAX. 5MB)</p>
                                </div>
                                <input type="file" name="<?= $field ?>" class="hidden" accept="image/*">
                            <?php endif; ?>
                            </div>
                        </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <input type="hidden" name="deleted_images" id="deleted_images" value="">
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end pt-6 border-t border-gray-200">
                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-save mr-2"></i>Update Trip
                    </button>
                    </div>
                </form>
                </div>
            </div>
            </div>
        </main>
    </div>

  <!-- Alpine JS for dropdown functionality -->
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Reset image field function
        window.resetImageField = function(fieldName) {
            const container = document.getElementById(fieldName + '_container');
            container.innerHTML = `
                <div class="image-upload-icon">+</div>
                <input type="file" name="${fieldName}" class="hidden" accept="image/*">
            `;
            handleImageUpload(container);
        };

        // Image Upload Handling
        const handleImageUpload = (container) => {
            const input = container.querySelector('input[type="file"]');
            const deleteBtn = container.querySelector('.delete-existing-image');

            if (input) {
                container.addEventListener('click', (e) => {
                    if (!e.target.closest('.delete-btn')) {
                        input.click();
                    }
                });
                
                input.addEventListener('change', function() {
                    if (this.files[0]) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            container.innerHTML = `
                                <div class="image-preview" style="background-image: url('${e.target.result}')"></div>
                                <button type="button" class="delete-btn" onclick="resetImageField('${this.name}')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                     </svg>
                                </button>
                            `;
                        };
                        reader.readAsDataURL(this.files[0]);
                    }
                });
            }

            if (deleteBtn) {
                deleteBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const field = this.dataset.field;
                    const deletedInput = document.getElementById('deleted_images');
                    const deleted = deletedInput.value ? deletedInput.value.split(',') : [];
                    
                    if (!deleted.includes(field)) {
                        deleted.push(field);
                        deletedInput.value = deleted.join(',');
                    }
                    
                    // Reset to upload state
                    container.innerHTML = `
                        <div class="image-upload-icon">+</div>
                        <input type="file" name="${field}" class="hidden" accept="image/*">
                    `;
                    handleImageUpload(container);
                });
            }
        };

        // Initialize all image containers
        document.querySelectorAll('.image-upload').forEach(container => {
            handleImageUpload(container);
        });
    });
    </script>
</body>
</html>