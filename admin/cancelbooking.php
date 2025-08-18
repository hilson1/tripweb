<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
  <style>
    /* Hide additional columns on smaller screens */
    @media (max-width: 768px) {
      .hidden-on-mobile {
        display: none;
      }

      .expand-button {
        display: inline-block;
      }
    }

    .expand-button {
      display: none;
      cursor: pointer;
      color: #4F46E5;
      /* Tailwind's indigo-600 */
    }
  </style>
  <script>
    // Function to fetch data from the backend
    async function fetchData() {
      try {
        const response = await fetch('/api/users'); // Replace with your backend API endpoint
        if (!response.ok) {
          throw new Error('Failed to fetch data');
        }
        const data = await response.json();
        populateTable(data);
      } catch (error) {
        console.error('Error fetching data:', error);
      }
    }

    // Function to populate the table with data
    function populateTable(data) {
      const tbody = document.querySelector('tbody');
      tbody.innerHTML = ''; // Clear existing rows

      data.forEach((user, index) => {
        const row = `
          <tr class="border-b border-gray-200 hover:bg-gray-100">
            <td class="py-3 px-6 text-left">${user.id}</td>
            <td class="py-3 px-6 text-left">${user.firstName}</td>
            <td class="py-3 px-6 text-left">${user.lastName}</td>
            <td class="py-3 px-6 text-left hidden-on-mobile">${user.username}</td>
            <td class="py-3 px-6 text-left hidden-on-mobile">${user.email}</td>
            <td class="py-3 px-6 text-left hidden-on-mobile">${user.phone}</td>
            <td class="py-3 px-6 text-left hidden-on-mobile">
              <span class="${getStatusClass(user.status)} py-1 px-3 rounded-full text-xs">
                ${user.status}
              </span>
            </td>
            <td class="py-3 px-6 text-left hidden-on-mobile">
              <div class="flex item-center justify-center">
                <button class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110">
                  <i class="fas fa-edit"></i>
                </button>
                <button class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </div>
            </td>
            <td class="py-3 px-6 text-left">
              <span class="expand-button" onclick="toggleDetails(this)">+</span>
            </td>
          </tr>
        `;
        tbody.insertAdjacentHTML('beforeend', row);
      });
    }

    // Helper function to get status class based on status
    function getStatusClass(status) {
      switch (status.toLowerCase()) {
        case 'active':
          return 'bg-green-200 text-green-600';
        case 'expired':
          return 'bg-red-200 text-red-600';
        case 'draft':
          return 'bg-yellow-200 text-yellow-600';
        case 'featured':
          return 'bg-orange-200 text-orange-600';
        default:
          return 'bg-gray-200 text-gray-600';
      }
    }

    // Function to toggle additional details on smaller screens
    function toggleDetails(button) {
      const row = button.closest('tr');
      const hiddenColumns = row.querySelectorAll('.hidden-on-mobile');

      hiddenColumns.forEach((column) => {
        if (column.style.display === 'none' || column.style.display === '') {
          column.style.display = 'table-cell';
          button.textContent = '-';
        } else {
          column.style.display = 'none';
          button.textContent = '+';
        }
      });
    }

    // Fetch data when the page loads
    document.addEventListener('DOMContentLoaded', fetchData);
  </script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
      dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function () {
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
    <?php include("frontend/asidebar.php"); ?>


    <!-- Main Content -->
    <div class="ml-64 p-6 w-[84%] mx-auto mt-16">
      <div class="bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-4"><br></h1>
        <div class="bg-gray-100 p-4 rounded-lg mb-6">
          <h2 class="text-xl font-semibold mb-4">All Booking</h2>
          <form>
            <!-- Search Bar -->
            <div class="mt-4 mb-6">
              <input type="text" id="searchInput" placeholder="Search users..."
                class="w-full md:w-1/3 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <!-- User Table -->
            <div class="bg-white p-6 rounded-lg shadow-lg overflow-x-auto">
              <table class="min-w-full" id="userTable">
                <thead>
                  <tr class="bg-gray-50">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vehicle</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duration</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                  <tr>
                    <td class="px-6 py-4">Ram </td>
                    <td class="px-6 py-4">9764318052</td>
                    <td class="px-6 py-4">Kathmandu</td>
                    <td class="px-6 py-4">plane</td>
                    <td class="px-6 py-4">15 days</td>
                    <td class="px-6 py-4">paid</td>
                    <td class="px-6 py-4"><span
                        class="px-2 py-1 text-sm bg-red-100 text-green-800 rounded-full">Cancelled</span></td>
                    <td class="px-6 py-4">
                      <button class="text-blue-500 hover:text-blue-700">Edit</button>
                      <button class="text-red-500 hover:text-red-700 ml-2 delete-btn">Delete</button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>

  </div>
</body>

</html>