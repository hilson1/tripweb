<?php
require '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get and validate form data
    $destination = trim($_POST['destination']);
    $description = trim($_POST['description']);
    $status = strtolower(trim($_POST['status'] ?? 'active'));
    
    // Validate status
    if (!in_array($status, ['active', 'inactive'])) {
        $status = 'active';
    }
    
    // Initialize image path
    $imagePath = '';
    
    // Handle file upload if image is provided
    if (isset($_FILES['dest_image']) && $_FILES['dest_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../assets/destinations/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        // Validate image
        $check = getimagesize($_FILES['dest_image']['tmp_name']);
        if ($check === false) {
            $error = "File is not an image.";
        } else {
            $imageFileType = strtolower(pathinfo($_FILES['dest_image']['name'], PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (!in_array($imageFileType, $allowedTypes)) {
                $error = "Only JPG, JPEG, PNG & GIF files are allowed.";
            } elseif ($_FILES['dest_image']['size'] > 5 * 1024 * 1024) {
                $error = "File is too large. Maximum size is 5MB.";
            } else {
                $fileName = uniqid() . '.' . $imageFileType;
                $targetPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['dest_image']['tmp_name'], $targetPath)) {
                    $imagePath = 'assets/destinations/' . $fileName;
                } else {
                    $error = "Error uploading image.";
                }
            }
        }
    }
    
    // Only proceed if no errors
    if (!isset($error)) {
        $stmt = $conn->prepare("INSERT INTO destination (destination, description, dest_image, status) VALUES (?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("ssss", $destination, $description, $imagePath, $status);
            
            if ($stmt->execute()) {
                header("Location: alldestination.php?success=1");
                exit();
            } else {
                $error = "Error adding destination: " . $conn->error;
            }
            
            $stmt->close();
        } else {
            $error = "Database error: " . $conn->error;
        }
    }
    
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Add Destination - ThankYouNepalTrip</title>
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
      <div class="bg-white rounded-xl shadow-md p-6">
        <div class="mb-8">
          <div class="gradient-bg rounded-2xl p-6 text-white">
            <h1 class="text-3xl font-bold">
              <i class="fas fa-map-marker-alt mr-3"></i>Add New Destination
            </h1>
          </div>
        </div>

        <?php if (isset($error)): ?>
          <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?php echo $error; ?>
          </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" class="glass-effect rounded-2xl shadow-xl p-6" onsubmit="return validateForm()">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="form-group">
              <label class="block text-gray-700 mb-2 font-medium">Destination Name *</label>
              <input type="text" name="destination" id="destination" required 
                     class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div class="form-group">
              <label class="block text-gray-700 mb-2 font-medium">Status *</label>
              <select name="status" id="status" required
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="active" selected>Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>
            
            <div class="md:col-span-2 form-group">
              <label class="block text-gray-700 mb-2 font-medium">Description *</label>
              <textarea name="description" id="description" rows="4" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>
            
            <div class="form-group">
              <label class="block text-gray-700 mb-2 font-medium">Destination Image</label>
              <div class="border-2 border-dashed border-gray-300 rounded-lg p-4">
                <input type="file" name="dest_image" id="dest_image" accept="image/*" 
                       class="block w-full text-sm text-gray-500
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-lg file:border-0
                              file:text-sm file:font-semibold
                              file:bg-blue-50 file:text-blue-700
                              hover:file:bg-blue-100"
                       onchange="previewImage(event)">
                <p class="mt-2 text-sm text-gray-500">JPEG, PNG, or JPG (Max 5MB)</p>
              </div>
            </div>
            
            <div class="flex items-center">
              <div class="w-32 h-32 bg-gray-200 rounded-lg flex items-center justify-center overflow-hidden">
                <img id="imagePreview" src="#" alt="Preview" class="hidden w-full h-full object-cover">
                <i class="fas fa-image text-gray-400 text-3xl" id="placeholderIcon"></i>
              </div>
            </div>
          </div>
          
          <div class="mt-8 flex justify-end space-x-4">
            <a href="alldestination.php" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
              Cancel
            </a>
            <button type="submit" class="gradient-bg text-white px-6 py-2 rounded-lg hover:opacity-90 transition-colors">
              <i class="fas fa-save mr-2"></i>Create Destination
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
      const placeholder = document.getElementById('placeholderIcon');
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
          placeholder.classList.add('hidden');
        }
        reader.readAsDataURL(file);
      } else {
        preview.src = '#';
        preview.classList.add('hidden');
        placeholder.classList.remove('hidden');
      }
    }

    function validateForm() {
      const destination = document.getElementById('destination').value.trim();
      const description = document.getElementById('description').value.trim();
      const fileInput = document.getElementById('dest_image');
      
      // Basic validation
      if (!destination || !description) {
        alert('Please fill in all required fields.');
        return false;
      }
      
      // File validation if a file was selected
      if (fileInput.files.length > 0) {
        const file = fileInput.files[0];
        const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
        
        if (!validTypes.includes(file.type)) {
          alert('Only JPG, PNG, and GIF images are allowed.');
          return false;
        }
        
        if (file.size > 5 * 1024 * 1024) {
          alert('Image size must be less than 5MB.');
          return false;
        }
      }

      return true;
    }
  </script>
</body>
</html>