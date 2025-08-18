<?php
require 'frontend/connection.php';

// Handle delete request
if (isset($_POST['delete_item'])) {
    // Get the item ID to delete
    $item_id = $_POST['item_id'];
    
    // Make sure the item ID is valid
    if (!empty($item_id) && is_numeric($item_id)) {
        // Prepare the SQL statement
        $stmt = $conn->prepare("DELETE FROM itinerary WHERE itinerary_id = ?");
        
        // Check if the statement was prepared successfully
        if ($stmt) {
            // Bind the parameter
            $stmt->bind_param("i", $item_id);
            
            // Execute the statement and check if it was successful
            if ($stmt->execute()) {
                // Redirect to prevent form resubmission
                header("Location: allitinerary.php?delete=success");
                exit();
            } else {
                $delete_error = "Error executing delete: " . $stmt->error;
            }
            
            // Close the statement
            $stmt->close();
        } else {
            $delete_error = "Error preparing delete statement: " . $conn->error;
        }
    } else {
        $delete_error = "Invalid itinerary ID";
    }
}

// Fetch all itinerary data
$stmt = $conn->prepare("SELECT * FROM itinerary ORDER BY tripid, day_number");
$stmt->execute();
$result = $stmt->get_result();

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

            // Search functionality
            const searchInput = document.getElementById('searchInput');
            const userTable = document.getElementById('userTable');
            
            if (searchInput && userTable) {
                searchInput.addEventListener('keyup', function() {
                    const searchTerm = this.value.toLowerCase();
                    const rows = userTable.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
                    
                    for (let i = 0; i < rows.length; i++) {
                        const row = rows[i];
                        const cells = row.getElementsByTagName('td');
                        let found = false;
                        
                        for (let j = 0; j < cells.length - 1; j++) { // -1 to exclude actions column
                            if (cells[j].textContent.toLowerCase().includes(searchTerm)) {
                                found = true;
                                break;
                            }
                        }
                        
                        row.style.display = found ? '' : 'none';
                    }
                });
            }

            // Confirm delete
            const deleteButtons = document.querySelectorAll('button[name="delete_item"]');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    if (!confirm('Are you sure you want to delete this itinerary item?')) {
                        e.preventDefault();
                    }
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
        <!-- main section -->
        <div class="ml-64 p-6 w-[84%] mx-auto mt-16">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-4"><br></h1>
                <div class="bg-gray-100 p-4 rounded-lg mb-6">
                    <h2 class="text-3xl font-bold text-gray-800 mt-6 tracking-tighter">All Itinerary</h2>
                    
                    <!-- Display success/error messages -->
                    <?php if (isset($_GET['delete']) && $_GET['delete'] == 'success'): ?>
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            Itinerary item deleted successfully!
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($delete_error)): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <?php echo htmlspecialchars($delete_error); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Search Bar -->
                    <div class="mt-4 mb-6">
                        <input type="text" id="searchInput" placeholder="Search itinerary..."
                            class="w-full md:w-1/3 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>

                    <!-- Itinerary Table -->
                    <div class="bg-white p-6 rounded-lg shadow-lg overflow-x-auto">
                        <table class="min-w-full" id="userTable">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Trip ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Day</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Description</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                            <?php
                            if ($result->num_rows > 0) {
                                while ($items = $result->fetch_assoc()) {
                                    ?>
                                    <tr>
                                        <td class="px-6 py-4"><?php echo htmlspecialchars($items["tripid"]); ?></td>
                                        <td class="px-6 py-4"><?php echo htmlspecialchars($items["day_number"]); ?></td>
                                        <td class="px-6 py-4"><?php echo htmlspecialchars($items["title"]); ?></td>
                                        <td class="px-6 py-4"><?php echo htmlspecialchars(substr($items["description"], 0, 100)) . (strlen($items["description"]) > 100 ? '...' : ''); ?></td>
                                        <td class="px-6 py-4">
                                            <a href="edititinerary.php?id=<?php echo $items['itinerary_id']; ?>" 
                                               class="text-blue-500 hover:text-blue-700 mr-2" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="post" style="display:inline;" action="allitinerary.php">
                                                <input type="hidden" name="item_id" value="<?php echo $items['itinerary_id']; ?>">
                                                <button type="submit" name="delete_item" 
                                                        class="text-red-500 hover:text-red-700" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php }
                            } else { ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        No itinerary items found.
                                    </td>
                                </tr>
                            <?php }
                            $stmt->close();
                            $conn->close();
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>