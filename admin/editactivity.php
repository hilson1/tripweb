<?php
require '../connection.php';

// Check if activity name is provided
if (!isset($_GET['name']) || empty($_GET['name'])) {
    header("Location: allactivities.php");
    exit();
}

$activity_name = htmlspecialchars(trim($_GET['name']), ENT_QUOTES, 'UTF-8');

// Fetch activity data
$stmt = $conn->prepare("SELECT * FROM activities WHERE activity = ?");
$stmt->bind_param("s", $activity_name);
$stmt->execute();
$result = $stmt->get_result();
$activity = $result->fetch_assoc();

if (!$activity) {
    header("Location: allactivities.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $activity_name = trim($_POST['activity']);
    $description = trim($_POST['description']);
    $original_name = htmlspecialchars(trim($_POST['original_name']), ENT_QUOTES, 'UTF-8');
    
    // Initialize image path with existing value
    $image_path = $activity['main_image'];
    
    // Handle image upload if new file was provided
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/activities/";
        
        // Create directory if it doesn't exist
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        
        // Validate image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (in_array($imageFileType, $allowedTypes)) {
                // Generate unique filename
                $new_filename = uniqid() . '.' . $imageFileType;
                $target_file = $target_dir . $new_filename;
                
                // Move uploaded file
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    // Delete old image if it exists
                    if (!empty($activity['main_image']) && file_exists($activity['main_image'])) {
                        @unlink($activity['main_image']);
                    }
                    $image_path = $target_file;
                } else {
                    echo "<script>alert('Error uploading image file.');</script>";
                }
            } else {
                echo "<script>alert('Only JPG, JPEG, PNG & GIF files are allowed.');</script>";
            }
        } else {
            echo "<script>alert('File is not an image.');</script>";
        }
    }
    
    // Update activity in database
    $update_stmt = $conn->prepare("UPDATE activities SET activity = ?, description = ?, main_image = ? WHERE activity = ?");
    $update_stmt->bind_param("ssss", $activity_name, $description, $image_path, $original_name);
    
    if ($update_stmt->execute()) {
        echo "<script>alert('Activity updated successfully'); window.location.href = 'allactivities.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error updating activity: " . addslashes($conn->error) . "');</script>";
    }
    
    $update_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Edit Activity - ThankYouNepalTrip</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  
  <!-- FontAwesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="frontend/sidebar.css">
</head>

<body class="bg-gray-50 font-sans leading-normal tracking-normal" x-data="{ sidebarOpen: false }">
  <!-- Overlay for mobile sidebar -->
  <div class="overlay" :class="{ 'open': sidebarOpen }" @click="sidebarOpen = false"></div>

  <!-- Top Navigation Bar -->
  <?php include 'frontend/header.php'; ?>

  <!-- Sidebar -->
  <?php include 'frontend/sidebar.php'; ?>

  <!-- Main Content Area -->
  <main class="main-content pt-16 min-h-screen transition-all duration-300">
    <div class="p-6">
      <!-- Edit Activity Content -->
      <div class="bg-white rounded-xl shadow-md p-6">
        <!-- Header Section -->
        <div class="mb-8">
          <div class="gradient-bg rounded-2xl p-6 text-white">
            <div class="flex justify-between items-center">
              <div>
                <h1 class="text-3xl font-bold mb-2">
                  <i class="fas fa-edit mr-3"></i>Edit Activity
                </h1>
                <p class="text-blue-100">Update activity information</p>
              </div>
              <a href="allactivities.php" 
                 class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-6 py-2 rounded-lg flex items-center transition-all duration-300">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to List
              </a>
            </div>
          </div>
        </div>

        <!-- Form Section -->
        <div class="glass-effect rounded-2xl shadow-xl p-6">
          <form method="post" enctype="multipart/form-data" class="space-y-6">
            <input type="hidden" name="original_name" value="<?= htmlspecialchars($activity['activity']) ?>">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Activity Name -->
              <div class="col-span-2 space-y-2">
                <label class="block text-sm font-semibold text-gray-700">
                  <i class="fas fa-hiking mr-2 text-blue-500"></i>Activity Name
                </label>
                <input type="text" name="activity" id="activity" 
                       value="<?php echo htmlspecialchars($activity['activity']); ?>"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300" required>
              </div>

              <!-- Description -->
              <div class="col-span-2 space-y-2">
                <label class="block text-sm font-semibold text-gray-700">
                  <i class="fas fa-align-left mr-2 text-blue-500"></i>Description
                </label>
                <textarea name="description" id="description" rows="4"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                          required><?php echo htmlspecialchars($activity['description']); ?></textarea>
              </div>

              <!-- Image Upload -->
              <div class="col-span-2 space-y-4">
                <label class="block text-sm font-semibold text-gray-700">
                  <i class="fas fa-image mr-2 text-blue-500"></i>Activity Image
                </label>
                <div class="flex items-start gap-6">
                  <div id="current-image" class="w-48">
                    <?php if (!empty($activity['main_image']) && file_exists("../" . $activity['main_image'])): ?>
                      <img src="../<?php echo htmlspecialchars($activity['main_image']); ?>" 
                           alt="Current Activity Image" 
                           class="max-h-48 w-auto object-cover rounded-lg border shadow-md"
                           onerror="this.onerror=null; this.src='../assets/no-image.jpg';">
                    <?php else: ?>
                      <div class="w-32 h-32 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400">
                        <i class="fas fa-image text-2xl"></i>
                      </div>
                    <?php endif; ?>
                    <span class="text-xs text-gray-500 block mt-2 text-center">Current Image</span>
                  </div>

                  <div class="flex-1">
                    <label class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors duration-300">
                      <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-10 h-10 mb-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                        </svg>
                        <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span></p>
                        <p class="text-xs text-gray-500">PNG, JPG or GIF (MAX. 5MB)</p>
                      </div>
                      <input id="image" name="image" type="file" class="hidden" accept="image/*">
                    </label>
                    <div id="image-preview" class="mt-4"></div>
                  </div>
                </div>
              </div>

              <!-- Buttons -->
              <div class="col-span-2 flex justify-end gap-4 pt-6">
                <button type="button" onclick="window.location.href='allactivities.php'"
                        class="px-6 py-3 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-300">
                  <i class="fas fa-times mr-2"></i>Cancel
                </button>
                <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-lg shadow-lg transition-all duration-300 font-medium">
                  <i class="fas fa-save mr-2"></i>Update Activity
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>

  <!-- Alpine JS for dropdown functionality -->
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  
  <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Image preview functionality
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('image-preview');
            const currentImage = document.getElementById('current-image');
            
            if (file) {
                // Check file size (5MB max)
                const maxSize = 5 * 1024 * 1024; // 5MB
                if (file.size > maxSize) {
                    alert('File is too large. Maximum size is 5MB.');
                    this.value = '';
                    return;
                }
                
                // Check file type
                const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
                if (!validTypes.includes(file.type)) {
                    alert('Only JPG, PNG, and GIF images are allowed.');
                    this.value = '';
                    return;
                }
                
                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `
                        <img src="${e.target.result}" class="max-h-48 w-auto object-cover rounded-lg shadow-md">
                        <span class="text-xs text-gray-500 block mt-2 text-center">New Image Preview</span>
                    `;
                    currentImage.classList.add('hidden');
                }
                reader.readAsDataURL(file);
            }
        });
    });
  </script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>