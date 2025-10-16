<?php
require "frontend/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and validate form data
    $activity = trim($_POST['activity']);
    $description = trim($_POST['description']);
     
    // Initialize image path
    $act_image = '';
    
    // Handle file upload if image is provided
    if (isset($_FILES['act_image']) && $_FILES['act_image']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "../assets/activity/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        // Validate image
        $check = getimagesize($_FILES["act_image"]["tmp_name"]);
        if ($check === false) {
            echo "<script>alert('File is not an image.');</script>";
        } else {
            $imageFileType = strtolower(pathinfo($_FILES["act_image"]["name"], PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (!in_array($imageFileType, $allowedTypes)) {
                echo "<script>alert('Only JPG, JPEG, PNG & GIF files are allowed.');</script>";
            } else {
                $new_filename = uniqid() . '.' . $imageFileType;
                $target_file = $target_dir . $new_filename;
                
                if (move_uploaded_file($_FILES["act_image"]["tmp_name"], $target_file)) {
                    $act_image = "assets/activity/" . $new_filename;
                } else {
                    echo "<script>alert('Error uploading image.');</script>";
                }
            }
        }
    }
    
    // Insert activity data into the database
    $sql = "INSERT INTO activities (activity, description, main_image) 
            VALUES (?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("sss", $activity, $description, $act_image);
        
        if ($stmt->execute()) {
            echo "<script>alert('Activity created successfully'); window.location.href = 'allactivities.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error: " . addslashes($stmt->error) . "');</script>";
        }
        
        $stmt->close();
    } else {
        echo "<script>alert('Error preparing statement: " . addslashes($conn->error) . "');</script>";
    }
    
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Create Activity - ThankYouNepalTrip</title>
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
        <div class="mb-8">
          <div class="gradient-bg rounded-2xl p-6 text-white">
            <h1 class="text-3xl font-bold">
              <i class="fas fa-hiking mr-3"></i>Add New Activities
            </h1>
          </div>
        </div>
        
        <form method="post" enctype="multipart/form-data" onsubmit="return validateForm(event)">
          <div class="grid grid-cols-1 gap-6">
            <div class="form-group">
              <label class="form-label" for="activity">Activity Name *</label>
              <input type="text" name="activity" id="activity" class="form-input" required>
            </div>
            
            <div class="form-group">
              <label class="form-label" for="description">Description *</label>
              <textarea name="description" id="description" class="form-input h-32" required></textarea>
            </div>
            
            <div class="form-group">
              <label class="form-label" for="act_image">Activity Image</label>
              <input type="file" name="act_image" id="act_image" accept="image/*" class="form-input" onchange="previewImage(event)">
              <img id="imagePreview" src="#" alt="Image Preview" class="hidden mt-2 h-32 object-contain">
              <p class="text-xs text-gray-500 mt-1">Only JPG, JPEG, PNG, GIF files allowed (Max 5MB)</p>
            </div>
            
          </div>
          
          <div class="mt-8 flex justify-end space-x-4">
            <a href="allactivities.php" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
              Cancel
            </a>
            <button type="submit" class="gradient-bg text-white px-6 py-2 rounded-lg hover:opacity-90 transition-colors">
              <i class="fas fa-save mr-2"></i>Create Activities
            </button>
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

    function previewImage(event) {
      const input = event.target;
      const preview = document.getElementById('imagePreview');
      const file = input.files[0];
      
      if (file) {
        // Validate file size (5MB max)
        if (file.size > 5 * 1024 * 1024) {
          alert('File is too large. Maximum size is 5MB.');
          input.value = '';
          return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
          preview.src = e.target.result;
          preview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
      }
    }

    function validateForm(event) {
      const activity = document.getElementById('activity').value.trim();
      const description = document.getElementById('description').value.trim();
      const fileInput = document.getElementById('act_image');
      
      // Basic validation
      if (!activity || !description) {
        alert('Please fill in all required fields.');
        event.preventDefault();
        return false;
      }
      
      // File validation if a file was selected
      if (fileInput.files.length > 0) {
        const file = fileInput.files[0];
        const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
        
        if (!validTypes.includes(file.type)) {
          alert('Only JPG, PNG, and GIF images are allowed.');
          event.preventDefault();
          return false;
        }
        
        if (file.size > 5 * 1024 * 1024) {
          alert('Image size must be less than 5MB.');
          event.preventDefault();
          return false;
        }
      }

      return true;
    }
  </script>
</body>
</html>