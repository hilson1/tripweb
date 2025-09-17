<?php
require '../connection.php';

// session_start();
// if (!isset($_SESSION['admin_id'])) {
//   header('location:adminlogin.php');
//   exit;
// }

$stmt = $conn->prepare("SELECT tripid, title FROM trips ORDER BY title ASC");
$stmt->execute();
$result = $stmt->get_result();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    // Get form data
    $tripid = intval($_POST['tripid']);
    $day = intval($_POST['day']);
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);

    // Insert user data into the database
    $sql = "INSERT INTO itinerary (tripid, day_number, title, description) VALUES (?,?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiss", $tripid, $day, $title, $description);
    if ($stmt->execute()) {
        echo "<script> alert('Itinerary Add Successful'); </script>";
    } else {
        echo "<script> alert('Itinerary add Failed!'); </script>";
    }
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="frontend/sidebar.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <?php
        include("frontend/header.php");
        ?>

        <?php
        include("frontend/sidebar.php");
        ?>
        <!-- Main Content -->
        <div class="ml-64 p-6 w-full mt-16">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-4"><br></h1>
                <div class="bg-gray-100 p-4 rounded-lg mb-6">
                    <h2 class="text-xl font-semibold mb-4">Create Itineary</h2>
                    <form method="post">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="Transportation" class="block text-sm font-medium text-gray-700 mb-1">Trip
                                    Name:
                                </label>
                                <select id="Transportation" name="tripid" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="" disabled selected>Select Trip</option>
                                    <?php if ($result->num_rows > 0) {
                                        while ($trip = $result->fetch_assoc()) {
                                            ?>
                                            <option value="<?php echo $trip["tripid"]; ?>"><?php echo $trip["title"]; ?>
                                            </option>
                                        <?php }
                                    } ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-700">Number of Day:</label>
                                <input type="number" name="day" class="w-full border border-gray-300 rounded-lg p-2"
                                    required>
                            </div>
                            <div>
                                <label class="block text-gray-700">Title:</label>
                                <input type="text" name="title" class="w-full border border-gray-300 rounded-lg p-2"
                                    required>
                            </div>
                            <div>
                                <label class="block text-gray-700">Description:</label>
                                <input type="text" name="description"
                                    class="w-full border border-gray-300 rounded-lg p-2" required>
                            </div>
                        </div>
                        <div class="md:col-span-2 flex justify-end space-x-4">
                            <button type="submit" class="bg-[#008080] text-white px-4 py-2 rounded">Create </button>
                            <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>