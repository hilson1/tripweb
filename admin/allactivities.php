<?php
require '../connection.php';
 
// Handle delete request
if (isset($_POST['delete_activity'])) {
    $activity_name = $_POST['activity_name'];
    
    if (!empty($activity_name)) {
        $stmt = $conn->prepare("DELETE FROM triptypes WHERE triptype = ?");
        
        if ($stmt) {
            $stmt->bind_param("s", $triptype_name);
            
            if ($stmt->execute()) {
                header("Location: alltriptype?delete=success");
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

// Get fresh data for display
$stmt = $conn->prepare("SELECT * FROM activities");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Activity Management - ThankYouNepalTrip</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  
  <!-- FontAwesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
  <link rel="stylesheet" href="frontend/sidebar.css">
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
      <!-- Activity Management Content -->
      <div class="bg-white rounded-xl shadow-md p-6">
        <!-- Header Section -->
        <div class="mb-8">
          <div class="gradient-bg rounded-2xl p-6 text-white">
            <div class="flex justify-between items-center">
              <div>
                <h1 class="text-3xl font-bold mb-2">
                  <i class="fas fa-hiking mr-3"></i>Activity Management
                </h1>
                <p class="text-blue-100">Manage and monitor all activities</p>
              </div>
              <div class="text-right">
                <div class="text-2xl font-bold" id="totalActivities">
                  <?php echo $result->num_rows; ?>
                </div>
                <div class="text-blue-100">Total Activities</div>
              </div>
            </div>
          </div>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
          <div class="w-full md:w-1/3">
            <div class="relative">
              <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
              <input type="text" id="searchInput" placeholder="Search activity..."
                     class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
          </div>
          <a href="createactivities" class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-6 py-2 rounded-lg transition-all duration-300 flex items-center shadow-lg">
            <i class="fas fa-plus mr-2"></i>Add New Activity
          </a>
        </div>


        <!-- Table Section -->
        <div class="glass-effect rounded-2xl shadow-xl overflow-hidden">
          <div class="overflow-x-auto custom-scrollbar">
            <table class="min-w-full" id="activityTable">
              <thead class="gradient-bg text-white">
                <tr>
                  <th class="py-4 px-6 text-left font-semibold">
                    <i class="fas fa-hiking mr-2"></i>Activity
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
                  while ($activity = $result->fetch_assoc()) {
                ?>
                <tr class="table-row hover:bg-gray-50">
                  <td class="py-4 px-6">
                    <div class="font-semibold text-gray-900">
                      <?php echo htmlspecialchars($activity["activity"]); ?>
                    </div>
                  </td>
                  <td class="py-4 px-6 hidden-mobile">
                    <span class="text-gray-700">
                      <?php
                        $desc = $activity["description"];
                        echo htmlspecialchars(substr($desc, 0, 100));
                        if (strlen($desc) > 50) echo '...';
                      ?>
                    </span>
                  </td>
                  <td class="py-4 px-6">
                    <?php if (!empty($activity["main_image"])): ?>
                        <img src="../<?php echo htmlspecialchars($activity["main_image"]); ?>" 
                            alt="<?php echo htmlspecialchars($activity["activity"]); ?>" 
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
                      <a href="editactivity?name=<?php echo urlencode($activity['activity']); ?>" 
                         class="action-button bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-lg transition-colors">
                        <i class="fas fa-edit"></i>
                      </a>
                      <a href="deleteactivity?name=<?php echo urlencode($activity['activity']); ?>" 
                         class="action-button bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition-colors"
                         onclick="return confirm('Are you sure you want to delete this activity?')">
                        <i class="fas fa-trash"></i>
                      </a>
                    </div>
                  </td>
                </tr>
                <?php 
                  }
                } else {
                ?>
                <tr>
                  <td colspan="9" class="py-8 px-6 text-center text-gray-500">
                    <i class="fas fa-map-marked-alt text-4xl mb-4 block"></i>
                    <p class="text-lg">No activities found</p>
                    <?php if (!empty($searchTerm)): ?>
                        <p class="text-sm mt-2">
                            Try adjusting your search criteria or 
                            <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="text-blue-600 hover:text-blue-800">clear search</a>
                        </p>
                    <?php endif; ?>
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

  <!-- Alpine JS for dropdown functionality -->
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  
  <script>
    // Initialize sidebar state
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

    //Search Functionality
    document.getElementById('searchInput').addEventListener('input', function() {
      const query = this.value.toLowerCase();
      filteredRows = allRows.filter(row => {
        return Array.from(row.cells).some(cell => 
          cell.textContent.toLowerCase().includes(query)
        );
      });
      currentPage = 1;
      updateTable();
    });

    // Table functionality
    let currentPage = 1;
    let entriesPerPage = 10;
    let allRows = [];
    let filteredRows = [];

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
      allRows = Array.from(document.querySelectorAll('#activityTable tbody tr')).filter(row => 
        !row.querySelector('td[colspan]')
      );
      filteredRows = [...allRows];
      updateTable();
    });

    function changeEntries() {
      entriesPerPage = parseInt(document.getElementById('entries').value);
      currentPage = 1;
      updateTable();
    }

    function searchTable() {
      const searchTerm = document.getElementById('search').value.toLowerCase();
      
      if (searchTerm === '') {
        filteredRows = [...allRows];
      } else {
        filteredRows = allRows.filter(row => {
          const cells = row.querySelectorAll('td');
          return Array.from(cells).some(cell => 
            cell.textContent.toLowerCase().includes(searchTerm)
          );
        });
      }
      
      currentPage = 1;
      updateTable();
    }

    function updateTable() {
      // Hide all rows
      allRows.forEach(row => row.style.display = 'none');
      
      // Calculate pagination
      const totalEntries = filteredRows.length;
      const startIndex = (currentPage - 1) * entriesPerPage;
      const endIndex = Math.min(startIndex + entriesPerPage, totalEntries);
      
      // Show relevant rows
      for (let i = startIndex; i < endIndex; i++) {
        if (filteredRows[i]) {
          filteredRows[i].style.display = '';
        }
      }
      
      // Update pagination info
      document.getElementById('startEntry').textContent = totalEntries > 0 ? startIndex + 1 : 0;
      document.getElementById('endEntry').textContent = endIndex;
      document.getElementById('totalEntries').textContent = totalEntries;
      
      // Update pagination buttons
      updatePagination(totalEntries);
    }

    function updatePagination(totalEntries) {
      const totalPages = Math.ceil(totalEntries / entriesPerPage);
      const paginationDiv = document.getElementById('pagination');
      paginationDiv.innerHTML = '';
      
      if (totalPages <= 1) return;
      
      // Previous button
      if (currentPage > 1) {
        paginationDiv.innerHTML += `
          <button onclick="changePage(${currentPage - 1})" 
                  class="px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
            <i class="fas fa-chevron-left"></i>
          </button>`;
      }
      
      // Page numbers
      for (let i = Math.max(1, currentPage - 2); i <= Math.min(totalPages, currentPage + 2); i++) {
        const activeClass = i === currentPage ? 'bg-blue-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-50';
        paginationDiv.innerHTML += `
          <button onclick="changePage(${i})" 
                  class="px-3 py-2 text-sm border border-gray-300 rounded-lg transition-colors ${activeClass}">
            ${i}
          </button>`;
      }
      
      // Next button
      if (currentPage < totalPages) {
        paginationDiv.innerHTML += `
          <button onclick="changePage(${currentPage + 1})" 
                  class="px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
            <i class="fas fa-chevron-right"></i>
          </button>`;
      }
    }

    function changePage(page) {
      currentPage = page;
      updateTable();
    }

    // Export Functions
    function printTable() {
      const printWindow = window.open('', '_blank');
      const tableHTML = generatePrintableTable();
      
      printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
          <title>Activities Report</title>
          <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            h1 { color: #333; text-align: center; margin-bottom: 30px; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background-color: #f5f5f5; font-weight: bold; }
            tr:nth-child(even) { background-color: #f9f9f9; }
            .print-date { text-align: right; color: #666; margin-bottom: 20px; }
          </style>
        </head>
        <body>
          <div class="print-date">Generated on: ${new Date().toLocaleString()}</div>
          <h1>Activities Report</h1>
          ${tableHTML}
        </body>
        </html>
      `);
      
      printWindow.document.close();
      printWindow.print();
    }

    function exportToPDF() {
      const { jsPDF } = window.jspdf;
      const doc = new jsPDF('l', 'mm', 'a4'); // landscape orientation
      
      // Add title
      doc.setFontSize(18);
      doc.text('Activities Report', 14, 22);
      
      // Add date
      doc.setFontSize(10);
      doc.text(`Generated on: ${new Date().toLocaleString()}`, 14, 32);
      
      // Get table data
      const tableData = getTableData();
      
      // Generate PDF table
      doc.autoTable({
        head: [['ID', 'Activity', 'Description', 'Status']],
        body: tableData,
        startY: 40,
        styles: {
          fontSize: 8,
          cellPadding: 3,
        },
        headStyles: {
          fillColor: [102, 126, 234],
          textColor: 255,
          fontStyle: 'bold'
        },
        alternateRowStyles: {
          fillColor: [249, 249, 249]
        }
      });
      
      doc.save('activities-report.pdf');
    }

    function exportToExcel() {
      const tableData = getTableData();
      const ws = XLSX.utils.aoa_to_sheet([
        ['ID', 'Activity', 'Description', 'Status'],
        ...tableData
      ]);
      
      const wb = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(wb, ws, 'Activities');
      
      XLSX.writeFile(wb, 'activities-report.xlsx');
    }

    function getTableData() {
      const data = [];
      filteredRows.forEach(row => {
        const cells = row.querySelectorAll('td');
        if (cells.length > 0) {
          data.push([
            cells[0].textContent.trim(), // ID
            cells[1].textContent.trim(), // Activity
            cells[2] ? cells[2].textContent.trim() : 'N/A', // Description
            cells[4] ? cells[4].textContent.trim() : 'N/A' // Status
          ]);
        }
      });
      return data;
    }

    function generatePrintableTable() {
      const tableData = getTableData();
      let html = `
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Activity</th>
              <th>Description</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
      `;
      
      tableData.forEach(row => {
        html += '<tr>';
        row.forEach(cell => {
          html += `<td>${cell}</td>`;
        });
        html += '</tr>';
      });
      
      html += '</tbody></table>';
      return html;
    }
  </script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>