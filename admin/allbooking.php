<?php
include __DIR__ . '/auth-check.php';
require '../connection.php';


// Get fresh data from the trip_booking table
$stmt = $conn->prepare("SELECT * FROM trip_bookings ORDER BY id ");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Booking Management - ThankYouNepalTrip</title>

  <script src="https://cdn.tailwindcss.com"></script>
  
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
  <link rel="stylesheet" href="frontend/sidebar.css">
</head>

<body class="bg-gray-50 font-sans leading-normal tracking-normal" x-data="{ sidebarOpen: false }">
  <div class="overlay" :class="{ 'open': sidebarOpen }" @click="sidebarOpen = false"></div>

  <?php include 'frontend/header.php'; ?>

  <?php include 'frontend/sidebar.php'; ?>

  <main class="main-content pt-16 min-h-screen transition-all duration-300">
    <div class="p-6">
      <div class="bg-white rounded-xl shadow-md p-6">
        <div class="mb-8">
          <div class="gradient-bg rounded-2xl p-6 text-white">
            <div class="flex justify-between items-center">
              <div>
                <h1 class="text-3xl font-bold mb-2">
                  <i class="fas fa-bookmark mr-3"></i>Booking Management
                </h1>
                <p class="text-blue-100">Manage and monitor all bookings</p>
              </div>
              <div class="text-right">
                <div class="text-2xl font-bold" id="totalEntriesDisplay">
                  <?php echo $result->num_rows; ?>
                </div>
                <div class="text-blue-100">Total Bookings</div>
              </div>
            </div>
          </div>
        </div>

        <?php include 'frontend/exportdata.php'; ?>

        <div class="glass-effect rounded-2xl shadow-xl overflow-hidden">
          <div class="overflow-x-auto custom-scrollbar">
            <table class="min-w-full" id="data-table">
              <thead class="gradient-bg text-white">
                <tr>
                  <th class="py-4 px-6 text-left font-semibold"><i class="fas fa-fingerprint mr-2"></i>ID</th>
                  <th class="py-4 px-6 text-left font-semibold"><i class="fas fa-signature mr-2"></i>Full Name</th>
                  <th class="py-4 px-6 text-left font-semibold hidden-mobile"><i class="fas fa-route mr-2"></i>Trip Name</th>
                  <th class="py-4 px-6 text-left font-semibold hidden-mobile"><i class="fas fa-envelope mr-2"></i>Email</th>
                  <th class="py-4 px-6 text-left font-semibold hidden-mobile"><i class="fas fa-phone mr-2"></i>Phone</th>
                  <th class="py-4 px-6 text-left font-semibold hidden-mobile"><i class="fas fa-calendar-alt mr-2"></i>Arrival Date</th>
                  <th class="py-4 px-6 text-left font-semibold hidden-mobile"><i class="fas fa-calendar-check mr-2"></i>Departure Date</th>
                  <th class="py-4 px-6 text-left font-semibold hidden-mobile"><i class="fas fa-users mr-2"></i>Guests</th>
                  <th class="py-4 px-6 text-left font-semibold"><i class="fas fa-money-check-alt mr-2"></i>Payment Status</th>
                  <th class="py-4 px-6 text-left font-semibold hidden-mobile"><i class="fas fa-credit-card mr-2"></i>Action</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                <?php
                if ($result->num_rows > 0) {
                  while ($booking = $result->fetch_assoc()) {
                    $paymentStatusClass = '';
                    $paymentStatusText = '';
                    switch(strtolower($booking['payment_status'])) {
                      case 'paid':
                        $paymentStatusClass = 'bg-green-500';
                        $paymentStatusText = 'Completed';
                        break;
                      case 'not paid':
                        $paymentStatusClass = 'bg-red-500';
                        $paymentStatusText = 'Not Completed';
                        break;
                      default:
                        $paymentStatusClass = 'bg-gray-500';
                        $paymentStatusText = 'Unknown';
                    }
                ?>
                <tr class="table-row hover:bg-gray-50">
                  <td class="py-4 px-6">
                    <span class="font-mono text-sm text-gray-600"><?php echo htmlspecialchars($booking["id"]); ?></span>
                  </td>
                  <td class="py-4 px-6">
                    <div class="font-semibold text-gray-900">
                      <?php echo htmlspecialchars($booking["full_name"]); ?>
                    </div>
                  </td>
                  <td class="py-4 px-6 hidden-mobile">
                    <span class="text-gray-700"><?php echo htmlspecialchars($booking["trip_name"]); ?></span>
                  </td>
                  <td class="py-4 px-6 hidden-mobile">
                    <a href="mailto:<?php echo htmlspecialchars($booking["email"]); ?>" 
                       class="text-blue-600 hover:text-blue-800 transition-colors">
                      <?php echo htmlspecialchars($booking["email"]); ?>
                    </a>
                  </td>
                  <td class="py-4 px-6 hidden-mobile">
                    <a href="tel:<?php echo htmlspecialchars($booking["phone_number"]); ?>" 
                       class="text-green-600 hover:text-green-800 transition-colors">
                      <?php echo htmlspecialchars($booking["phone_number"] ?: 'N/A'); ?>
                    </a>
                  </td>
                  <td class="py-4 px-6 hidden-mobile">
                    <div class="text-sm">
                      <div><?php echo htmlspecialchars($booking["arrival_date"]); ?></div>
                    </div>
                  </td>
                  <td class="py-4 px-6 hidden-mobile">
                    <div class="text-sm">
                      <div><?php echo htmlspecialchars($booking["departure_date"]); ?></div>
                    </div>
                  </td>
                  <td class="py-4 px-6 hidden-mobile">
                    <div class="text-sm text-gray-700">
                      Adults: <?php echo htmlspecialchars($booking["adults"]); ?> <br>
                      Children: <?php echo htmlspecialchars($booking["children"]); ?>
                    </div>
                  </td>
                  <td class="py-4 px-6">
                    <span class="<?php echo $paymentStatusClass; ?> text-white px-3 py-1 rounded-full text-xs font-semibold">
                      <?php echo $paymentStatusText; ?>
                    </span>
                  </td>
                  <td class="py-4 px-6">
                    <div class="flex space-x-2">
                      <a href="editbooking?id=<?php echo urlencode($booking['id']); ?>" 
                         class="action-button bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-lg transition-colors">
                        <i class="fas fa-edit"></i>
                      </a>
                      <a href="deletebooking?id=<?php echo urlencode($booking['id']); ?>" 
                         class="action-button bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition-colors"
                         onclick="return confirm('Are you sure you want to delete this booking?')">
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
                  <td colspan="10" class="py-8 px-6 text-center text-gray-500">
                    <i class="fas fa-bookmark-slash text-4xl mb-4 block"></i>
                    <p class="text-lg">No bookings found</p>
                  </td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>

          <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
              <div class="text-sm text-gray-600">
                Showing <span id="startEntry">1</span> to <span id="endEntry">10</span> of <span id="totalEntries"><?php echo $result->num_rows; ?></span> entries
              </div>
              <div class="flex space-x-2" id="pagination">
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  
  <script>
    // Initialize sidebar state
    document.addEventListener('alpine:init', () => {
      Alpine.data('main', () => ({
        sidebarOpen: window.innerWidth >= 1024,
        
        init() {
          if (window.innerWidth < 1024) {
            this.sidebarOpen = false;
          }
          
          window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
              this.sidebarOpen = true;
            }
          });
        }
      }));
    });

    // Table functionality
    let currentPage = 1;
    let entriesPerPage = 10;
    let allRows = [];
    let filteredRows = [];

    document.addEventListener('DOMContentLoaded', function() {
      allRows = Array.from(document.querySelectorAll('#data-table tbody tr')).filter(row => 
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
      allRows.forEach(row => row.style.display = 'none');
      
      const totalEntries = filteredRows.length;
      const startIndex = (currentPage - 1) * entriesPerPage;
      const endIndex = Math.min(startIndex + entriesPerPage, totalEntries);
      
      for (let i = startIndex; i < endIndex; i++) {
        if (filteredRows[i]) {
          filteredRows[i].style.display = '';
        }
      }
      
      document.getElementById('startEntry').textContent = totalEntries > 0 ? startIndex + 1 : 0;
      document.getElementById('endEntry').textContent = endIndex;
      document.getElementById('totalEntries').textContent = totalEntries;
      
      updatePagination(totalEntries);
    }

    function updatePagination(totalEntries) {
      const totalPages = Math.ceil(totalEntries / entriesPerPage);
      const paginationDiv = document.getElementById('pagination');
      paginationDiv.innerHTML = '';
      
      if (totalPages <= 1) return;
      
      if (currentPage > 1) {
        paginationDiv.innerHTML += `
          <button onclick="changePage(${currentPage - 1})" 
                    class="px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
            <i class="fas fa-chevron-left"></i>
          </button>`;
      }
      
      for (let i = Math.max(1, currentPage - 2); i <= Math.min(totalPages, currentPage + 2); i++) {
        const activeClass = i === currentPage ? 'bg-blue-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-50';
        paginationDiv.innerHTML += `
          <button onclick="changePage(${i})" 
                    class="px-3 py-2 text-sm border border-gray-300 rounded-lg transition-colors ${activeClass}">
            ${i}
          </button>`;
      }
      
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
          <title>Booking Report</title>
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
          <h1>Booking Report</h1>
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
      
      doc.setFontSize(18);
      doc.text('Booking Report', 14, 22);
      
      doc.setFontSize(10);
      doc.text(`Generated on: ${new Date().toLocaleString()}`, 14, 32);
      
      const tableData = getTableData();
      
      doc.autoTable({
        head: [['ID', 'Full Name', 'Trip Name', 'Email', 'Phone', 'Arrival', 'Departure', 'Adults', 'Children', 'Payment Status', 'Payment Mode']],
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
      
      doc.save('booking-report.pdf');
    }

    function exportToExcel() {
      const tableData = getTableData();
      const ws = XLSX.utils.aoa_to_sheet([
        ['ID', 'Full Name', 'Trip Name', 'Email', 'Phone', 'Arrival Date', 'Departure Date', 'Adults', 'Children', 'Payment Status', 'Payment Mode'],
        ...tableData
      ]);
      
      const wb = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(wb, ws, 'Bookings');
      
      XLSX.writeFile(wb, 'booking-report.xlsx');
    }

    function getTableData() {
      const data = [];
      filteredRows.forEach(row => {
        const cells = row.querySelectorAll('td');
        if (cells.length > 0) {
          data.push([
            cells[0].textContent.trim(), // ID
            cells[1].textContent.trim(), // Full Name
            cells[2].textContent.trim(), // Trip Name
            cells[3].textContent.trim(), // Email
            cells[4].textContent.trim(), // Phone
            cells[5].textContent.trim(), // Arrival Date
            cells[6].textContent.trim(), // Departure Date
            cells[7].textContent.trim().split('Adults: ')[1].split(' ')[0], // Adults
            cells[7].textContent.trim().split('Children: ')[1], // Children
            cells[8].textContent.trim(), // Payment Status
            cells[9].textContent.trim() // Payment Mode
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
              <th>Full Name</th>
              <th>Trip Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Arrival Date</th>
              <th>Departure Date</th>
              <th>Adults</th>
              <th>Children</th>
              <th>Payment Status</th>
              <th>Payment Mode</th>
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