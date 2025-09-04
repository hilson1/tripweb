<?php
require_once('frontend/connection.php');

// Handle delete request
if (isset($_POST['delete_item'])) {
    $item_id = $_POST['item_id'] ?? null;

    if (!empty($item_id) && is_numeric($item_id)) {
        $stmt = $conn->prepare("DELETE FROM itinerary WHERE itinerary_id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $item_id);
            if ($stmt->execute()) {
                header("Location: allitinerary.php?delete=success");
                exit();
            } else {
                $delete_error = "Error executing delete: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $delete_error = "Error preparing delete statement: " . $conn->error;
        }
    } else {
        $delete_error = "Invalid itinerary ID";
    }
}

// Fetch all itinerary data with trip name
$sql = "
    SELECT i.itinerary_id, i.tripid, i.day_number, i.title, i.description, t.title AS trip_name
    FROM itinerary i
    LEFT JOIN trips t ON i.tripid = t.tripid
    ORDER BY i.tripid, i.day_number
";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>All Itineraries - ThankYouNepalTrip</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="frontend/sidebar.css">
</head>
<body class="bg-gray-50 font-sans leading-normal tracking-normal">

  <!-- Top Navigation Bar -->
  <?php include('frontend/header.php'); ?>

  <!-- Sidebar -->
  <?php include('frontend/sidebar.php'); ?>

  <!-- Main Content Area -->
  <main class="main-content pt-16 min-h-screen transition-all duration-300">
    <div class="p-6">

      <!-- Header Card -->
      <div class="bg-white rounded-xl shadow-md p-6 mb-6 flex justify-between items-center flex-wrap">
        <div>
          <h1 class="text-2xl sm:text-3xl font-bold mb-2"><i class="fas fa-route mr-2"></i>Itineraries Management</h1>
          <p class="text-gray-500">Manage and monitor all itineraries</p>
        </div>
        <div class="text-right mt-4 sm:mt-0">
          <div class="text-2xl font-bold"><?= $result->num_rows ?></div>
          <div class="text-gray-400">Total Itineraries</div>
        </div>
      </div>

      <!-- Error Message -->
      <?php if (isset($delete_error)): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= htmlspecialchars($delete_error) ?></div>
      <?php endif; ?>

      <!-- Search & Entries -->
      <div class="flex flex-col sm:flex-row justify-between items-center mb-4 space-y-3 sm:space-y-0 w-full">
        <div class="w-full sm:w-auto">
          <label class="flex items-center space-x-2">
            <span>Show</span>
            <select id="entries" class="border rounded p-1 w-full sm:w-20" onchange="changeEntries()">
              <option value="5">5</option>
              <option value="10" selected>10</option>
              <option value="25">25</option>
              <option value="50">50</option>
            </select>
            <span>entries</span>
          </label>
        </div>
        <div class="w-full sm:w-64">
          <input type="text" id="search" onkeyup="searchTable()" placeholder="Search..." 
                 class="border rounded p-2 w-full">
        </div>
      </div>

      <!-- Table Card -->
      <div class="bg-white rounded-2xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm" id="itineraryTable">
            <thead class="bg-gradient-to-r from-blue-500 to-purple-500 text-white">
              <tr>
                <th class="py-3 px-4 text-left font-semibold whitespace-nowrap"><i class="fas fa-id-badge mr-1"></i>ID</th>
                <th class="py-3 px-4 text-left font-semibold whitespace-nowrap"><i class="fas fa-route mr-1"></i>Trip</th>
                <th class="py-3 px-4 text-left font-semibold whitespace-nowrap"><i class="fas fa-calendar-day mr-1"></i>Day</th>
                <th class="py-3 px-4 text-left font-semibold whitespace-nowrap"><i class="fas fa-heading mr-1"></i>Title</th>
                <th class="py-3 px-4 text-left font-semibold"><i class="fas fa-align-left mr-1"></i>Description</th>
                <th class="py-3 px-4 text-left font-semibold whitespace-nowrap"><i class="fas fa-cog mr-1"></i>Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                  <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4 whitespace-nowrap"><?= htmlspecialchars($row['itinerary_id']) ?></td>
                    <td class="py-3 px-4 whitespace-nowrap"><?= htmlspecialchars($row['trip_name'] ?? 'N/A') ?></td>
                    <td class="py-3 px-4 whitespace-nowrap"><?= htmlspecialchars($row['day_number']) ?></td>
                    <td class="py-3 px-4"><?= htmlspecialchars($row['title']) ?></td>
                    <td class="py-3 px-4"><?= htmlspecialchars($row['description']) ?></td>
                    <td class="py-3 px-4">
                      <div class="flex flex-col sm:flex-row sm:space-x-2 space-y-2 sm:space-y-0">
                        <a href="edititinerary.php?itinerary_id=<?= $row['itinerary_id'] ?>" 
                           class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-center">
                          <i class="fas fa-edit mr-1"></i>Edit
                        </a>
                        <form method="POST" class="inline" onsubmit="return confirmDelete(event, <?= $row['itinerary_id'] ?>)">
                          <input type="hidden" name="item_id" value="<?= $row['itinerary_id'] ?>">
                          <button type="submit" name="delete_item" 
                                  class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 w-full sm:w-auto">
                            <i class="fas fa-trash-alt mr-1"></i>Delete
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6" class="py-8 px-6 text-center text-gray-500">
                    <i class="fas fa-route text-4xl mb-4 block"></i>
                    <p class="text-lg">No itineraries found</p>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">
          <div class="flex flex-col sm:flex-row justify-between items-center text-xs sm:text-sm text-gray-600">
            <div>Showing <span id="startEntry">1</span> to <span id="endEntry">10</span> of <span id="totalEntries"><?= $result->num_rows ?></span> entries</div>
            <div class="flex flex-wrap space-x-2 mt-2 sm:mt-0" id="pagination"></div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
  // Delete Confirmation
  function confirmDelete(event, formElement) {
    event.preventDefault();
    Swal.fire({
      title: 'Are you sure?',
      text: "This itinerary will be deleted permanently!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.isConfirmed) {
        formElement.submit();
      }
    });
    return false;
  }

  // Table Variables
  let currentPage = 1;
  let entriesPerPage = 10;
  let allRows = [];
  let filteredRows = [];

  document.addEventListener('DOMContentLoaded', () => {
    allRows = Array.from(document.querySelectorAll('#itineraryTable tbody tr'))
      .filter(row => !row.querySelector('td[colspan]'));
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
    filteredRows = searchTerm === ''
      ? [...allRows]
      : allRows.filter(row =>
          Array.from(row.cells).some(cell =>
            cell.textContent.toLowerCase().includes(searchTerm)
          )
        );
    currentPage = 1;
    updateTable();
  }

  function updateTable() {
    // Hide all rows first
    allRows.forEach(row => {
      row.style.display = 'none';
      row.classList.remove("hover:bg-gray-50"); // reset
    });

    const totalEntries = filteredRows.length;
    const startIndex = (currentPage - 1) * entriesPerPage;
    const endIndex = Math.min(startIndex + entriesPerPage, totalEntries);

    // Show only visible rows & re-apply hover style
    for (let i = startIndex; i < endIndex; i++) {
      if (filteredRows[i]) {
        filteredRows[i].style.display = 'table-row';
        filteredRows[i].classList.add("hover:bg-gray-50", "transition-colors");
      }
    }

    // Update entry info
    document.getElementById('startEntry').textContent = totalEntries > 0 ? startIndex + 1 : 0;
    document.getElementById('endEntry').textContent = endIndex;
    document.getElementById('totalEntries').textContent = totalEntries;

    // Update pagination
    updatePagination(totalEntries);
  }

  function updatePagination(totalEntries) {
    const totalPages = Math.ceil(totalEntries / entriesPerPage);
    const paginationDiv = document.getElementById('pagination');
    paginationDiv.innerHTML = '';

    if (totalPages <= 1) return;

    // Prev button
    if (currentPage > 1) {
      paginationDiv.innerHTML += `
        <button onclick="changePage(${currentPage - 1})"
          class="px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition">
          <i class="fas fa-chevron-left"></i>
        </button>`;
    }

    // Page numbers
    for (let i = Math.max(1, currentPage - 2); i <= Math.min(totalPages, currentPage + 2); i++) {
      const activeClass = i === currentPage
        ? 'bg-blue-500 text-white'
        : 'bg-white text-gray-700 hover:bg-gray-100';
      paginationDiv.innerHTML += `
        <button onclick="changePage(${i})"
          class="px-3 py-2 text-sm border border-gray-300 rounded-lg transition ${activeClass}">
          ${i}
        </button>`;
    }

    // Next button
    if (currentPage < totalPages) {
      paginationDiv.innerHTML += `
        <button onclick="changePage(${currentPage + 1})"
          class="px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition">
          <i class="fas fa-chevron-right"></i>
        </button>`;
    }
  }

  function changePage(page) {
    currentPage = page;
    updateTable();
  }
</script>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
