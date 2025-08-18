<?php
require '../connection.php';

// Check if activity ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: allactivities.php");
    exit();
}

$activity_id = (int)$_GET['id'];

// Fetch activity data
$stmt = $conn->prepare("SELECT * FROM activity WHERE activity_id = ?");
$stmt->bind_param("i", $activity_id);
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
    $status = strtolower(trim($_POST['status']));
    
    // Validate status
    $status = in_array($status, ['active', 'inactive']) ? $status : 'active';
    
    // Initialize image path with existing value
    $image_path = $activity['act_image'];
    
    // Handle image upload if new file was provided
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "../uploads/activities/";
        
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
                    if (!empty($activity['act_image']) && file_exists($target_dir . $activity['act_image'])) {
                        @unlink($target_dir . $activity['act_image']);
                    }
                    $image_path = $new_filename;
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
    $update_stmt = $conn->prepare("UPDATE activity SET activity = ?, description = ?, act_image = ?, activity_status = ? WHERE activity_id = ?");
    $update_stmt->bind_param("ssssi", $activity_name, $description, $image_path, $status, $activity_id);
    
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
      <div class="glass-effect rounded-2xl shadow-xl p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Activity</h1>
        
        <form method="post" enctype="multipart/form-data">
          <div class="grid grid-cols-1 gap-6">
            <!-- Activity Information -->
            <div>
              <h2 class="text-lg font-semibold mb-4 text-gray-800">Activity Information</h2>
              
              <div class="form-group">
                <label class="form-label" for="activity">Activity Name *</label>
                <input type="text" name="activity" id="activity" class="form-input" 
                       value="<?php echo htmlspecialchars($activity['activity']); ?>" required>
              </div>
              
              <div class="form-group">
                <label class="form-label" for="description">Description *</label>
                <textarea name="description" id="description" class="form-input h-32" required><?php echo htmlspecialchars($activity['description']); ?></textarea>
              </div>
              
              <div class="form-group">
                <label class="form-label" for="status">Status *</label>
                <select name="status" id="status" class="form-input" required>
                  <option value="active" <?= ($activity['activity_status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Active</option>
                  <option value="inactive" <?= ($activity['activity_status'] ?? 'active') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
              </div>
            </div>
            
            <!-- Image Section -->
            <div>
              <h2 class="text-lg font-semibold mb-4 text-gray-800">Activity Image</h2>
              
              <div class="form-group">
                <label class="form-label">Current Image</label>
                <div class="mt-2">
                  <?php if (!empty($activity['act_image']) && file_exists("../uploads/activities/" . $activity['act_image'])): ?>
                    <img src="../uploads/activities/<?php echo htmlspecialchars($activity['act_image']); ?>" 
                         alt="Current Activity Image" 
                         class="w-32 h-32 object-cover rounded-lg"
                         onerror="this.onerror=null; this.src='../assets/no-image.jpg';">
                  <?php else: ?>
                    <div class="w-32 h-32 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400">
                      <i class="fas fa-image text-2xl"></i>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
              
              <div class="form-group">
                <label class="form-label" for="image">Upload New Image</label>
                <input type="file" name="image" id="image" accept="image/*" class="form-input">
                <p class="text-xs text-gray-500 mt-1">Only JPG, JPEG, PNG, GIF files (Max 5MB)</p>
              </div>
            </div>
          </div>
          
          <div class="flex justify-end space-x-4 mt-8">
            <button type="button" class="btn-secondary" onclick="window.location.href='allactivities.php'">Cancel</button>
            <button type="submit" class="btn-primary">Update Activity</button>
          </div>
        </form>
      </div>
    </div>
  </main>

  <!-- Alpine JS for dropdown functionality -->
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  
  <script>
    // Initialize sidebar state
    document.addEventListener('alpine:init', () => {
      Alpine.data('main', () => ({
        sidebarOpen: window.innerWidth >= 1024,
        
        init() {
          if (window.innerWidth < 1024) {
            this.sidebarOpen = false;
          }
          
          window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
              this.sidebarOpen = true;
            }
          });
        }
      }));
    });

    // Client-side image validation
    document.getElementById('image').addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        // Check file size (5MB max)
        const maxSize = 5 * 1024 * 1024; // 5MB
        if (file.size > maxSize) {
          alert('File is too large. Maximum size is 5MB.');
          this.value = '';
          return;
        }
        
        // Check file type
        const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!validTypes.includes(file.type)) {
          alert('Only JPG, PNG, and GIF images are allowed.');
          this.value = '';
        }
      }
    });
  </script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>