<?php
require '../connection.php';

// Handle delete request
if (isset($_POST['delete_triptype'])) {
    $triptype_name = $_POST['triptype_name'];
    
    if (!empty($triptype_name)) {
        $stmt = $conn->prepare("DELETE FROM triptypes WHERE triptype = ?");
        
        if ($stmt) {
            $stmt->bind_param("s", $triptype_name);
            
            if ($stmt->execute()) {
                header("Location: alltriptype.php?delete=success");
                exit();
            } else {
                $delete_error = "Error deleting: " . $stmt->error;
            }
            
            $stmt->close();
        } else {
            $delete_error = "Error preparing statement: " . $conn->error;
        }
    } else {
        $delete_error = "Invalid triptype name";
    }
}

$stmt = $conn->prepare("SELECT * FROM triptypes");
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="frontend/sidebar.css">
     <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
        
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
                        
                        for (let j = 0; j < cells.length - 1; j++) {
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
            const deleteButtons = document.querySelectorAll('button[name="delete_triptype"]');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    if (!confirm('Are you sure you want to delete this triptype?')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="flex h-screen">
        <!-- Header -->
        <?php include("frontend/header.php"); ?>
        <!-- Sidebar -->
        <?php include("frontend/sidebar.php"); ?>
        
        <!-- main section -->
        <div class="ml-64 p-6 w-[84%] mx-auto mt-16">
            <div class="bg-white shadow-md rounded-lg p-6">
                <div class="bg-gray-100 p-4 rounded-lg mb-6">
                    <h2 class="text-3xl font-bold text-gray-800 mt-6 tracking-tighter">All Trip Type</h2>
                    
                    <!-- Messages -->
                    <?php if (isset($_GET['delete']) && $_GET['delete'] == 'success'): ?>
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            Triptype deleted successfully!
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($delete_error)): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <?php echo htmlspecialchars($delete_error); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Search Bar -->
                    <div class="mt-4 mb-6">
                        <input type="text" id="searchInput" placeholder="Search triptypes..."
                            class="w-full md:w-1/3 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>

                    <div class="flex justify-end mb-4">
                        <a href="createtriptype.php" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                            <i class="fas fa-plus mr-2"></i>Add New Trip Type
                        </a>
                    </div>

                    <!-- Table -->
                    <div class="bg-white p-6 rounded-lg shadow-lg overflow-x-auto">
                        <table class="min-w-full" id="userTable">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Image</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php if ($result->num_rows > 0): ?>
                                    <?php while ($triptype = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td class="px-6 py-4"><?php echo htmlspecialchars($triptype["triptype"]); ?></td>
                                            <td class="px-6 py-4"><?php echo htmlspecialchars($triptype["description"]); ?></td>
                                            <td class="px-6 py-4">
                                                <img src="<?php echo "../" . htmlspecialchars($triptype["main_image"]); ?>" 
                                                     alt="triptype" class="h-12 w-20 object-cover">
                                            </td>
                                            <td class="px-6 py-4">
                                                <a href="edittriptype.php?name=<?php echo urlencode($triptype['triptype']); ?>" 
                                                   class="text-blue-500 hover:text-blue-700 mr-2">
                                                   <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="post" class="inline">
                                                    <input type="hidden" name="triptype_name" 
                                                           value="<?php echo htmlspecialchars($triptype['triptype']); ?>">
                                                    <button type="submit" name="delete_triptype" 
                                                            class="text-red-500 hover:text-red-700">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                            No triptypes found.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>