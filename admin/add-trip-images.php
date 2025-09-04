<?php
require 'frontend/connection.php';

$stmt = $conn->prepare("SELECT * FROM trips");
$stmt->execute();
$result = $stmt->get_result();

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tripid = $_POST['tripid'];
    $uploadDir = '../uploads/tripimg/';

    // Upload function with image validation
    function uploadFile($fileInputName, $uploadDir)
    {
        if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === UPLOAD_ERR_OK) {
            $fileTmp = $_FILES[$fileInputName]['tmp_name'];
            $fileName = basename($_FILES[$fileInputName]['name']);
            $fileSize = $_FILES[$fileInputName]['size'];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png'];
            // Validate extension and size
            if (in_array($fileExt, $allowed) && $fileSize < 5000000) {
                // Optional: check if it's a valid image
                if (getimagesize($fileTmp) === false) {
                    return null;
                }
                $newFileName = uniqid('', true) . '.' . $fileExt;
                $fileDestination = $uploadDir . $newFileName;

                if (move_uploaded_file($fileTmp, $fileDestination)) {
                    return 'uploads/tripimg/' . $newFileName;
                }
            }
        }
        return null;
    }

    // Upload images
    $image1Path = uploadFile('image1', $uploadDir);
    $image2Path = uploadFile('image2', $uploadDir);
    $image3Path = uploadFile('image3', $uploadDir);

    // Store in database
    if ($image1Path && $image2Path && $image3Path) {
        $stmt = $conn->prepare("INSERT INTO trip_images (tripid, main_image, side_image1, side_image2) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $tripid, $image1Path, $image2Path, $image3Path);

        if ($stmt->execute()) {
            echo "<script>alert('Images uploaded successfully!');</script>";
        } else {
            echo "<script>alert('Failed to save image paths in the database.');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('One or more images failed to upload.');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Add Trip Images - ThankYouNepalTrip</title>

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
      <!-- Add Trip Images Content -->
      <div class="bg-white rounded-xl shadow-md p-6">
        <!-- Header Section -->
        <div class="mb-8">
          <div class="gradient-bg rounded-2xl p-6 text-white">
            <div class="flex justify-between items-center">
              <div>
                <h1 class="text-3xl font-bold mb-2">
                  <i class="fas fa-images mr-3"></i>Add Trip Images
                </h1>
                <p class="text-blue-100">Upload images for trip destinations</p>
              </div>
              <div class="text-right">
                <div class="text-blue-100">
                  <i class="fas fa-upload text-2xl"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Form Section -->
        <div class="glass-effect rounded-2xl shadow-xl p-8">
          <form method="post" enctype="multipart/form-data" class="space-y-6">
            <!-- Trip Selection -->
            <div class="mb-8">
              <label for="tripid" class="block text-sm font-semibold text-gray-700 mb-3">
                <i class="fas fa-map-marked-alt mr-2 text-blue-500"></i>Select Trip
              </label>
              <select id="tripid" name="tripid" required
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                <option value="" disabled selected>Choose a trip to add images</option>
                <?php if ($result->num_rows > 0) {
                    while ($trip = $result->fetch_assoc()) { ?>
                        <option value="<?php echo $trip["tripid"]; ?>"><?php echo htmlspecialchars($trip["title"]); ?></option>
                <?php }
                } ?>
              </select>
            </div>

            <!-- Image Upload Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
              <!-- Main Image -->
              <div class="space-y-4">
                <label class="block text-sm font-semibold text-gray-700">
                  <i class="fas fa-image mr-2 text-green-500"></i>Main Image
                </label>
                <div class="relative">
                  <input type="file" name="image1" id="image1" 
                         class="hidden" 
                         accept="image/jpeg,image/jpg,image/png" 
                         required
                         onchange="previewImage(this, 'preview1')">
                  <label for="image1" 
                         class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors duration-200">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6" id="upload-area-1">
                      <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                      <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> main image</p>
                      <p class="text-xs text-gray-500">PNG, JPG or JPEG (MAX. 5MB)</p>
                    </div>
                    <img id="preview1" class="hidden w-full h-full object-cover rounded-lg" alt="Preview">
                  </label>
                </div>
              </div>

              <!-- Side Image 1 -->
              <div class="space-y-4">
                <label class="block text-sm font-semibold text-gray-700">
                  <i class="fas fa-image mr-2 text-yellow-500"></i>Side Image 1
                </label>
                <div class="relative">
                  <input type="file" name="image2" id="image2" 
                         class="hidden" 
                         accept="image/jpeg,image/jpg,image/png" 
                         required
                         onchange="previewImage(this, 'preview2')">
                  <label for="image2" 
                         class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors duration-200">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6" id="upload-area-2">
                      <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                      <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> side image</p>
                      <p class="text-xs text-gray-500">PNG, JPG or JPEG (MAX. 5MB)</p>
                    </div>
                    <img id="preview2" class="hidden w-full h-full object-cover rounded-lg" alt="Preview">
                  </label>
                </div>
              </div>

              <!-- Side Image 2 -->
              <div class="space-y-4">
                <label class="block text-sm font-semibold text-gray-700">
                  <i class="fas fa-image mr-2 text-purple-500"></i>Side Image 2
                </label>
                <div class="relative">
                  <input type="file" name="image3" id="image3" 
                         class="hidden" 
                         accept="image/jpeg,image/jpg,image/png" 
                         required
                         onchange="previewImage(this, 'preview3')">
                  <label for="image3" 
                         class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors duration-200">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6" id="upload-area-3">
                      <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                      <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> side image</p>
                      <p class="text-xs text-gray-500">PNG, JPG or JPEG (MAX. 5MB)</p>
                    </div>
                    <img id="preview3" class="hidden w-full h-full object-cover rounded-lg" alt="Preview">
                  </label>
                </div>
              </div>
            </div>

            <!-- Upload Guidelines -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-8">
              <h3 class="text-sm font-semibold text-blue-800 mb-2">
                <i class="fas fa-info-circle mr-2"></i>Upload Guidelines
              </h3>
              <ul class="text-sm text-blue-700 space-y-1">
                <li><i class="fas fa-check mr-2 text-green-500"></i>Accepted formats: JPG, JPEG, PNG</li>
                <li><i class="fas fa-check mr-2 text-green-500"></i>Maximum file size: 5MB per image</li>
                <li><i class="fas fa-check mr-2 text-green-500"></i>Recommended dimensions: 1920x1080 pixels</li>
                <li><i class="fas fa-check mr-2 text-green-500"></i>All three images are required</li>
              </ul>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
              <button type="button" 
                      onclick="window.location.href='alltrip.php'" 
                      class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors duration-200">
                <i class="fas fa-times mr-2"></i>Cancel
              </button>
              <button type="submit" 
                      class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 transform hover:scale-105 shadow-lg">
                <i class="fas fa-upload mr-2"></i>Upload Images
              </button>
            </div>
          </form>
        </div>
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
          // Close sidebar on mobile by default
          if (window.innerWidth < 1024) {
            this.sidebarOpen = false;
          }
          
          // Update state when window is resized
          window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
              this.sidebarOpen = true;
            }
          });
        }
      }));
    });

    // Image preview function
    function previewImage(input, previewId) {
      const file = input.files[0];
      const preview = document.getElementById(previewId);
      const uploadArea = preview.previousElementSibling;
      
      if (file) {
        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!allowedTypes.includes(file.type)) {
          alert('Please select a valid image file (JPG, JPEG, or PNG)');
          input.value = '';
          return;
        }
        
        // Validate file size (5MB)
        if (file.size > 5000000) {
          alert('File size must be less than 5MB');
          input.value = '';
          return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
          preview.src = e.target.result;
          preview.classList.remove('hidden');
          uploadArea.classList.add('hidden');
        };
        reader.readAsDataURL(file);
      } else {
        preview.classList.add('hidden');
        uploadArea.classList.remove('hidden');
      }
    }

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
      const tripSelect = document.getElementById('tripid');
      const image1 = document.getElementById('image1');
      const image2 = document.getElementById('image2');
      const image3 = document.getElementById('image3');
      
      if (!tripSelect.value) {
        alert('Please select a trip');
        e.preventDefault();
        return;
      }
      
      if (!image1.files[0] || !image2.files[0] || !image3.files[0]) {
        alert('Please upload all three images');
        e.preventDefault();
        return;
      }
      
      // Show loading state
      const submitBtn = this.querySelector('button[type="submit"]');
      const originalText = submitBtn.innerHTML;
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Uploading...';
      submitBtn.disabled = true;
      
      // Re-enable button after 30 seconds (fallback)
      setTimeout(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
      }, 30000);
    });

    // Drag and drop functionality
    function setupDragAndDrop(labelElement, inputElement, previewId) {
      labelElement.addEventListener('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        this.classList.add('border-blue-500', 'bg-blue-50');
      });

      labelElement.addEventListener('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        this.classList.remove('border-blue-500', 'bg-blue-50');
      });

      labelElement.addEventListener('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        this.classList.remove('border-blue-500', 'bg-blue-50');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
          inputElement.files = files;
          previewImage(inputElement, previewId);
        }
      });
    }

    // Initialize drag and drop for all upload areas
    document.addEventListener('DOMContentLoaded', function() {
      setupDragAndDrop(document.querySelector('label[for="image1"]'), document.getElementById('image1'), 'preview1');
      setupDragAndDrop(document.querySelector('label[for="image2"]'), document.getElementById('image2'), 'preview2');
      setupDragAndDrop(document.querySelector('label[for="image3"]'), document.getElementById('image3'), 'preview3');
    });
  </script>
</body>
</html>