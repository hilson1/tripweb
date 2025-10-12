<?php
require 'frontend/connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // ✅ Sanitize form inputs
        $activityName = htmlspecialchars(trim($_POST['act_name']), ENT_QUOTES, 'UTF-8');
        $activityDescription = htmlspecialchars(trim($_POST['act_description']), ENT_QUOTES, 'UTF-8');

        if (empty($activityName)) throw new Exception("Error: Trip Type Name is required.");
        if (empty($activityDescription)) throw new Exception("Error: Trip Type Description is required.");

        // ✅ Upload directory (assuming this file is inside admin/)
        $uploadDir = __DIR__ . '/../assets/triptype/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $image1Path = '';

        // ✅ Handle image upload
        if (!empty($_FILES['act_image']['name'])) {
            $fileTmp = $_FILES['act_image']['tmp_name'];
            $fileName = basename($_FILES['act_image']['name']);
            $fileSize = $_FILES['act_image']['size'];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];

            if (!in_array($fileExt, $allowed)) {
                throw new Exception("Error: Only JPG, JPEG, PNG & GIF files are allowed.");
            }

            if ($fileSize > 5000000) {
                throw new Exception("Error: File is too large. Max 5MB allowed.");
            }

            if (getimagesize($fileTmp) === false) {
                throw new Exception("Error: Invalid image file.");
            }

            // ✅ Generate unique filename and move file
            $newFileName = uniqid('triptype_', true) . '.' . $fileExt;
            $fileDestination = $uploadDir . $newFileName;

            if (!move_uploaded_file($fileTmp, $fileDestination)) {
                throw new Exception("Error: Failed to upload image. Check folder permissions.");
            }

            // ✅ Save relative path for frontend use
            $image1Path = 'assets/triptype/' . $newFileName;
        } else {
            throw new Exception("Error: Please upload a representative image.");
        }

        // ✅ Insert into database
        $stmt = $conn->prepare("INSERT INTO triptypes (triptype, description, main_image) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $activityName, $activityDescription, $image1Path);

        if (!$stmt->execute()) {
            throw new Exception("Error: Database insert failed. " . $stmt->error);
        }

        $_SESSION['message'] = "Success: Trip Type added successfully!";
        header("Location: alltriptype.php");
        exit();

    } catch (Exception $e) {
        $_SESSION['message'] = $e->getMessage();
        header("Location: createtriptype.php");
        exit();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Trip Type - ThankYouNepalTrip</title>

    <!-- Tailwind CSS & Icons -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="frontend/sidebar.css" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="flex h-screen">
        <?php include("frontend/header.php"); ?>
        <?php include("frontend/sidebar.php"); ?>

        <main class="main-content pt-16 min-h-screen transition-all duration-300">
            <div class="p-6">

                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl p-6 text-white flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">
                            <i class="fas fa-plus mr-3"></i>Add New Trip Type
                        </h1>
                        <p class="text-blue-100">Create a new trip type category for your travel offerings</p>
                    </div>
                    <a href="alltriptype.php" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition">
                        <i class="fas fa-arrow-left mr-2"></i>Back to List
                    </a>
                </div>

                <!-- Alert -->
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="mt-6">
                        <div class="<?php echo (strpos($_SESSION['message'], 'Error') !== false) ? 'bg-red-100 text-red-700 border-red-400' : 'bg-green-100 text-green-700 border-green-400'; ?> border rounded-lg px-4 py-3 shadow-sm">
                            <?php echo htmlspecialchars($_SESSION['message']); ?>
                        </div>
                    </div>
                    <?php unset($_SESSION['message']); ?>
                <?php endif; ?>

                <!-- Form -->
                <div class="mt-8 bg-white rounded-2xl shadow-xl p-8">
                    <form method="post" enctype="multipart/form-data" class="space-y-8">
                        <div>
                            <label class="block text-sm font-semibold mb-2">Trip Type Name</label>
                            <input type="text" name="act_name" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Adventure, Cultural, etc.">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2">Description</label>
                            <textarea name="act_description" rows="4" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Describe what this trip type includes..."></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2">Upload Image</label>
                            <input type="file" name="act_image" accept="image/*" required class="block w-full text-sm text-gray-600 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                        </div>
                        <div class="flex justify-end space-x-4">
                            <a href="alltriptype.php" class="px-6 py-3 border border-gray-300 rounded-lg text-sm text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fas fa-times mr-2"></i>Cancel
                            </a>
                            <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                <i class="fas fa-save mr-2"></i>Create
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
