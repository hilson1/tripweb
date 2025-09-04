<header class="top-navbar fixed top-0 left-0 right-0 h-16 flex items-center justify-between px-6 z-40">
    <!-- Left section - Logo and mobile menu button -->
    <div class="flex items-center">
      <!-- Mobile menu button (hidden on desktop) -->
      <button class="mobile-menu-button mr-4 text-white lg:hidden" @click="sidebarOpen = !sidebarOpen">
        <i class="fas fa-bars text-xl"></i>
      </button>
      
      <!-- Logo (hidden on mobile) -->
      <div class="hidden lg:flex items-center">
        <i class="fas fa-mountain-sun text-white text-2xl mr-2"></i>
        <span class="text-white font-bold text-xl">THANKYOUNEPALTRIP</span>
      </div>
    </div>
    
    <!-- Center section - Search bar -->
    <!-- <div class="flex-1 max-w-2xl mx-4">
      <div class="relative">
        <input 
          type="text" 
          placeholder="Search..." 
          class="search-input w-full py-2 pl-10 pr-4 rounded-lg focus:outline-none"
        >
        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
      </div>
    </div> -->
    
    <!-- Right section - User controls -->
    <div class="flex items-center space-x-4">
      <!-- Notifications -->
      <div class="relative">
        <button class="text-white p-2 rounded-full hover:bg-white hover:bg-opacity-10 transition">
          <i class="fas fa-bell text-xl"></i>
          <span class="notification-badge">3</span>
        </button>
      </div>
      
      <!-- Messages -->
      <div class="relative">
        <button class="text-white p-2 rounded-full hover:bg-white hover:bg-opacity-10 transition">
          <i class="fas fa-envelope text-xl"></i>
          <span class="notification-badge">5</span>
        </button>
      </div>
      
      <!-- User dropdown -->
      <div x-data="{ open: false }" class="relative">
        <button 
          @click="open = !open" 
          class="flex items-center space-x-2 focus:outline-none"
        >
          <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center">
            <i class="fas fa-user text-purple-700"></i>
          </div>
          <span class="text-white font-medium hidden md:inline">Admin</span>
          <i class="fas fa-chevron-down text-white text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
        </button>
        
        <!-- Dropdown menu -->
        <div 
          x-show="open" 
          @click.away="open = false"
          class="user-dropdown absolute right-0 mt-2 w-48 rounded-lg shadow-lg py-1 z-50"
          x-transition:enter="transition ease-out duration-100"
          x-transition:enter-start="transform opacity-0 scale-95"
          x-transition:enter-end="transform opacity-100 scale-100"
          x-transition:leave="transition ease-in duration-75"
          x-transition:leave-start="transform opacity-100 scale-100"
          x-transition:leave-end="transform opacity-0 scale-95"
        >
          <a href="profileview" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">
            <i class="fas fa-user-circle mr-2"></i> Profile
          </a>
          <a href="changepass" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">
            <i class="fas fa-key mr-2"></i> Change Password
          </a>
          <div class="border-t border-gray-200 my-1"></div>
          <a href="logout" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">
            <i class="fas fa-sign-out-alt mr-2"></i> Logout
          </a>
        </div>
      </div>
    </div>
  </header>