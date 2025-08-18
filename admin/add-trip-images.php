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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

            dropdownToggles.forEach(toggle => {
                toggle.addEventListener('click', function (event) {
                    event.preventDefault();

                    // Close all dropdowns
                    document.querySelectorAll('.dropdown-menu').forEach(menu => {
                        if (menu !== this.nextElementSibling) {
                            menu.classList.add('hidden');
                        }
                    });

                    // Toggle current dropdown
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
        <?php
        include("frontend/asidebar.php");
        ?>
        <!-- main section -->
        <div class="ml-64 p-6 w-full mt-16">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Add Trip Image</h2>
                <form class="grid grid-cols-1 md:grid-cols-2 gap-4" method="post" enctype="multipart/form-data">
                    <div class="mb-6">
                        <label for="Transportation" class="block text-sm font-medium text-gray-700 mb-1">Trip Name:
                        </label>
                        <select id="Transportation" name="tripid" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="" disabled selected>Select Trip</option>
                            <?php if ($result->num_rows > 0) {
                                while ($trip = $result->fetch_assoc()) {
                                    ?>
                                    <option value="<?php echo $trip["tripid"]; ?>"><?php echo $trip["title"]; ?></option>
                                <?php }
                            } ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700">Main Image:</label>
                        <input type="file" name="image1" id="image1" class="w-full p-2 border border-gray-300 rounded"
                            required>
                    </div>
                    <div>
                        <label class="block text-gray-700">Side Image 1:</label>
                        <input type="file" name="image2" id="image2" class="w-full p-2 border border-gray-300 rounded"
                            required>
                    </div>
                    <div>
                        <label class="block text-gray-700">Side Image 2:</label>
                        <input type="file" name="image3" id="image3" class="w-full p-2 border border-gray-300 rounded"
                            required>
                    </div>
                    <div class="md:col-span-2 flex justify-end space-x-4">
                        <input type="submit" class="bg-[#008080] text-white px-4 py-2 rounded" value="Insert">
                        <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</body>

</html>