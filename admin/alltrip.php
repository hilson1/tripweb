<?php
require '../connection.php';

session_start();
// Uncomment these lines when ready to implement authentication
// if (!isset($_SESSION['admin_id'])) {
//   header('location:adminlogin.php');
//   exit;
// }

// Handle delete request
if (isset($_POST['delete_trip'])) {
    $tripId = $_POST['trip_id'];
    
    try {
        // Start transaction
        $conn->begin_transaction();
        
        // Delete related trip images first
        $stmt = $conn->prepare("SELECT image_path FROM trip_images WHERE tripid = ?");
        $stmt->bind_param("i", $tripId);
        $stmt->execute();
        $images = $stmt->get_result();
        
        // Delete image files from server
        while ($image = $images->fetch_assoc()) {
            if (file_exists($image['image_path'])) {
                unlink($image['image_path']);
            }
        }
        
        // Delete from trip_images table
        $stmt = $conn->prepare("DELETE FROM trip_images WHERE tripid = ?");
        $stmt->bind_param("i", $tripId);
        $stmt->execute();
        
        // Delete from trips table
        $stmt = $conn->prepare("DELETE FROM trips WHERE tripid = ?");
        $stmt->bind_param("i", $tripId);
        $stmt->execute();
        
        // Commit transaction
        $conn->commit();
        
        $_SESSION['success'] = "Trip deleted successfully!";
        
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = "Error deleting trip: " . $e->getMessage();
    }
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch trips with search functionality and proper join with trip_types
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$whereClause = '';
$params = [];
$types = '';

if (!empty($searchTerm)) {
    $whereClause = "WHERE trips.title LIKE ? OR trips.location LIKE ? OR tt.name LIKE ?";
    $searchParam = "%$searchTerm%";
    $params = [$searchParam, $searchParam, $searchParam];
    $types = 'sss';
}

$sql = "SELECT trips.tripid, trips.title, trips.departurefrom, trips.groupsize, 
               trips.location, trips.price, trips.status, trips.triptype_id,
               tt.triptype as name, tt.description as triptype_description
        FROM trips 
        LEFT JOIN triptypes tt ON trips.triptype_id = tt.triptype_id
        $whereClause 
        ORDER BY trips.tripid DESC";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trip Management - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    
    <style>
        .table-container {
            max-height: 600px;
            overflow-y: auto;
        }
        
        .status-badge {
            @apply px-2 py-1 text-xs font-semibold rounded-full;
        }
        
        .status-active {
            @apply bg-green-100 text-green-800;
        }
        
        .status-expired {
            @apply bg-red-100 text-red-800;
        }
        
        .status-featured {
            @apply bg-blue-100 text-blue-800;
        }
        
        .btn-action {
            @apply px-3 py-1 text-sm font-medium rounded-md transition-colors duration-200;
        }
        
        .btn-edit {
            @apply bg-blue-500 text-white hover:bg-blue-600;
        }
        
        .btn-delete {
            @apply bg-red-500 text-white hover:bg-red-600;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Dropdown functionality
            const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
            dropdownToggles.forEach(toggle => {
                toggle.addEventListener('click', function (event) {
                    event.preventDefault();
                    document.querySelectorAll('.dropdown-menu').forEach(menu => {
                        if (menu !== this.nextElementSibling) {
                            menu.classList.add('hidden');
                        }
                    });
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
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value;
                    const url = new URL(window.location);
                    if (searchTerm) {
                        url.searchParams.set('search', searchTerm);
                    } else {
                        url.searchParams.delete('search');
                    }
                    window.location.href = url.toString();
                });
            }

            // Delete confirmation
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const tripName = this.dataset.tripName;
                    if (confirm(`Are you sure you want to delete "${tripName}"? This action cannot be undone.`)) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.innerHTML = `
                            <input type="hidden" name="delete_trip" value="1">
                            <input type="hidden" name="trip_id" value="${this.dataset.tripId}">
                        `;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });
    </script>
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <?php include("frontend/asidebar.php"); ?>
        
        <!-- Main Content -->
        <div class="ml-64 p-6 w-[84%] mx-auto mt-16">
            <!-- Success/Error Messages -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <span class="block sm:inline"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></span>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <span class="block sm:inline"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></span>
                </div>
            <?php endif; ?>
            
            <div class="bg-white shadow-md rounded-lg p-6">
                <!-- Header Section -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800 mt-6 tracking-tighter">Trip Management</h1>
                        <p class="text-gray-600 mt-1">Manage all your trips from here</p>
                    </div>
                    <a href="createtrip.php" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                        <i class="fas fa-plus mr-2"></i>Add New Trip
                    </a>
                </div>
                
                <!-- Search and Filter Section -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex-1 max-w-md">
                            <div class="relative">
                                <input type="text" id="searchInput" 
                                       placeholder="Search trips by name, location, or type..." 
                                       value="<?php echo htmlspecialchars($searchTerm); ?>"
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                        <div class="text-sm text-gray-600">
                            Total Trips: <span class="font-semibold"><?php echo $result->num_rows; ?></span>
                        </div>
                    </div>
                </div>
                
                <!-- Trips Table -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="table-container">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 sticky top-0">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Trip Title
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Departure From
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Trip Type
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Group Size
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Location
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Price (USD)
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if ($result->num_rows > 0): ?>
                                    <?php while ($trip = $result->fetch_assoc()): ?>
                                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <?php echo htmlspecialchars($trip['title']); ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    <?php echo htmlspecialchars($trip['departurefrom'] ?? 'N/A'); ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900 capitalize">
                                                    <?php echo htmlspecialchars($trip['name']); ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    <?php echo htmlspecialchars($trip['groupsize'] ?? 'N/A'); ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900 capitalize">
                                                    <?php echo htmlspecialchars($trip['location']); ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-semibold text-gray-900">
                                                    $<?php echo number_format($trip['price'], 2); ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <?php
                                                $status = strtolower($trip['status']);
                                                $statusClasses = [
                                                    'draft' => 'bg-yellow-100 text-yellow-800',
                                                    'active' => 'bg-green-100 text-green-800',
                                                    'expired' => 'bg-red-100 text-red-800',
                                                ];
                                                $badgeClass = $statusClasses[$status] ?? 'bg-gray-100 text-gray-800';
                                                ?>
                                                <span class="px-3 py-1 rounded-full text-sm font-medium <?php echo $badgeClass; ?>">
                                                    <?php echo ucfirst($trip['status']); ?>
                                                </span>
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                                <a href="edittrip.php?id=<?php echo $trip['tripid']; ?>" 
                                                   class="btn-action btn-edit inline-flex items-center">
                                                    <i class="fas fa-edit mr-1"></i>Edit
                                                </a>
                                                <button class="btn-action btn-delete delete-btn inline-flex items-center" 
                                                        data-trip-id="<?php echo $trip['tripid']; ?>"
                                                        data-trip-name="<?php echo htmlspecialchars($trip['title']); ?>">
                                                    <i class="fas fa-trash mr-1"></i>Delete
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="px-6 py-12 text-center">
                                            <div class="text-gray-500">
                                                <i class="fas fa-map-marked-alt text-4xl mb-4"></i>
                                                <p class="text-lg font-medium">No trips found</p>
                                                <p class="text-sm">
                                                    <?php if (!empty($searchTerm)): ?>
                                                        Try adjusting your search criteria or 
                                                        <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="text-blue-600 hover:text-blue-800">clear search</a>
                                                    <?php else: ?>
                                                        Get started by creating your first trip
                                                    <?php endif; ?>
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Pagination would go here if needed -->
                <?php if ($result->num_rows > 0): ?>
                    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6 mt-4">
                        <div class="flex-1 flex justify-between sm:hidden">
                            <!-- Mobile pagination buttons -->
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Showing <span class="font-medium"><?php echo $result->num_rows; ?></span> results
                                </p>
                            </div>
                            <!-- Pagination buttons would go here -->
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>