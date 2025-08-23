<?php
require 'frontend/connection.php';
// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $activityName = $_POST['act_name'];
    $activityDescription = $_POST['act_description'];
    $uploadDir = '../uploads/triptypes/';

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
                    return 'uploads/triptypes/' . $newFileName;
                }
            }
        }
        return null;
    }

    // Upload images
    $image1Path = uploadFile('act_image', $uploadDir);

    // Store in database
    if ($image1Path) {
        $stmt = $conn->prepare("INSERT INTO triptypes (triptype, description, main_image) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $activityName, $activityDescription, $image1Path);

        if ($stmt->execute()) {
            echo "<script>alert('TripType uploaded successfully!');</script>";
            echo "<script> window.location.href = 'alltriptype.php'; </script>";
        } else {
            echo "<script>alert('Failed to save image paths in the database.');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Images failed to upload.');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="frontend/sidebar.css" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        /* Form styling to match edit trips page */
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        
        .form-label {
            @apply block text-md font-semibold text-gray-700;
        }
        
        .form-input, .form-textarea {
            @apply w-full px-4 py-3 border border-gray-300 rounded-lg 
                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                   transition-colors duration-200 bg-white;
        }
        
        .btn-primary {
            @apply px-8 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white 
                   font-medium rounded-lg hover:from-blue-600 hover:to-blue-700 
                   transition-all duration-200 transform hover:scale-105 shadow-lg;
        }
        
        .btn-secondary {
            @apply bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 
                   rounded-lg transition-colors duration-200 backdrop-blur-sm;
        }
        
        
        .section-header {
            @apply text-xl font-semibold text-gray-800 border-b border-gray-200 pb-2 mb-6;
        }
        
        /* Icon colors */
        .icon-blue { @apply text-blue-500; }
        .icon-green { @apply text-green-500; }
        .icon-purple { @apply text-purple-500; }
        .icon-orange { @apply text-orange-500; }
        .icon-pink { @apply text-pink-500; }
        
        /* Image upload styling */
        .image-upload-zone {
            @apply flex flex-col items-center justify-center w-full h-48 
                   border-2 border-gray-300 border-dashed rounded-lg cursor-pointer 
                   bg-gray-50 hover:bg-gray-100 transition-all duration-200;
        }
        
        .image-upload-zone:hover {
            @apply border-blue-400 bg-blue-50;
        }
        
        .upload-icon {
            @apply w-12 h-12 mb-4 text-gray-400;
        }
        
        .upload-text {
            @apply mb-2 text-sm text-gray-600;
        }
        
        .upload-subtext {
            @apply text-xs text-gray-500;
        }
        
        /* Message styling */
        .message-success {
            @apply bg-green-50 border-green-400 text-green-700 rounded-lg border px-6 py-4 
                   transition-all duration-300 transform hover:scale-[1.02] shadow-lg;
        }
        
        .message-error {
            @apply bg-red-50 border-red-400 text-red-700 rounded-lg border px-6 py-4 
                   transition-all duration-300 transform hover:scale-[1.02] shadow-lg;
        }
    </style>
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="flex h-screen">
        <?php include("frontend/header.php"); ?>
        
        <!-- Sidebar -->
        <?php include("frontend/sidebar.php"); ?>
        
        <!-- Main Content -->
        <main class="main-content pt-16 min-h-screen transition-all duration-300">
            <div class="p-6">
                <!-- Header Section -->
                <div class="mb-8">
                    <div class="gradient-bg rounded-2xl p-6 text-white">
                        <div class="flex justify-between items-center">
                            <div>
                                <h1 class="text-3xl font-bold mb-2">
                                    <i class="fas fa-plus mr-3"></i>Add New Trip Type
                                </h1>
                                <p class="text-blue-100">Create a new trip type category for your travel offerings</p>
                            </div>
                            <div class="text-right">
                                <a href="alltriptype.php" class="btn-secondary">
                                    <i class="fas fa-arrow-left mr-2"></i>Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Section -->
                <div class="glass-effect rounded-2xl shadow-xl p-8">
                    <form method="post" enctype="multipart/form-data" class="space-y-8">
                        
                        <!-- Basic Information Section -->
                        <div class="space-y-6">
                            <h3 class="section-header">
                                <i class="fas fa-info-circle mr-2 icon-blue"></i>Trip Type Information
                            </h3>
                            
                            <div class="form-field">
                                <label for="act_name" class="form-label">
                                    <label class="form-label"></i>Trip Type Name
                                </label>
                                <input type="text" id="act_name" name="act_name" required
                                    class="form-input"
                                    placeholder="Enter trip type name (e.g., Adventure, Cultural, Relaxation)">
                            </div>
                            
                            <div class="form-field">
                                <label for="act_description" class="form-label">
                                    <label class="form-label"></i>Trip Type Description
                                </label>
                                <textarea id="act_description" name="act_description" rows="5" required
                                    class="form-input"
                                    placeholder="Provide a detailed description of this trip type, what it includes, and what makes it unique..."></textarea>
                            </div>
                        </div>

                        <!-- Image Upload Section -->
                        <div class="space-y-6">
                            <h3 class="section-header">
                                <i class="fas fa-image mr-2 icon-pink"></i>Trip Type Image
                            </h3>
                            
                            <div class="form-input">
                                <label for="act_image" class="form-label">
                                    <i class="fas fa-camera mr-2 icon-orange"></i>Upload Representative Image
                                </label>
                                <div class="image-upload-zone" onclick="document.getElementById('act_image').click()">
                                    <div id="upload-content" class="flex flex-col items-center justify-center py-6">
                                        <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                        <p class="upload-text">
                                            <span class="font-semibold text-blue-600">Click to upload</span> 
                                            or drag and drop
                                        </p>
                                        <p class="upload-subtext">PNG, JPG or GIF (MAX. 2MB)</p>
                                    </div>
                                    <div id="image-preview" class="w-full h-full hidden"></div>
                                </div>
                                <input id="act_image" name="act_image" type="file" class="hidden" accept="image/*" />
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end pt-6 border-t border-gray-200">
                            <div class="flex space-x-4">
                                <a href="alltriptype.php">
                                    <button type="button" class="btn-secondary">
                                        <i class="fas fa-times mr-2"></i>Cancel
                                    </button>
                                </a>
                                <button type="submit" class="btn-primary">
                                    <i class="fas fa-save mr-2"></i>Create Trip Type
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <!-- Success/Error Messages -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="fixed top-4 right-4 z-50 max-w-md">
            <div class="<?= strpos($_SESSION['message'], 'Error') === 0 ? 'message-error' : 'message-success' ?>" role="alert">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <?php if (strpos($_SESSION['message'], 'Error') === 0): ?>
                            <i class="fas fa-exclamation-circle w-6 h-6 text-red-600"></i>
                        <?php else: ?>
                            <i class="fas fa-check-circle w-6 h-6 text-green-600"></i>
                        <?php endif; ?>
                    </div>
                    <div class="ml-3">
                        <p class="font-medium"><?= htmlspecialchars($_SESSION['message']) ?></p>
                    </div>
                    <div class="ml-auto pl-3">
                        <button onclick="this.parentElement.parentElement.parentElement.remove()" 
                                class="inline-flex rounded-md p-1.5 hover:bg-gray-100 focus:outline-none">
                            <i class="fas fa-times w-4 h-4"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <script>
        // Enhanced image preview functionality
        document.getElementById('act_image').addEventListener('change', function (e) {
            const file = e.target.files[0];
            const preview = document.getElementById('image-preview');
            const uploadContent = document.getElementById('upload-content');

            if (file) {
                // Validate file size (2MB limit)
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size must be less than 2MB');
                    this.value = '';
                    return;
                }

                // Validate file type
                if (!file.type.match('image.*')) {
                    alert('Please select a valid image file');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.innerHTML = `
                        <div class="relative w-full h-full">
                            <img src="${e.target.result}" class="w-full h-full object-cover rounded-lg" alt="Preview">
                            <div class="absolute top-2 right-2">
                                <button type="button" onclick="clearImagePreview()" 
                                        class="bg-red-500 hover:bg-red-600 text-white rounded-full w-8 h-8 flex items-center justify-center transition-colors duration-200">
                                    <i class="fas fa-times text-sm"></i>
                                </button>
                            </div>
                            <div class="absolute bottom-2 left-2 bg-black bg-opacity-50 text-white px-2 py-1 rounded text-xs">
                                ${file.name}
                            </div>
                        </div>
                    `;
                    preview.classList.remove('hidden');
                    uploadContent.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        // Function to clear image preview
        function clearImagePreview() {
            const preview = document.getElementById('image-preview');
            const uploadContent = document.getElementById('upload-content');
            const fileInput = document.getElementById('act_image');
            
            preview.innerHTML = '';
            preview.classList.add('hidden');
            uploadContent.classList.remove('hidden');
            fileInput.value = '';
        }

        // Auto-dismiss messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const messages = document.querySelectorAll('.message-success, .message-error');
            messages.forEach(function(message) {
                setTimeout(function() {
                    message.style.transform = 'translateX(100%)';
                    setTimeout(function() {
                        message.remove();
                    }, 300);
                }, 5000);
            });
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const tripTypeName = document.getElementById('act_name').value.trim();
            const description = document.getElementById('act_description').value.trim();
            
            if (tripTypeName.length < 3) {
                alert('Trip type name must be at least 3 characters long');
                e.preventDefault();
                return;
            }
            
            if (description.length < 10) {
                alert('Description must be at least 10 characters long');
                e.preventDefault();
                return;
            }
        });
    </script>
</body>

</html>