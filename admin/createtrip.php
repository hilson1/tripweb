<?php
require 'frontend/connection.php'; // Reuse your DB connection file

$tripTypesQuery = "SELECT triptype_id, triptype, description FROM triptypes";
$tripTypesResult = $conn->query($tripTypesQuery);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $transportation = trim($_POST['Transportation']);
    $Accomodation = trim($_POST['Accomodation']);
    $Maximum = intval($_POST['Maximum']);
    $departure = trim($_POST['Departure']); // Fixed variable name
    $season = trim($_POST['season']);
    $triptype_id = intval($_POST['triptype_id']);
    $meals = trim($_POST['meals']);
    $language = trim($_POST['language']);
    $fitnesslevel = trim($_POST['fitnesslevel']);
    $groupsize = trim($_POST['groupsize']);
    $minimumage = intval($_POST['minimumage']);
    $maximumage = intval($_POST['maximumage']);
    $location = trim($_POST['location']);
    $duration = trim($_POST['duration']); // Added duration field

    // Get trip type name
    $tripTypeQuery = "SELECT triptype FROM triptypes WHERE triptype_id = ?";
    $stmt = $conn->prepare($tripTypeQuery);
    $stmt->bind_param("i", $triptype_id);
    $stmt->execute();
    $tripTypeResult = $stmt->get_result();
    $triptype_name = '';
    if ($tripTypeResult && $tripTypeResult->num_rows > 0) {
        $triptype_name = $tripTypeResult->fetch_assoc()['triptype']; // Fixed column name
    }

    $sql = "INSERT INTO trips (
        title, price, description, transportation, accomodation, maximumaltitude, 
        departurefrom, bestseason, triptype_id, triptype, meals, language, 
        fitnesslevel, groupsize, minimumage, maximumage, location, duration
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sdsssississsssiiss", // Corrected parameter types: 17 characters for 17 variables
        $title,          // s - string
        $price,          // d - double/float
        $description,   // s - string
        $transportation, // s - string
        $Accomodation,   // s - string
        $Maximum,        // i - integer
        $departure,      // s - string
        $season,         // s - string
        $triptype_id,    // i - integer
        $triptype_name,  // s - string
        $meals,          // s - string
        $language,       // s - string
        $fitnesslevel,   // s - string
        $groupsize,      // s - string
        $minimumage,     // i - integer
        $maximumage,     // i - integer
        $location ,      // s - string
        $duration
    );
    
    if ($stmt->execute()) {
        echo "<script>alert('Trip created successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
    $stmt->close();
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.addEventListener('click', function (event) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    if (!menu.parentElement.contains(event.target)) {
                        menu.classList.add('hidden');
                    }
                });
            });
            document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
                toggle.addEventListener('click', function (event) {
                    event.stopPropagation();
                    const dropdownMenu = this.nextElementSibling;
                    dropdownMenu.classList.toggle('hidden');
                });
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
        <!-- Main Content -->
        <div class="ml-64 p-6 w-full mt-16">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h1 class="text-3xl font-bold text-gray-800 mt-6 tracking-tighter ">Add New Trips</h1>
                <form method="post">
                    <div class="mt-8">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title:</label>
                        <input type="text" name="title" id="title" class="w-full p-2 border border-gray-300 rounded"
                            required>
                    </div>
                    <div class="mt-8">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Short Description:</label>
                        <input type="text" name="description" id="description"
                            class="w-full p-2 border border-gray-300 rounded" required>
                    </div>
                    <div class="mt-8">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Price:</label>
                        <input type="number" name="price" id="price" class="w-full p-2 border border-gray-300 rounded"
                            required>
                    </div>
                    <div class="mt-8">
                        <label for="Transportation"
                            class="block text-sm font-medium text-gray-700 mb-1">Transportation</label>
                        <select id="Transportation" name="Transportation" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="" disabled selected>Select Transportation</option>
                            <option value="Bus">Bus</option>
                            <option value="Car">Car</option>
                            <option value="Helicopter">Helicopter</option>
                        </select>
                    </div>
                    <div class="mt-8">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Accommodation:</label>
                        <input type="text" id="Accomodation" name="Accomodation"
                            class="w-full p-2 border border-gray-300 rounded" required>
                    </div>
                    <div class="mt-8">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Maximum altitude:</label>
                        <input type="number" id="Maximum" name="Maximum"
                            class="w-full p-2 border border-gray-300 rounded" required>
                    </div>
                    <div class="mt-8">
                        <label for="Departure" class="block text-sm font-medium text-gray-700 mb-1">Departure from</label>
                        <select id="Departure" name="Departure" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="" disabled selected>Departure From</option>
                            <option value="Kathmandu">Kathmandu</option>
                            <option value="Lalitpur">Lalitpur</option>
                            <option value="Chitwan">Chitwan</option>
                        </select>
                    </div>
                    <div class="mt-8">
                        <label for="season" class="block text-sm font-medium text-gray-700 mb-1">Best season</label>
                        <select id="season" name="season" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="" disabled selected>Best Season</option>
                            <option value="Winter">Winter</option>
                            <option value="Summer">Summer</option>
                            <option value="Spring">Spring</option>
                            <option value="Autumn">Autumn</option>
                        </select>
                    </div>
                    <div class="mt-8">
                        <label for="triptype" class="block text-sm font-medium text-gray-700 mb-2">Trip Type</label>
                        <select id="triptype" name="triptype_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="" disabled selected>Select Trip Type</option>
                            <?php if ($tripTypesResult && $tripTypesResult->num_rows > 0): ?>
                                <?php while ($tripType = $tripTypesResult->fetch_assoc()): ?>
                                    <option value="<?php echo $tripType['triptype_id']; ?>" title="<?php echo htmlspecialchars($tripType['description']); ?>">
                                        <?php echo htmlspecialchars($tripType['triptype']); ?>
                                    </option>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <option value="" disabled>No trip types available</option>
                            <?php endif; ?>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Hover over options to see descriptions</p>
                    </div>
                    <div class="mt-7">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Meals:</label>
                        <input type="text" id="meals" name="meals" class="w-full p-2 border border-gray-300 rounded"
                            required>
                    </div>
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Language:</label>
                        <input type="text" id="language" name="language"
                            class="w-full p-2 border border-gray-300 rounded" required>
                    </div>
                    <div class="mt-6">
                        <label for="fitnesslevel" class="block text-sm font-medium text-gray-700 mb-1">Fitness
                            Level</label>
                        <select id="fitnesslevel" name="fitnesslevel" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="" disabled selected>Select Fitness Level</option>
                            <option value="Beginner">Beginner</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                        </select>
                    </div>
                    <div class="mt-6">
                        <label for="groupsize" class="block text-sm font-medium text-gray-700 mb-1">Group Size</label>
                        <select id="groupsize" name="groupsize" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="" disabled selected>Select Group Size</option>
                            <option value="2">2</option>
                            <option value="2-6">2-6</option>
                            <option value="6-14">6-14</option>
                            <option value="14-More">14-More</option>
                        </select>
                    </div>
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Age:</label>
                        <input type="number" id="minimumage" name="minimumage"
                            class="w-full p-2 border border-gray-300 rounded" required>
                    </div>
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Maximum Age:</label>
                        <input type="number" id="maximumage" name="maximumage"
                            class="w-full p-2 border border-gray-300 rounded" required>
                    </div>
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Location: </label>
                        <input type="text" id="location" name="location"
                            class="w-full p-2 border border-gray-300 rounded" required>
                    </div>
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Duration:</label>
                        <input type="text" id="duration" name="duration"
                            class="w-full p-2 border border-gray-300 rounded" placeholder="e.g., 5 days, 2 weeks" required>
                    </div>
                    <div class="mt-6">
                        <!-- Submit Button -->
                        <div class="md:col-span-2 mt-4 flex justify-end space-x-4">
                            <button type="submit" class="bg-[#008080] text-white px-4 py-2 rounded">Create</button>
                            <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.querySelector("form");

            form.addEventListener("submit", function (e) {
                // Prevent form from submitting until validation is done
                e.preventDefault();

                let isValid = true;
                let errorMsg = "";

                // Text and number input validations
                const title = document.getElementById("title").value.trim();
                const description = document.getElementById("description").value.trim();
                const price = document.getElementById("price").value;
                const accommodation = document.getElementById("Accomodation").value.trim();
                const maximum = document.getElementById("Maximum").value;
                const meals = document.getElementById("meals").value.trim();
                const language = document.getElementById("language").value.trim();
                const minAge = document.getElementById("minimumage").value;
                const maxAge = document.getElementById("maximumage").value;
                const location = document.getElementById("location").value.trim();
                cosnt duration = document.getElementById("duration").value.trim();

                // Select field validations
                const transportation = document.getElementById("Transportation").value;
                const departure = document.getElementById("Departure").value;
                const season = document.getElementById("season").value;
                const tripType = document.getElementById("triptype").value;
                const fitnessLevel = document.getElementById("fitnesslevel").value;
                const groupSize = document.getElementById("groupsize").value;

                if (title === "") {
                    isValid = false;
                    errorMsg += "Title is required.\n";
                }

                if (description === "") {
                    isValid = false;
                    errorMsg += "Description is required.\n";
                }

                if (!price || price <= 0) {
                    isValid = false;
                    errorMsg += "Price must be a positive number.\n";
                }

                if (transportation === "") {
                    isValid = false;
                    errorMsg += "Please select a transportation option.\n";
                }

                if (accommodation === "") {
                    isValid = false;
                    errorMsg += "Accommodation is required.\n";
                }

                if (!maximum || maximum <= 0) {
                    isValid = false;
                    errorMsg += "Maximum altitude must be a positive number.\n";
                }

                if (departure === "") {
                    isValid = false;
                    errorMsg += "Please select a departure location.\n";
                }

                if (season === "") {
                    isValid = false;
                    errorMsg += "Please select the best season.\n";
                }

                if (tripType === "") {
                    isValid = false;
                    errorMsg += "Please select a trip type.\n";
                }

                if (meals === "") {
                    isValid = false;
                    errorMsg += "Meals information is required.\n";
                }

                if (language === "") {
                    isValid = false;
                    errorMsg += "Language is required.\n";
                }

                if (fitnessLevel === "") {
                    isValid = false;
                    errorMsg += "Please select a fitness level.\n";
                }

                if (groupSize === "") {
                    isValid = false;
                    errorMsg += "Please select a group size.\n";
                }

                if (!minAge || isNaN(minAge) || minAge <= 0) {
                    isValid = false;
                    errorMsg += "Minimum age must be a valid positive number.\n";
                }

                if (!maxAge || isNaN(maxAge) || maxAge <= 0) {
                    isValid = false;
                    errorMsg += "Maximum age must be a valid positive number.\n";
                }

                if (parseInt(minAge) > parseInt(maxAge)) {
                    isValid = false;
                    errorMsg += "Minimum age cannot be greater than maximum age.\n";
                }

                if (location === "") {
                    isValid = false;
                    errorMsg += "Location is required.\n";
                }

                if (duration === "") {
                    isValid = false;
                    errorMsg += "Duration is required.\n";
                }

                if (!isValid) {
                    alert(errorMsg);
                } else {
                    form.submit(); // Submit the form if all validations pass
                }
            });
        });
    </script>

</body>

</html>