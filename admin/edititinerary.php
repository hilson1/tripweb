<?php
require 'frontend/connection.php';

$itinerary = [];
$trips = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $itinerary_id = filter_input(INPUT_POST, 'itinerary_id', FILTER_VALIDATE_INT);
        $tripid = filter_input(INPUT_POST, 'tripid', FILTER_VALIDATE_INT);
        $day_number = filter_input(INPUT_POST, 'day_number', FILTER_VALIDATE_INT);
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);

        // Validation
        if (empty($tripid)) throw new Exception("Error: Trip selection is required");
        if (empty($day_number) || $day_number < 1) throw new Exception("Error: Valid day number is required");
        if (empty($title)) throw new Exception("Error: Title is required");
        if (empty($description)) throw new Exception("Error: Description is required");

        // Update query
        $stmt = $conn->prepare("UPDATE itinerary SET 
            tripid = ?,
            day_number = ?,
            title = ?,
            description = ?
            WHERE itinerary_id = ?");

        $stmt->bind_param("iissi", 
            $tripid,
            $day_number,
            $title,
            $description,
            $itinerary_id
        );

        if (!$stmt->execute()) {
            throw new Exception("Error: " . $stmt->error);
        }

        $_SESSION['message'] = "Success: Itinerary updated successfully!";
        header("Location: allitinerary.php");
        exit();

    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header("Location: edititinerary.php?id=" . $itinerary_id);
        exit();
    }
} else {
    // Get itinerary data for editing
    if (isset($_GET['id'])) {
        $itinerary_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        
        if ($itinerary_id) {
            // Fetch itinerary with trip name
            $stmt = $conn->prepare("
                SELECT i.*, t.title AS trip_name 
                FROM itinerary i 
                LEFT JOIN trips t ON i.tripid = t.tripid 
                WHERE i.itinerary_id = ?
            ");
            $stmt->bind_param("i", $itinerary_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $itinerary = $result->fetch_assoc();
            
            if (!$itinerary) {
                $_SESSION['error'] = "Error: Itinerary not found";
                header("Location: allitinerary.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Error: Invalid Itinerary ID";
            header("Location: allitinerary.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Error: Itinerary ID not specified";
        header("Location: allitinerary.php");
        exit();
    }

    // Fetch all trips for dropdown
    $stmt = $conn->prepare("SELECT tripid, title FROM trips ORDER BY title ASC");
    $stmt->execute();
    $trips_result = $stmt->get_result();
    while ($trip = $trips_result->fetch_assoc()) {
        $trips[] = $trip;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Itinerary - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

            dropdownToggles.forEach(toggle => {
                toggle.addEventListener('click', function (event) {
                    event.preventDefault();
                    
                    // Close all dropdowns
                    document.querySelectorAll('.dropdown-menu').forEach(menu => {
                        if (menu !== this.nextElementSibling) {
                            menu.classList.add('hidden');
                        }
                    });

                    // Toggle current dropdown
                    const dropdownMenu = this.nextElementSibling;
                    dropdownMenu.classList.toggle('hidden');
                });
            });

            // Close dropdowns when clicking outside
            document.addEventListener('click', function (event) {
                if (!event.target.closest('.dropdown-toggle')) {
                    document.querySelectorAll('.dropdown-menu').forEach(menu => {
                        menu.classList.add('hidden');
                    });
                }
            });

            // Character counter for description
            const descTextarea = document.getElementById('description');
            const charCount = document.getElementById('char-count');
            
            if (descTextarea && charCount) {
                descTextarea.addEventListener('input', function() {
                    charCount.textContent = this.value.length;
                });
            }

            // Form validation
            const form = document.getElementById('editItineraryForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const dayNumber = document.getElementById('day_number').value;
                    const title = document.getElementById('title').value.trim();
                    const description = document.getElementById('description').value.trim();

                    if (dayNumber < 1) {
                        e.preventDefault();
                        alert('Day number must be at least 1');
                        return;
                    }

                    if (title.length < 3) {
                        e.preventDefault();
                        alert('Title must be at least 3 characters long');
                        return;
                    }

                    if (description.length < 10) {
                        e.preventDefault();
                        alert('Description must be at least 10 characters long');
                        return;
                    }
                });
            }
        });
    </script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
   <div class="flex h-screen">
        <!-- Sidebar -->
        <?php include("frontend/asidebar.php"); ?>

        <!-- Main section -->
        <div class="ml-64 p-6 w-[84%] mx-auto">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-4"><br></h1>
                <div class="bg-gray-100 p-4 rounded-lg mb-6">
                    <div class="flex items-center justify-between mb-6">
                        <h1 class="text-2xl font-bold text-gray-800">Edit Itinerary</h1>
                        <a href="allitinerary.php" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to List
                        </a>
                    </div>

                    <!-- Display messages -->
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>

                    <form id="editItineraryForm" action="edititinerary.php" method="POST" class="space-y-6">
                        <input type="hidden" name="itinerary_id" value="<?= htmlspecialchars($itinerary['itinerary_id'] ?? '') ?>">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Trip Selection -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    Select Trip <span class="text-red-500">*</span>
                                </label>
                                <select name="tripid" id="tripid" 
                                        class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                        required>
                                    <option value="">Choose a trip...</option>
                                    <?php foreach ($trips as $trip): ?>
                                        <option value="<?= $trip['tripid'] ?>" 
                                                <?= ($itinerary['tripid'] ?? '') == $trip['tripid'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($trip['title']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (!empty($itinerary['trip_name'])): ?>
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Currently assigned to: <strong><?= htmlspecialchars($itinerary['trip_name']) ?></strong>
                                    </p>
                                <?php endif; ?>
                            </div>

                            <!-- Day Number -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    Day Number <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="day_number" id="day_number"
                                       value="<?= htmlspecialchars($itinerary['day_number'] ?? '') ?>" 
                                       min="1" max="30"
                                       class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       required>
                                <p class="text-xs text-gray-500">Enter the day number (1-30)</p>
                            </div>
                        </div>

                        <!-- Title -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Activity Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" id="title"
                                   value="<?= htmlspecialchars($itinerary['title'] ?? '') ?>" 
                                   maxlength="100"
                                   class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Enter activity title..."
                                   required>
                            <p class="text-xs text-gray-500">Maximum 100 characters</p>
                        </div>

                        <!-- Description -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Description <span class="text-red-500">*</span>
                            </label>
                            <textarea name="description" id="description" rows="6"
                                      class="w-full px-3 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Describe the activities, places to visit, meals, etc..."
                                      required><?= htmlspecialchars($itinerary['description'] ?? '') ?></textarea>
                            <div class="flex justify-between">
                                <p class="text-xs text-gray-500">Provide detailed information about the day's activities</p>
                                <p class="text-xs text-gray-500">Characters: <span id="char-count"><?= strlen($itinerary['description'] ?? '') ?></span></p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-4 pt-6 border-t">
                            <a href="allitinerary.php" 
                               class="px-6 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-save mr-2"></i>
                                Update Itinerary
                            </button>
                        </div>
                    </form>

                    <!-- Additional Info Card -->
                    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h3 class="text-lg font-medium text-blue-800 mb-2">
                            <i class="fas fa-lightbulb mr-2"></i>
                            Tips for Writing Itinerary
                        </h3>
                        <ul class="text-sm text-blue-700 space-y-1">
                            <li>• Be specific about timings and locations</li>
                            <li>• Include meal information and accommodation details</li>
                            <li>• Mention transportation arrangements</li>
                            <li>• Add any special requirements or notes</li>
                            <li>• Keep descriptions clear and informative</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>