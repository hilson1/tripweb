<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Dashboard - ThankYouNepalTrip</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  
  <!-- FontAwesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" />
  <link href="frontend/sidebar.css" rel="stylesheet">
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
      <!-- Your page content will go here -->
      <div class="bg-white rounded-xl shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800">Welcome to Admin Dashboard</h1>
        <p class="text-gray-600 mt-2">Select an option from the sidebar to get started</p>
        
        <!-- Sample content area - replace with your actual content -->
        <div class="mt-8">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Stat Card 1 -->
            <div class="bg-blue-50 rounded-lg p-6 border border-blue-100">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-blue-600">Total Users</p>
                  <p class="text-2xl font-bold mt-1 text-blue-900">1,254</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                  <i class="fas fa-users text-blue-600"></i>
                </div>
              </div>
              <p class="text-xs text-blue-500 mt-3"><span class="text-green-500">↑ 12%</span> from last month</p>
            </div>
            
            <!-- Stat Card 2 -->
            <div class="bg-purple-50 rounded-lg p-6 border border-purple-100">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-purple-600">Total Bookings</p>
                  <p class="text-2xl font-bold mt-1 text-purple-900">342</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                  <i class="fas fa-bookmark text-purple-600"></i>
                </div>
              </div>
              <p class="text-xs text-purple-500 mt-3"><span class="text-green-500">↑ 8%</span> from last month</p>
            </div>
            
            <!-- Stat Card 3 -->
            <div class="bg-green-50 rounded-lg p-6 border border-green-100">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-green-600">Active Trips</p>
                  <p class="text-2xl font-bold mt-1 text-green-900">56</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                  <i class="fas fa-suitcase-rolling text-green-600"></i>
                </div>
              </div>
              <p class="text-xs text-green-500 mt-3"><span class="text-green-500">↑ 5%</span> from last month</p>
            </div>
            
            <!-- Stat Card 4 -->
            <div class="bg-orange-50 rounded-lg p-6 border border-orange-100">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-orange-600">Revenue</p>
                  <p class="text-2xl font-bold mt-1 text-orange-900">$24,589</p>
                </div>
                <div class="bg-orange-100 p-3 rounded-full">
                  <i class="fas fa-dollar-sign text-orange-600"></i>
                </div>
              </div>
              <p class="text-xs text-orange-500 mt-3"><span class="text-green-500">↑ 15%</span> from last month</p>
            </div>
          </div>
          
          <!-- Recent Activity Section -->
          <div class="mt-8 bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 border-b border-gray-200">
              <h2 class="text-lg font-semibold text-gray-800">Recent Activities</h2>
            </div>
            <div class="divide-y divide-gray-200">
              <!-- Activity Item 1 -->
              <div class="p-6 hover:bg-gray-50 transition">
                <div class="flex items-start">
                  <div class="bg-blue-100 p-2 rounded-full mr-4">
                    <i class="fas fa-user-plus text-blue-600 text-sm"></i>
                  </div>
                  <div class="flex-1">
                    <p class="text-sm font-medium text-gray-800">New user registered</p>
                    <p class="text-sm text-gray-500 mt-1">John Doe registered as a new customer</p>
                    <p class="text-xs text-gray-400 mt-2">2 hours ago</p>
                  </div>
                </div>
              </div>
              
              <!-- Activity Item 2 -->
              <div class="p-6 hover:bg-gray-50 transition">
                <div class="flex items-start">
                  <div class="bg-purple-100 p-2 rounded-full mr-4">
                    <i class="fas fa-bookmark text-purple-600 text-sm"></i>
                  </div>
                  <div class="flex-1">
                    <p class="text-sm font-medium text-gray-800">New booking</p>
                    <p class="text-sm text-gray-500 mt-1">Booking #12345 for Everest Base Camp Trek</p>
                    <p class="text-xs text-gray-400 mt-2">5 hours ago</p>
                  </div>
                </div>
              </div>
              
              <!-- Activity Item 3 -->
              <div class="p-6 hover:bg-gray-50 transition">
                <div class="flex items-start">
                  <div class="bg-green-100 p-2 rounded-full mr-4">
                    <i class="fas fa-check-circle text-green-600 text-sm"></i>
                  </div>
                  <div class="flex-1">
                    <p class="text-sm font-medium text-gray-800">Booking approved</p>
                    <p class="text-sm text-gray-500 mt-1">Booking #12344 for Annapurna Circuit approved</p>
                    <p class="text-xs text-gray-400 mt-2">1 day ago</p>
                  </div>
                </div>
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
  </script>
</body>
</html>