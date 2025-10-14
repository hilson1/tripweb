<?php
require '../connection.php';

// Get fresh data for display
$stmt = $conn->prepare("SELECT * FROM users ORDER BY userid DESC");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Edit Users - ThankYouNepalTrip</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  
  <!-- FontAwesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
  
  <style>
    .status-active {
      background-color: #10b981;
    }
    .status-inactive {
      background-color: #6b7280;
    }
    .status-suspended {
      background-color: #ef4444;
    }
    .gradient-bg {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .glass-effect {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
    }
    .custom-scrollbar::-webkit-scrollbar {
      height: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
      background: #c1c1c1;
      border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
      background: #a8a8a8;
    }
    .table-row {
      transition: all 0.2s ease-in-out;
    }
    .table-row:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.5);
      z-index: 40;
    }
    .overlay.open {
      display: block;
    }
    @media (max-width: 768px) {
      .hidden-mobile {
        display: none;
      }
    }
    [x-cloak] {
      display: none !important;
    }
  </style>
</head>

<body class="bg-gray-50 font-sans leading-normal tracking-normal" x-data="{ sidebarOpen: window.innerWidth >= 1024 }" x-cloak>
  <!-- Overlay for mobile sidebar -->
  <div x-show="sidebarOpen && window.innerWidth < 1024" 
       @click="sidebarOpen = false" 
       class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden">
  </div>

  <!-- Top Navigation Bar -->
  <?php include 'frontend/header.php'; ?>

  <!-- Sidebar -->
  <?php include 'frontend/sidebar.php'; ?>

  <!-- Main Content Area -->
  <main class="main-content pt-16 min-h-screen transition-all duration-300" :class="{ 'lg:ml-64': sidebarOpen }">
    <div class="p-6">
      <!-- Users Management Content -->
      <div class="bg-white rounded-xl shadow-md p-6">
        <!-- Header Section -->
        <div class="mb-8">
          <div class="gradient-bg rounded-2xl p-6 text-white">
            <div class="flex justify-between items-center">
              <div>
                <h1 class="text-3xl font-bold mb-2">
                  <i class="fas fa-users mr-3"></i>Users Management
                </h1>
                <p class="text-blue-100">Manage and monitor all user accounts</p>
              </div>
              <div class="text-right">
                <div class="text-2xl font-bold" id="totalUsers">
                  <?php echo $result->num_rows; ?>
                </div>
                <div class="text-blue-100">Total Users</div>
              </div>
            </div>
          </div>
        </div>
      
        <!-- Export and Search Section -->
        <div class="mb-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
          <!-- Search Box -->
          <div class="w-full lg:w-auto">
            <div class="relative">
              <input type="text" 
                     id="search" 
                     placeholder="Search users..." 
                     class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full lg:w-64">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
              </div>
            </div>
          </div>
          
          <!-- Entries and Export Buttons -->
          <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
            <!-- Entries Select -->
            <div class="flex items-center gap-2">
              <label class="text-sm text-gray-600">Show</label>
              <select id="entries" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                <option value="5">5</option>
                <option value="10" selected>10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
              </select>
              <label class="text-sm text-gray-600">entries</label>
            </div>
            
            <!-- Export Buttons -->
            <div class="flex gap-2">
              <button type="button" id="printBtn" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition flex items-center gap-2">
                <i class="fas fa-print"></i> Print
              </button>
              <button type="button" id="pdfBtn" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm transition flex items-center gap-2">
                <i class="fas fa-file-pdf"></i> PDF
              </button>
              <button type="button" id="excelBtn" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm transition flex items-center gap-2">
                <i class="fas fa-file-excel"></i> Excel
              </button>
            </div>
          </div>
        </div>

        <!-- Table Section -->
        <div class="glass-effect rounded-2xl shadow-xl overflow-hidden">
          <div class="overflow-x-auto custom-scrollbar">
            <table class="min-w-full" id="usersTable">
              <thead class="gradient-bg text-white">
                <tr>
                  <th class="py-4 px-6 text-left font-semibold">
                    <i class="fas fa-id-card mr-2"></i>User ID
                  </th>
                  <th class="py-4 px-6 text-left font-semibold">
                    <i class="fas fa-user mr-2"></i>Name
                  </th>
                  <th class="py-4 px-6 text-left font-semibold hidden-mobile">
                    <i class="fas fa-at mr-2"></i>Username
                  </th>
                  <th class="py-4 px-6 text-left font-semibold hidden-mobile">
                    <i class="fas fa-envelope mr-2"></i>Email
                  </th>
                  <th class="py-4 px-6 text-left font-semibold hidden-mobile">
                    <i class="fas fa-phone mr-2"></i>Phone
                  </th>
                  <th class="py-4 px-6 text-left font-semibold hidden-mobile">
                    <i class="fas fa-map-marker-alt mr-2"></i>Location
                  </th>
                  <th class="py-4 px-6 text-left font-semibold">
                    <i class="fas fa-info-circle mr-2"></i>Status
                  </th>
                  <th class="py-4 px-6 text-left font-semibold">
                    <i class="fas fa-cog mr-2"></i>Actions
                  </th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100" id="usersTableBody">
                <?php
                if ($result->num_rows > 0) {
                  while ($user = $result->fetch_assoc()) {
                    $statusClass = '';
                    $statusText = '';
                    switch($user['status']) {
                      case 'active':
                        $statusClass = 'status-active';
                        $statusText = 'Active';
                        break;
                      case 'inactive':
                        $statusClass = 'status-inactive';
                        $statusText = 'Inactive';
                        break;
                      case 'suspended':
                        $statusClass = 'status-suspended';
                        $statusText = 'Suspended';
                        break;
                      default:
                        $statusClass = 'bg-gray-500';
                        $statusText = 'Unknown';
                    }
                ?>
                <tr class="table-row hover:bg-gray-50" data-userid="<?php echo $user['userid']; ?>">
                  <td class="py-4 px-6">
                    <div class="flex items-center">
                      <div class="w-10 h-10 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3">
                        <?php echo strtoupper(substr($user["first_name"], 0, 1) . substr($user["last_name"], 0, 1)); ?>
                      </div>
                      <span class="font-mono text-sm text-gray-600"><?php echo htmlspecialchars($user["userid"]); ?></span>
                    </div>
                  </td>
                  <td class="py-4 px-6">
                    <div>
                      <div class="font-semibold text-gray-900">
                        <?php echo htmlspecialchars($user["first_name"] . " " . $user["last_name"]); ?>
                      </div>
                      <div class="text-sm text-gray-500 lg:hidden">
                        @<?php echo htmlspecialchars($user["user_name"]); ?>
                      </div>
                    </div>
                  </td>
                  <td class="py-4 px-6 hidden-mobile">
                    <span class="text-gray-700">@<?php echo htmlspecialchars($user["user_name"]); ?></span>
                  </td>
                  <td class="py-4 px-6 hidden-mobile">
                    <a href="mailto:<?php echo htmlspecialchars($user["email"]); ?>" 
                       class="text-blue-600 hover:text-blue-800 transition-colors">
                      <?php echo htmlspecialchars($user["email"]); ?>
                    </a>
                  </td>
                  <td class="py-4 px-6 hidden-mobile">
                    <a href="tel:<?php echo htmlspecialchars($user["phone_number"]); ?>" 
                       class="text-green-600 hover:text-green-800 transition-colors">
                      <?php echo htmlspecialchars($user["phone_number"] ?: 'N/A'); ?>
                    </a>
                  </td>
                  <td class="py-4 px-6 hidden-mobile">
                    <div class="text-sm">
                      <div><?php echo htmlspecialchars($user["address"] ?: 'N/A'); ?></div>
                      <?php if ($user["country"]): ?>
                        <div class="text-gray-500"><?php echo htmlspecialchars($user["country"]); ?></div>
                      <?php endif; ?>
                    </div>
                  </td>
                  <td class="py-4 px-6">
                    <span class="<?php echo $statusClass; ?> text-white px-3 py-1 rounded-full text-xs font-semibold">
                      <?php echo $statusText; ?>
                    </span>
                  </td>
                  <td class="py-4 px-6">
                    <div class="flex justify-start space-x-2">
                      <!-- Edit Button -->
                      <button type="button" 
                              class="edit-btn bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg text-sm transition flex items-center gap-1"
                              data-userid="<?php echo $user['userid']; ?>">
                        <i class="fas fa-edit text-xs"></i>
                        <span>Edit</span>
                      </button>

                      <!-- Delete Button -->
                      <button type="button" 
                              class="delete-btn bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg text-sm transition flex items-center gap-1"
                              data-userid="<?php echo $user['userid']; ?>">
                        <i class="fas fa-trash text-xs"></i>
                        <span>Delete</span>
                      </button>
                    </div>
                  </td>
                </tr>
                <?php 
                  }
                } else {
                ?>
                <tr>
                  <td colspan="8" class="py-8 px-6 text-center text-gray-500">
                    <i class="fas fa-users-slash text-4xl mb-4 block"></i>
                    <p class="text-lg">No users found</p>
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

  <!-- Alpine JS -->
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.0/dist/cdn.min.js" defer></script>
  
  <script>
    // Table functionality
    let currentPage = 1;
    let entriesPerPage = 10;
    let allRows = [];
    let filteredRows = [];

    // Initialize when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
      console.log('Initializing users management system...');
      
      // Initialize table functionality
      initializeTable();
      
      // Set up all event listeners
      setupEventListeners();
    });

    function setupEventListeners() {
      // Search functionality
      document.getElementById('search').addEventListener('input', searchTable);
      
      // Entries select
      document.getElementById('entries').addEventListener('change', changeEntries);
      
      // Export buttons
      document.getElementById('printBtn').addEventListener('click', printTable);
      document.getElementById('pdfBtn').addEventListener('click', exportToPDF);
      document.getElementById('excelBtn').addEventListener('click', exportToExcel);
      
      // Action buttons - Event delegation
      document.getElementById('usersTableBody').addEventListener('click', function(e) {
        const target = e.target;
        
        // Handle edit buttons
        if (target.closest('.edit-btn')) {
          const button = target.closest('.edit-btn');
          const userid = button.getAttribute('data-userid');
          editUser(userid);
        }
        
        // Handle delete buttons
        if (target.closest('.delete-btn')) {
          const button = target.closest('.delete-btn');
          const userid = button.getAttribute('data-userid');
          deleteUser(userid);
        }
      });
    }

    // Action Functions
    function editUser(userid) {
      console.log('Editing user:', userid);
      window.location.href = `edit_user.php?id=${userid}`;
    }

    function deleteUser(userid) {
      console.log('Deleting user:', userid);
      if (confirm('Are you sure you want to delete user ID ' + userid + '? This action cannot be undone.')) {
        window.location.href = `delete_user.php?id=${userid}`;
      }
    }

    function initializeTable() {
      allRows = Array.from(document.querySelectorAll('#usersTable tbody tr')).filter(row => 
        !row.querySelector('td[colspan]')
      );
      filteredRows = [...allRows];
      updateTable();
    }

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
        const prevButton = document.createElement('button');
        prevButton.innerHTML = '<i class="fas fa-chevron-left"></i>';
        prevButton.className = 'px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors';
        prevButton.addEventListener('click', () => changePage(currentPage - 1));
        paginationDiv.appendChild(prevButton);
      }
      
      // Page numbers
      for (let i = Math.max(1, currentPage - 2); i <= Math.min(totalPages, currentPage + 2); i++) {
        const activeClass = i === currentPage ? 'bg-blue-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-50';
        const pageButton = document.createElement('button');
        pageButton.textContent = i;
        pageButton.className = `px-3 py-2 text-sm border border-gray-300 rounded-lg transition-colors ${activeClass}`;
        pageButton.addEventListener('click', () => changePage(i));
        paginationDiv.appendChild(pageButton);
      }
      
      // Next button
      if (currentPage < totalPages) {
        const nextButton = document.createElement('button');
        nextButton.innerHTML = '<i class="fas fa-chevron-right"></i>';
        nextButton.className = 'px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors';
        nextButton.addEventListener('click', () => changePage(currentPage + 1));
        paginationDiv.appendChild(nextButton);
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
          <title>Users Report - ThankYouNepalTrip</title>
          <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            h1 { color: #333; text-align: center; margin-bottom: 30px; }
            .header { text-align: center; margin-bottom: 30px; }
            .header h1 { color: #333; margin: 0; }
            .header p { color: #666; margin: 5px 0; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
            th { background-color: #f5f5f5; font-weight: bold; color: #333; }
            tr:nth-child(even) { background-color: #f9f9f9; }
            .print-date { text-align: right; color: #666; margin-bottom: 20px; font-size: 14px; }
            .summary { margin-bottom: 20px; padding: 15px; background: #f8f9fa; border-radius: 5px; }
          </style>
        </head>
        <body>
          <div class="header">
            <h1>ThankYouNepalTrip - Users Report</h1>
            <p>Generated on: ${new Date().toLocaleString()}</p>
          </div>
          <div class="summary">
            <strong>Total Users:</strong> ${document.getElementById('totalEntries').textContent}
          </div>
          ${tableHTML}
        </body>
        </html>
      `);
      
      printWindow.document.close();
      printWindow.focus();
      setTimeout(() => {
        printWindow.print();
        printWindow.close();
      }, 500);
    }

    function exportToPDF() {
      const { jsPDF } = window.jspdf;
      const doc = new jsPDF('l', 'mm', 'a4');
      
      // Add title and header
      doc.setFontSize(20);
      doc.setTextColor(40);
      doc.text('ThankYouNepalTrip - Users Report', 14, 22);
      
      doc.setFontSize(10);
      doc.setTextColor(100);
      doc.text(`Generated on: ${new Date().toLocaleString()}`, 14, 32);
      doc.text(`Total Users: ${document.getElementById('totalEntries').textContent}`, 14, 38);
      
      // Get table data
      const tableData = getTableData();
      
      // Generate PDF table
      doc.autoTable({
        head: [['User ID', 'Name', 'Username', 'Email', 'Phone', 'Country', 'Status']],
        body: tableData,
        startY: 45,
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
        },
        margin: { top: 45 }
      });
      
      doc.save(`users-report-${new Date().toISOString().split('T')[0]}.pdf`);
    }

    function exportToExcel() {
      const tableData = getTableData();
      const ws = XLSX.utils.aoa_to_sheet([
        ['ThankYouNepalTrip - Users Report'],
        [`Generated on: ${new Date().toLocaleString()}`],
        [`Total Users: ${document.getElementById('totalEntries').textContent}`],
        [], // empty row
        ['User ID', 'Name', 'Username', 'Email', 'Phone', 'Country', 'Status'],
        ...tableData
      ]);
      
      // Merge header cells
      if (!ws['!merges']) ws['!merges'] = [];
      ws['!merges'].push({ s: { r: 0, c: 0 }, e: { r: 0, c: 6 } });
      ws['!merges'].push({ s: { r: 1, c: 0 }, e: { r: 1, c: 6 } });
      ws['!merges'].push({ s: { r: 2, c: 0 }, e: { r: 2, c: 6 } });
      
      const wb = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(wb, ws, 'Users Report');
      
      XLSX.writeFile(wb, `users-report-${new Date().toISOString().split('T')[0]}.xlsx`);
    }

    function getTableData() {
      const data = [];
      filteredRows.forEach(row => {
        const cells = row.querySelectorAll('td');
        if (cells.length > 0) {
          data.push([
            cells[0].textContent.trim().split('\n').pop() || 'N/A',
            cells[1].textContent.trim().split('\n')[0] || 'N/A',
            cells[2] ? cells[2].textContent.trim() : 'N/A',
            cells[3] ? cells[3].textContent.trim() : 'N/A',
            cells[4] ? cells[4].textContent.trim() : 'N/A',
            cells[5] ? cells[5].textContent.trim().split('\n').pop() || 'N/A' : 'N/A',
            cells[6] ? cells[6].textContent.trim() : 'N/A'
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
              <th>User ID</th>
              <th>Name</th>
              <th>Username</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Country</th>
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