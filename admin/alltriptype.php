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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Trip-Type Management - ThankYouNepalTrip</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  
  <!-- FontAwesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
  <link rel="stylesheet" href="frontend/sidebar.css">
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="bg-gray-50 font-sans leading-normal tracking-normal" x-data="{ sidebarOpen: false }">
  <!-- Overlay for mobile sidebar -->
  <div class="overlay" :class="{ 'open': sidebarOpen }" @click="sidebarOpen = false"></div>

  <!-- Top Navigation Bar -->
  <?php include 'frontend/header.php'; ?>

  <!-- Sidebar -->
  <?php include 'frontend/sidebar.php'; ?>

  <!-- Main Content Area -->
  <main class="main-content pt-16 min-h-screen transition-all duration-300">
    <div class="p-6">
      <!-- Trip Type Management Content -->
      <div class="bg-white rounded-xl shadow-md p-6">
        <!-- Header Section -->
        <div class="mb-8">
          <div class="gradient-bg rounded-2xl p-6 text-white">
            <div class="flex justify-between items-center">
              <div>
                <h1 class="text-3xl font-bold mb-2">
                  <i class="fas fa-map-marked-alt mr-3"></i>Trip Type Management
                </h1>
                <p class="text-blue-100">Manage and monitor all trip types</p>
              </div>
              <div class="text-right">
                <div class="text-2xl font-bold" id="totalTripTypes">
                  <?php echo $result->num_rows; ?>
                </div>
                <div class="text-blue-100">Total Trip Types</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Messages -->
        <?php if (isset($_GET['delete']) && $_GET['delete'] == 'success'): ?>
          <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
            <i class="fas fa-check-circle mr-2"></i>
            Trip type deleted successfully!
          </div>
        <?php endif; ?>
        
        <?php if (isset($delete_error)): ?>
          <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <?php echo htmlspecialchars($delete_error); ?>
          </div>
        <?php endif; ?>

        <!-- Search and Add Button Section -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
          <div class="w-full md:w-1/3">
            <div class="relative">
              <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
              <input type="text" id="searchInput" placeholder="Search trip types..."
                     class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
          </div>
          <a href="createtriptype.php" class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-6 py-2 rounded-lg transition-all duration-300 flex items-center shadow-lg">
            <i class="fas fa-plus mr-2"></i>Add New Trip Type
          </a>
        </div>


        <!-- Table Section -->
        <div class="glass-effect rounded-2xl shadow-xl overflow-hidden">
          <div class="overflow-x-auto custom-scrollbar">
            <table class="min-w-full" id="triptypeTable">
              <thead class="gradient-bg text-white">
                <tr>
                  <th class="py-4 px-6 text-left font-semibold">
                    <i class="fas fa-tag mr-2"></i>Name
                  </th>
                  <th class="py-4 px-6 text-left font-semibold hidden-mobile">
                    <i class="fas fa-align-left mr-2"></i>Description
                  </th>
                  <th class="py-4 px-6 text-left font-semibold">
                    <i class="fas fa-image mr-2"></i>Image
                  </th>
                  <th class="py-4 px-6 text-left font-semibold">
                    <i class="fas fa-cog mr-2"></i>Actions
                  </th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                <?php
                if ($result->num_rows > 0) {
                  while ($triptype = $result->fetch_assoc()) {
                ?>
                <tr class="table-row hover:bg-gray-50">
                  <td class="py-4 px-6">
                    <div class="font-semibold text-gray-900">
                      <?php echo htmlspecialchars($triptype["triptype"]); ?>
                    </div>
                  </td>
                  <td class="py-4 px-6 hidden-mobile">
                    <span class="text-gray-700">
                      <?php
                        $desc = $triptype["description"];
                        echo htmlspecialchars(substr($desc, 0, 100));
                        if (strlen($desc) > 100) echo '...';
                      ?>
                    </span>
                  </td>
                  <td class="py-4 px-6">
                    <?php if (!empty($triptype["main_image"])): ?>
                        <img src="../<?php echo htmlspecialchars($triptype["main_image"]); ?>" 
                            alt="<?php echo htmlspecialchars($triptype["triptype"]); ?>" 
                            class="w-16 h-16 object-cover rounded-lg"
                            onerror="this.onerror=null; this.src='../assets/no-image.jpg';">
                    <?php else: ?>
                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400">
                        <i class="fas fa-image"></i>
                        </div>
                    <?php endif; ?>
                  </td>
                  <td class="py-4 px-6">
                    <div class="flex space-x-2">
                      <a href="edittriptype.php?name=<?php echo urlencode($triptype['triptype']); ?>" 
                         class="action-button bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-lg transition-colors">
                        <i class="fas fa-edit"></i>
                      </a>
                      <form method="post" class="inline">
                        <input type="hidden" name="triptype_name" 
                               value="<?php echo htmlspecialchars($triptype['triptype']); ?>">
                        <button type="submit" name="delete_triptype" 
                                class="action-button bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition-colors"
                                onclick="return confirm('Are you sure you want to delete this trip type?')">
                          <i class="fas fa-trash"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
                <?php 
                  }
                } else {
                ?>
                <tr>
                  <td colspan="4" class="py-8 px-6 text-center text-gray-500">
                    <i class="fas fa-map-marked-alt text-4xl mb-4 block"></i>
                    <p class="text-lg">No trip types found</p>
                  </td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
              <div class="text-sm text-gray-600">
                Showing <span id="startEntry">1</span> to <span id="endEntry">10</span> of <span id="totalEntries"><?php echo $result->num_rows; ?></span> entries
              </div>
              <div class="flex space-x-2" id="pagination">
                <!-- Pagination buttons will be generated by JavaScript -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const triptypeTable = document.getElementById('triptypeTable');
        
        if (searchInput && triptypeTable) {
            searchInput.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();
                const rows = triptypeTable.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
                
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
    });
  </script>
</body>

</html>