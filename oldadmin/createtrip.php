<?php
require 'frontend/connection.php'; // Reuse your DB connection file
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $transportation = trim($_POST['Transportation']);
    $accomodation = trim($_POST['accomodation']);
    $maxaltitude = intval($_POST['maxaltitude']);
    $depature = trim($_POST['depature']);
    $seasion = trim($_POST['seasion']);
    $triptype = trim($_POST['triptype']);
    $meals = trim($_POST['meals']);
    $language = trim($_POST['language']);
    $fitnesslevel = trim($_POST['fitnesslevel']);
    $groupsize = trim($_POST['groupsize']);
    $minimumage = intval($_POST['minimumage']);
    $maximumage = intval($_POST['maximumage']);
    $location = trim($_POST['location']);
    $sql = "INSERT INTO trips (
        title, price, transportation, accomodation, maximumaltitude, 
        departurefrom, bestseason, triptype, meals, language, 
        fitnesslevel, groupsize, minimumage, maximumage,description,location
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sdssssssssssiiss", // string, decimal, string... int, int
        $title,
        $price,
        $transportation,
        $accomodation,
        $maxaltitude,
        $depature,
        $seasion,
        $triptype,
        $meals,
        $language,
        $fitnesslevel,
        $groupsize,
        $minimumage,
        $maximumage,
        $description,
        $location
    );
    if ($stmt->execute()) {
        echo "<script>alert('Registration Successful');</script>";
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
                <h1 class="text-2xl font-bold text-gray-800 mt-12">Add New Trips</h1>
                <form method="post">
                    <div>
                        <label class="block text-gray-700">Title:</label>
                        <input type="text" name="title" id="title" class="w-full p-2 border border-gray-300 rounded"
                            required>
                    </div>
                    <div>
                        <label class="block text-gray-700">Short Description:</label>
                        <input type="text" name="description" id="description"
                            class="w-full p-2 border border-gray-300 rounded" required>
                    </div>
                    <div>
                        <label class="block text-gray-700">Price:</label>
                        <input type="number" name="price" id="Price" class="w-full p-2 border border-gray-300 rounded"
                            required>
                    </div>
                    <div class="mb-6">
                        <label for="Transportation"
                            class="block text-sm font-medium text-gray-700 mb-1">Transportation</label>
                        <select id="Transportation" name="Transportation" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="" disabled selected>Select Transportation</option>
                            <option value="Bus">Bus</option>
                            <option value="Car">car</option>
                            <option value="Helicopter">Helicopter</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700">Accomodation:</label>
                        <input type="text" id="Accomodation" name="accomodation"
                            class="w-full p-2 border border-gray-300 rounded" required>
                    </div>
                    <div>
                        <label class="block text-gray-700">Maxmimum altitude:</label>
                        <input type="text" id="Maxmimum" name="maxaltitude"
                            class="w-full p-2 border border-gray-300 rounded" required>
                    </div>
                    <div class="mb-6">
                        <label for="Depature" class="block text-sm font-medium text-gray-700 mb-1">Depature from</label>
                        <select id="Depature" name="depature" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="" disabled selected> Depature From</option>
                            <option value="Kathmandu">Kathmandu</option>
                            <option value="Lalitpur">Lalitpur</option>
                            <option value="Chitwan">Chitwan</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label for="Session" class="block text-sm font-medium text-gray-700 mb-1">Best session</label>
                        <select id="Session" name="seasion" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="" disabled selected>Best Session </option>
                            <option value="Winter">Winter</option>
                            <option value="Summer">Summer</option>
                            <option value="Spring">Spring</option>
                            <option value="Autumn">Autumn</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label for="triptype" class="block text-sm font-medium text-gray-700 mb-1">Trip Type</label>
                        <select id="triptype" name="triptype" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="" disabled selected>Select Triptype</option>
                            <option value="Nature Friendly">Nature Friendly</option>
                            <option value="Cultural">Cultural</option>
                            <option value="Budget Friendly">Budget Friendly</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700">Meals:</label>
                        <input type="text" id="Meals" name="meals" class="w-full p-2 border border-gray-300 rounded"
                            required>
                    </div>
                    <div>
                        <label class="block text-gray-700">Language:</label>
                        <input type="text" id="language" name="language"
                            class="w-full p-2 border border-gray-300 rounded" required>
                    </div>
                    <div class="mb-6">
                        <label for="fitnesslevel" class="block text-sm font-medium text-gray-700 mb-1">Fitness
                            Level</label>
                        <select id="fitnesslevel" name="fitnesslevel" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="" disabled selected>Select groupsize</option>
                            <option value="Begineer">Begineer</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label for="groupsize" class="block text-sm font-medium text-gray-700 mb-1">Group Size</label>
                        <select id="groupsize" name="groupsize" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="" disabled selected>Select groupsize</option>
                            <option value="2">2</option>
                            <option value="2-6">2-6</option>
                            <option value="6-14">6-14</option>
                            <option value="14-More">14-More</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700">Minimum Age:</label>
                        <input type="text" id="minimumage" name="minimumage"
                            class="w-full p-2 border border-gray-300 rounded" required>
                    </div>
                    <div>
                        <label class="block text-gray-700">Maximum Age:</label>
                        <input type="text" id="maximumage" name="maximumage"
                            class="w-full p-2 border border-gray-300 rounded" required>
                    </div>
                    <div>
                        <label class="block text-gray-700">Location: </label>
                        <input type="text" id="location" name="location"
                            class="w-full p-2 border border-gray-300 rounded" required>
                    </div>
                    <div>
                        <!-- Submit Button -->
                        <div class="md:col-span-2 mt-4 flex justify-end space-x-4">
                            <button type="submit" class="bg-[#008080] text-white px-4 py-2 rounded">Create </button>
                            <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
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
                const price = document.getElementById("Price").value;
                const accomodation = document.getElementById("Accomodation").value.trim();
                const maxAltitude = document.getElementById("Maxmimum").value;
                const meals = document.getElementById("Meals").value.trim();
                const language = document.getElementById("language").value.trim();
                const minAge = document.getElementById("minimumage").value;
                const maxAge = document.getElementById("maximumage").value;

                // Select field validations
                const transportation = document.getElementById("Transportation").value;
                const depature = document.getElementById("Depature").value;
                const session = document.getElementById("Session").value;
                const tripType = document.getElementById("triptype").value;
                const fitnessLevel = document.getElementById("fitnesslevel").value;
                const groupSize = document.getElementById("groupsize").value;

                if (title === "") {
                    isValid = false;
                    errorMsg += "Title is required.\n";
                }

                if (!price || price <= 0) {
                    isValid = false;
                    errorMsg += "Price must be a positive number.\n";
                }

                if (transportation === "") {
                    isValid = false;
                    errorMsg += "Please select a transportation option.\n";
                }

                if (accomodation === "") {
                    isValid = false;
                    errorMsg += "Accomodation is required.\n";
                }

                if (!maxAltitude || maxAltitude <= 0) {
                    isValid = false;
                    errorMsg += "Maximum altitude must be a positive number.\n";
                }

                if (depature === "") {
                    isValid = false;
                    errorMsg += "Please select a depature location.\n";
                }

                if (session === "") {
                    isValid = false;
                    errorMsg += "Please select the best session.\n";
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