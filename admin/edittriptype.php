<?php
include __DIR__ . '/auth-check.php';
require '../connection.php';
session_start();

$triptype = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $triptype_name = htmlspecialchars(trim($_POST['trip_name']), ENT_QUOTES, 'UTF-8');
        $triptype_desc = htmlspecialchars(trim($_POST['trip_desc']), ENT_QUOTES, 'UTF-8');
        $original_name = htmlspecialchars(trim($_POST['original_name']), ENT_QUOTES, 'UTF-8');
        $current_image = htmlspecialchars(trim($_POST['current_image']), ENT_QUOTES, 'UTF-8');

        if (empty($triptype_name)) throw new Exception("Error: Triptype name is required");
        if (empty($triptype_desc)) throw new Exception("Error: Triptype description is required");

        // ✅ Correct upload folder (one level above /admin/)
        $uploadDir = __DIR__ . '/../assets/triptype/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $targetFile = $current_image;

        // ✅ Handle image upload
        if (!empty($_FILES['triptype_image']['name'])) {
            $fileName = basename($_FILES["triptype_image"]["name"]);
            $newFileName = uniqid() . '_' . $fileName;
            $targetFilePath = $uploadDir . $newFileName;
            $relativePath = 'assets/triptype/' . $newFileName;
            $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($imageFileType, $allowedTypes)) {
                throw new Exception("Error: Only JPG, JPEG, PNG & GIF files are allowed.");
            }

            if ($_FILES["triptype_image"]["size"] > 5000000) {
                throw new Exception("Error: File is too large. Max 5MB allowed.");
            }

            // ✅ Move uploaded file
            if (!move_uploaded_file($_FILES["triptype_image"]["tmp_name"], $targetFilePath)) {
                throw new Exception("Error: Unable to save uploaded file. Check folder permissions.");
            }

            // ✅ Delete old image if exists
            if (!empty($current_image) && file_exists(__DIR__ . '/../' . $current_image)) {
                unlink(__DIR__ . '/../' . $current_image);
            }

            // ✅ Save new image path for DB
            $targetFile = $relativePath;
        }

        // ✅ Update database record
        $stmt = $conn->prepare("UPDATE triptypes SET triptype = ?, description = ?, main_image = ? WHERE triptype = ?");
        $stmt->bind_param("ssss", $triptype_name, $triptype_desc, $targetFile, $original_name);

        if (!$stmt->execute()) {
            throw new Exception("Error: " . $stmt->error);
        }

        $_SESSION['message'] = "Success: Triptype updated successfully!";
        header("Location: alltriptype");
        exit();

    } catch (Exception $e) {
        $_SESSION['message'] = $e->getMessage();
        header("Location: edittriptype?name=" . urlencode($triptype_name));
        exit();
    }
} else {
    if (isset($_GET['name'])) {
        $triptype_name = htmlspecialchars(trim($_GET['name']), ENT_QUOTES, 'UTF-8');
        $stmt = $conn->prepare("SELECT * FROM triptypes WHERE triptype = ?");
        $stmt->bind_param("s", $triptype_name);
        $stmt->execute();
        $result = $stmt->get_result();
        $triptype = $result->fetch_assoc();

        if (!$triptype) {
            $_SESSION['message'] = "Error: Triptype not found";
            header("Location: alltriptype");
            exit();
        }
    } else {
        $_SESSION['message'] = "Error: Triptype name not specified";
        header("Location: alltriptype");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Edit Trip-Type - ThankYouNepalTrip</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- FontAwesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="frontend/sidebar.css">
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="bg-gray-50 font-sans leading-normal tracking-normal" x-data="{ sidebarOpen: false }">
  <div class="overlay" :class="{ 'open': sidebarOpen }" @click="sidebarOpen = false"></div>

  <?php include 'frontend/header.php'; ?>
  <?php include 'frontend/sidebar.php'; ?>

  <main class="main-content pt-16 min-h-screen transition-all duration-300">
    <div class="p-6">
      <div class="bg-white rounded-xl shadow-md p-6">
        <div class="mb-8">
          <div class="gradient-bg rounded-2xl p-6 text-white">
            <div class="flex justify-between items-center">
              <div>
                <h1 class="text-3xl font-bold mb-2">
                  <i class="fas fa-edit mr-3"></i>Edit Trip Type
                </h1>
                <p class="text-blue-100">Update trip type information</p>
              </div>
              <a href="alltriptype"
                 class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-6 py-2 rounded-lg flex items-center transition-all duration-300">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to List
              </a>
            </div>
          </div>
        </div>

        <!-- ✅ Message block -->
        <?php if (isset($_SESSION['message'])): ?>
          <div class="mb-6">
            <div class="<?php echo (strpos($_SESSION['message'], 'Error') !== false) ? 'bg-red-100 text-red-700 border-red-400' : 'bg-green-100 text-green-700 border-green-400'; ?> border rounded-lg px-4 py-3 shadow-sm">
              <div class="flex justify-between items-center">
                <p class="font-medium"><?php echo htmlspecialchars($_SESSION['message']); ?></p>
                <button onclick="this.parentElement.parentElement.remove();" class="text-gray-500 hover:text-gray-700">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </div>
          <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <div class="glass-effect rounded-2xl shadow-xl p-6">
          <form action="edittriptype" method="POST" enctype="multipart/form-data" class="space-y-6">
            <input type="hidden" name="original_name" value="<?= htmlspecialchars($triptype['triptype']) ?>">
            <input type="hidden" name="current_image" value="<?= htmlspecialchars($triptype['main_image']) ?>">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">
                  <i class="fas fa-tag mr-2 text-blue-500"></i>Trip Type Name
                </label>
                <input type="text" name="trip_name" value="<?= htmlspecialchars($triptype['triptype']) ?>"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300" required>
              </div>

              <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">
                  <i class="fas fa-align-left mr-2 text-blue-500"></i>Description
                </label>
                <textarea name="trip_desc" rows="4"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                          required><?= htmlspecialchars($triptype['description']) ?></textarea>
              </div>

              <div class="col-span-2 space-y-4">
                <label class="block text-sm font-semibold text-gray-700">
                  <i class="fas fa-image mr-2 text-blue-500"></i>Trip Type Image
                </label>
                <div class="flex items-start gap-6">
                  <div id="current-image" class="w-48">
                    <img src="<?php echo '../' . $triptype['main_image']; ?>" class="max-h-48 w-auto object-cover rounded-lg border shadow-md">
                    <span class="text-xs text-gray-500 block mt-2 text-center">Current Image</span>
                  </div>

                  <div class="flex-1">
                    <label class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors duration-300">
                      <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-10 h-10 mb-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                        </svg>
                        <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span></p>
                        <p class="text-xs text-gray-500">PNG, JPG or GIF (MAX. 5MB)</p>
                      </div>
                      <input id="triptype_image" name="triptype_image" type="file" class="hidden" accept="image/*">
                    </label>
                    <div id="image-preview" class="mt-4"></div>
                  </div>
                </div>
              </div>

              <div class="col-span-2 flex justify-end gap-4 pt-6">
                <a href="alltriptype"
                   class="px-6 py-3 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-300">
                  <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-lg shadow-lg transition-all duration-300 font-medium">
                  <i class="fas fa-save mr-2"></i>Update Trip Type
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('triptype_image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('image-preview');
            const currentImage = document.getElementById('current-image');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" class="max-h-48 w-auto object-cover rounded-lg shadow-md">`;
                    currentImage.classList.add('hidden');
                }
                reader.readAsDataURL(file);
            }
        });
    });
  </script>
</body>
</html>
