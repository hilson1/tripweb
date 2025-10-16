<?php
include __DIR__ . '/auth-check.php';
require '../connection.php';
session_start();

$destination = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $destination_name = htmlspecialchars(trim($_POST['destination']), ENT_QUOTES, 'UTF-8');
        $description = htmlspecialchars(trim($_POST['description']), ENT_QUOTES, 'UTF-8');
        $original_name = htmlspecialchars(trim($_POST['original_name']), ENT_QUOTES, 'UTF-8');
        $current_image = htmlspecialchars(trim($_POST['current_image']), ENT_QUOTES, 'UTF-8');

        if (empty($destination_name)) throw new Exception("Error: Destination name is required");
        if (empty($description)) throw new Exception("Error: Destination description is required");

        $uploadDir = __DIR__ . '/assets/destinations/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $targetFile = $current_image;
        if (!empty($_FILES['dest_image']['name'])) {
            $fileName = basename($_FILES["dest_image"]["name"]);
            $newFileName = uniqid() . '_' . $fileName;
            $targetFilePath = $uploadDir . $newFileName;
            $relativePath = 'assets/destinations/' . $newFileName;
            $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($imageFileType, $allowedTypes)) throw new Exception("Error: Invalid file type.");
            if ($_FILES["dest_image"]["size"] > 5000000) throw new Exception("Error: File too large.");

            if (!move_uploaded_file($_FILES["dest_image"]["tmp_name"], $targetFilePath)) throw new Exception("Error uploading image.");

            if (!empty($current_image) && file_exists(__DIR__ . '/../' . $current_image)) unlink(__DIR__ . '/../' . $current_image);
            $targetFile = $relativePath;
        }

        $stmt = $conn->prepare("UPDATE destinations SET distination = ?, description = ?, main_image = ? WHERE distination = ?");
        $stmt->bind_param("ssss", $destination_name, $description, $targetFile, $original_name);
        if (!$stmt->execute()) throw new Exception("Error: " . $stmt->error);

        $_SESSION['message'] = "Success: Destination updated successfully!";
        header("Location: alldestination");
        exit();
    } catch (Exception $e) {
        $_SESSION['message'] = $e->getMessage();
        header("Location: editdestination?name=" . urlencode($destination_name));
        exit();
    }
} else {
    if (isset($_GET['name'])) {
        $destination_name = htmlspecialchars(trim($_GET['name']), ENT_QUOTES, 'UTF-8');
        $stmt = $conn->prepare("SELECT * FROM destinations WHERE distination = ?");
        $stmt->bind_param("s", $destination_name);
        $stmt->execute();
        $result = $stmt->get_result();
        $destination = $result->fetch_assoc();
        if (!$destination) {
            $_SESSION['message'] = "Error: Destination not found";
            header("Location: alldestination");
            exit();
        }
    } else {
        $_SESSION['message'] = "Error: Destination name not specified";
        header("Location: alldestination");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Edit Destination - ThankYouNepalTrip</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="frontend/sidebar.css">
</head>

<body class="bg-gray-50 font-sans leading-normal tracking-normal" x-data="{ sidebarOpen: false }">
  <?php include 'frontend/header.php'; ?>
  <?php include 'frontend/sidebar.php'; ?>

  <main class="main-content pt-16 min-h-screen transition-all duration-300">
    <div class="p-6">
      <div class="bg-white rounded-xl shadow-md p-6">
        <div class="mb-8">
          <div class="gradient-bg rounded-2xl p-6 text-white flex justify-between items-center">
            <div>
              <h1 class="text-3xl font-bold mb-2"><i class="fas fa-map-marker-alt mr-3"></i>Edit Destination</h1>
              <p class="text-blue-100">Update destination information</p>
            </div>
            <a href="alldestination" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-6 py-2 rounded-lg flex items-center transition-all">
              <i class="fas fa-arrow-left mr-2"></i>Back to List
            </a>
          </div>
        </div>

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

        <form action="editdestination" method="POST" enctype="multipart/form-data" class="space-y-6">
          <input type="hidden" name="original_name" value="<?= htmlspecialchars($destination['distination']) ?>">
          <input type="hidden" name="current_image" value="<?= htmlspecialchars($destination['main_image']) ?>">

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
              <label class="block text-sm font-semibold text-gray-700">
                <i class="fas fa-tag mr-2 text-blue-500"></i>Destination Name
              </label>
              <input type="text" name="destination" value="<?= htmlspecialchars($destination['distination']) ?>"
                     class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 transition" required>
            </div>

            <div class="space-y-2 md:col-span-2">
              <label class="block text-sm font-semibold text-gray-700">
                <i class="fas fa-align-left mr-2 text-blue-500"></i>Description
              </label>
              <textarea name="description" rows="4" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 transition"><?= htmlspecialchars($destination['description']) ?></textarea>
            </div>

            <div class="col-span-2 space-y-4">
              <label class="block text-sm font-semibold text-gray-700">
                <i class="fas fa-image mr-2 text-blue-500"></i>Destination Image
              </label>
              <div class="flex items-start gap-6">
                <div id="current-image" class="w-48">
                  <img src="<?php echo '../' . $destination['main_image']; ?>" class="max-h-48 w-auto object-cover rounded-lg border shadow-md">
                  <span class="text-xs text-gray-500 block mt-2 text-center">Current Image</span>
                </div>

                <div class="flex-1">
                  <label class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                      <svg class="w-10 h-10 mb-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5A5.5 5.5 0 0 0 5.207 5.021A4 4 0 0 0 5 13h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                      </svg>
                      <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span></p>
                      <p class="text-xs text-gray-500">PNG, JPG or GIF (MAX. 5MB)</p>
                    </div>
                    <input id="dest_image" name="dest_image" type="file" class="hidden" accept="image/*">
                  </label>
                  <div id="image-preview" class="mt-4"></div>
                </div>
              </div>
            </div>

            <div class="col-span-2 flex justify-end gap-4 pt-6">
              <a href="alldestination" class="px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
                <i class="fas fa-times mr-2"></i>Cancel
              </a>
              <button type="submit"
                      class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-lg shadow-lg font-medium transition">
                <i class="fas fa-save mr-2"></i>Update Destination
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <script>
    document.getElementById('dest_image').addEventListener('change', function(e) {
      const file = e.target.files[0];
      const preview = document.getElementById('image-preview');
      const currentImage = document.getElementById('current-image');
      if (file) {
        const reader = new FileReader();
        reader.onload = e => {
          preview.innerHTML = `<img src="${e.target.result}" class="max-h-48 w-auto object-cover rounded-lg shadow-md">`;
          currentImage.classList.add('hidden');
        };
        reader.readAsDataURL(file);
      }
    });
  </script>
</body>
</html>
