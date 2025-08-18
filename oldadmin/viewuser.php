<?php
require '../connection.php';

// session_start();
// if (!isset($_SESSION['admin_id'])) {
//   header('location:adminlogin.php');
//   exit;
// }

$stmt = $conn->prepare("SELECT * FROM users");
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
    <?php
    include("frontend/asidebar.php");
    ?>
    <!-- Main Content -->
    <div class="flex-1 flex flex-col ml-64">
      <main class="flex-1 p-6 mt-16">
        <div class="container mx-auto bg-white p-4 rounded shadow">
          <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Users</h1>
            <div class="flex items-center space-x-2">
              <div class="flex space-x-2">
                <button class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600">
                  <i class="fas fa-print"></i> <span class="hidden sm:inline">Print</span>
                </button>
                <button class="bg-red-500 text-white p-2 rounded hover:bg-red-600">
                  <i class="fas fa-file-pdf"></i> <span class="hidden sm:inline">PDF</span>
                </button>
                <button class="bg-green-500 text-white p-2 rounded hover:bg-green-600">
                  <i class="fas fa-file-excel"></i> <span class="hidden sm:inline">Excel</span>
                </button>
              </div>
            </div>
          </div>
          <div class="flex justify-between items-center mb-4">
            <div>
              <label class="mr-2" for="entries">Show</label>
              <select class="border rounded p-1" id="entries">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
              </select>
              <span class="ml-2">entries</span>
            </div>
            <div class="flex items-center">
              <label class="mr-2" for="search">Search:</label>
              <input class="border rounded p-1" id="search" type="text" />
            </div>
          </div>
          <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
              <thead>
                <tr class="bg-gray-300 text-gray-600 uppercase text-sm leading-normal">
                  <th class="py-3 px-6 text-left">user ID</th>
                  <th class="py-3 px-6 text-left">First Name</th>
                  <th class="py-3 px-6 text-left">Last Name</th>
                  <th class="py-3 px-6 text-left hidden-on-mobile">User Name</th>
                  <th class="py-3 px-6 text-left hidden-on-mobile">Email</th>
                  <th class="py-3 px-6 text-left hidden-on-mobile">Phone</th>
                  <th class="py-3 px-6 text-left hidden-on-mobile">Address</th>
                  <th class="py-3 px-6 text-left hidden-on-mobile">ProfileIMG</th>
                  <th class="py-3 px-6 text-left"></th>
                </tr>
              </thead>
              <tbody>
                <?php
                if ($result->num_rows > 0) {
                  while ($user = $result->fetch_assoc()) {
                    ?>
                    <tr>
                      <td class="py-3 px-6 text-left"><?php echo $user["userid"]; ?></td>
                      <td class="py-3 px-6 text-left"><?php echo $user["first_name"]; ?></td>
                      <td class="py-3 px-6 text-left"><?php echo $user["last_name"]; ?></td>
                      <td class="py-3 px-6 text-left"><?php echo $user["user_name"]; ?></td>
                      <td class="py-3 px-6 text-left"><?php echo $user["email"]; ?></td>
                      <td class="py-3 px-6 text-left"><?php echo $user["phone_number"]; ?></td>
                      <td class="py-3 px-6 text-left"><?php echo $user["address"]; ?></td>
                      <td class="py-3 px-6 text-left"><?php echo $user["profilepic"]; ?></td>
                    </tr>
                  <?php }
                }
                $stmt->close();
                $conn->close();
                ?>
              </tbody>
              <tbody class=" text-gray-600 text-sm font-light">
                <!-- Rows will be populated dynamically -->
              </tbody>
            </table>
          </div>
        </div>
      </main>
    </div>
  </div>
</body>

</html>