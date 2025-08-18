<?php
require'../connection.php';

// Fetch trip types for dropdown
$triptypes = [];
$stmt = $conn->prepare("SELECT triptype_id, triptype FROM triptypes");
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
            maximumaltitude = ?, departurefrom = ?, bestseason = ?, triptype_id = ?,
            meals = ?, language = ?, fitnesslevel = ?, groupsize = ?,
            minimumage = ?, maximumage = ?, location = ?,
            duration = ?, status = ?
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
            $_POST['triptype_id'],
            $_POST['meals'],
            $_POST['language'],
            $_POST['fitnesslevel'],
            $_POST['groupsize'],
            $_POST['minimumage'],
            $_POST['maximumage'],
            $_POST['location'],
            $_POST['duration'],
            $_POST['status'],
            $tripId
        );

        if (!$stmt->execute()) {
            throw new Exception("Update failed: " . $stmt->error);
        }
        $stmt->close();

        // Handle images
        $uploadDir = '../uploads/tripimg/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

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
                
                if (move_uploaded_file($_FILES[$field]['tmp_name'], $targetPath)) {
                    $stmt = $conn->prepare("UPDATE trip_images SET $field = ? WHERE tripid = ?");
                    $stmt->bind_param("si", $targetPath, $tripId);
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Dropdown functionality
            const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
            dropdownToggles.forEach(toggle => {
                toggle.addEventListener('click', function (event) {
                    event.preventDefault();
                    document.querySelectorAll('.dropdown-menu').forEach(menu => {
                        if (menu !== this.nextElementSibling) {
                            menu.classList.add('hidden');
                        }
                    });
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
        });
    </script>
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <?php include("frontend/asidebar.php"); ?>
        
        <!-- Main section -->
        <div class="ml-64 p-6 w-[84%] mx-auto">
            <div class="bg-white shadow-md rounded-lg p-6 mt-6">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-3xl font-bold text-gray-800 mt-6 tracking-tighter">Edit Trip</h1>
                    <a href="alltrip.php" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to List
                    </a>
                </div>
                
                <?php include('messages.php') ?>
                <!-- Error message -->
                 
                <?php if (isset($error)): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" enctype="multipart/form-data" class="space-y-6 mt-8">
                    <!-- Main Trip Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Trip Title</label>
                            <input type="text" name="title" 
                                   value="<?= htmlspecialchars($trip['title']) ?>" 
                                   class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   required>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Transportation</label>
                            <select name="transportation" class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="Bus" <?= $trip['transportation'] === 'Bus' ? 'selected' : '' ?>>Bus</option>
                                <option value="Car" <?= $trip['transportation'] === 'Car' ? 'selected' : '' ?>>Car</option>
                                <option value="Helicopter" <?= $trip['transportation'] === 'Helicopter' ? 'selected' : '' ?>>Helicopter</option>
                            </select>
                        </div>
                        
                        <div class="space-y-2 md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Short Description</label>
                            <textarea name="description" rows="4" 
                                      class="w-full px-3 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500"
                                      required><?= htmlspecialchars($trip['description']) ?></textarea>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Trip Type</label>
                            <select name="triptype_id" class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                <?php foreach ($triptypes as $type): ?>
                                    <option value="<?= $type['triptype_id'] ?>" <?= $trip['triptype_id'] == $type['triptype_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($type['triptype']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Location</label>
                            <input type="text" name="location" value="<?= htmlspecialchars($trip['location']) ?>" 
                                   class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   required>    
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Price</label>
                            <input type="number" name="price" value="<?= htmlspecialchars($trip['price']) ?>" 
                                   class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   required>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Duration</label>
                            <input type="text" name="duration" value="<?= htmlspecialchars($trip['duration']) ?>" 
                                   placeholder="e.g., 2 weeks, 10 days"
                                   class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   required>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="active" <?= $trip['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="expired" <?= $trip['status'] === 'expired' ? 'selected' : '' ?>>Expired</option>
                                <option value="draft" <?= $trip['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                            </select>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Best Season</label>
                            <select name="bestseason" class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="Winter" <?= $trip['bestseason'] === 'Winter' ? 'selected' : '' ?>>Winter</option>
                                <option value="Summer" <?= $trip['bestseason'] === 'Summer' ? 'selected' : '' ?>>Summer</option>
                                <option value="Spring" <?= $trip['bestseason'] === 'Spring' ? 'selected' : '' ?>>Spring</option>
                                <option value="Autumn" <?= $trip['bestseason'] === 'Autumn' ? 'selected' : '' ?>>Autumn</option>
                            </select>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Departure From</label>
                            <select name="departurefrom" class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="Kathmandu" <?= $trip['departurefrom'] === 'Kathmandu' ? 'selected' : '' ?>>Kathmandu</option>
                                <option value="Lalitpur" <?= $trip['departurefrom'] === 'Lalitpur' ? 'selected' : '' ?>>Lalitpur</option>
                                <option value="Bhaktapur" <?= $trip['departurefrom'] === 'Bhaktapur' ? 'selected' : '' ?>>Bhaktapur</option>
                            </select>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Accommodation</label>
                            <input type="text" name="accomodation" value="<?= htmlspecialchars($trip['accomodation']) ?>" 
                                   class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   required>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Maximum Altitude</label>
                            <input type="text" name="maximumaltitude" value="<?= htmlspecialchars($trip['maximumaltitude']) ?>" 
                                   class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Meals</label>
                            <input type="text" name="meals" value="<?= htmlspecialchars($trip['meals']) ?>" 
                                   class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   required>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Language</label>
                            <input type="text" name="language" value="<?= htmlspecialchars($trip['language']) ?>" 
                                   class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   required>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Fitness Level</label>
                            <select name="fitnesslevel" class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="Beginner" <?= $trip['fitnesslevel'] === 'Beginner' ? 'selected' : '' ?>>Beginner</option>
                                <option value="Medium" <?= $trip['fitnesslevel'] === 'Medium' ? 'selected' : '' ?>>Medium</option>
                                <option value="High" <?= $trip['fitnesslevel'] === 'High' ? 'selected' : '' ?>>High</option>
                            </select>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Group Size</label>
                            <select name="groupsize" class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="2" <?= $trip['groupsize'] === '2' ? 'selected' : '' ?>>2</option>
                                <option value="2-6" <?= $trip['groupsize'] === '2-6' ? 'selected' : '' ?>>2-6</option>
                                <option value="6-14" <?= $trip['groupsize'] === '6-14' ? 'selected' : '' ?>>6-14</option>
                                <option value="14-More" <?= $trip['groupsize'] === '14-More' ? 'selected' : '' ?>>14-More</option>
                            </select>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Minimum Age</label>
                            <input type="number" name="minimumage" value="<?= htmlspecialchars($trip['minimumage']) ?>" 
                                   class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   required>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Maximum Age</label>
                            <input type="number" name="maximumage" value="<?= htmlspecialchars($trip['maximumage']) ?>" 
                                   class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   required>
                        </div>
                    </div>

                    <!-- Image Upload Section -->
                    <div class="space-y-4">
                        <label class="block text-sm font-medium text-gray-700">Trip Images</label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <?php foreach (['main_image', 'side_image1', 'side_image2'] as $index => $field): ?>
                            <div class="space-y-2">
                                <label class="block text-xs text-gray-600 text-center">
                                    <?= $field === 'main_image' ? 'Main Image' : ($field === 'side_image1' ? 'Side Image 1' : 'Side Image 2') ?>
                                </label>
                                <div class="image-upload group relative" id="<?= $field ?>_container">
                                    <?php if (!empty($trip[$field])): ?>
                                        <div class="image-preview" style="background-image: url('<?= htmlspecialchars($trip[$field]) ?>')"></div>
                                        <button type="button" class="delete-existing-image delete-btn" data-field="<?= $field ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    <?php else: ?>
                                        <div class="image-upload-icon">+</div>
                                        <input type="file" name="<?= $field ?>" class="hidden" accept="image/*">
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <input type="hidden" name="deleted_images" id="deleted_images" value="">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg">
                            <i class="fas fa-save mr-2"></i>
                            Update Trip
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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