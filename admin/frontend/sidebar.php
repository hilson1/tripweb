
<aside 
    class="sidebar fixed top-0 left-0 h-screen shadow-xl overflow-y-auto sidebar-scrollbar transition-transform duration-300 ease-in-out"
    :class="{ 'open': sidebarOpen }"
    style="overflow-x: hidden !important;"
>
    <div class="sidebar-header p-5 text-xl font-bold flex items-center">
      <i class="fas fa-mountain-sun mr-3"></i>
      <span>THANKYOUNEPALTRIP</span>
    </div>
    
    <nav class="p-4 ">
      <ul class="space-y-2">
        <!-- Dashboard -->
        <li>
          <a href="../admin/index" class="nav-link flex items-center p-3 rounded-lg text-white hover:text-white">
            <i class="fas fa-tachometer-alt w-6 text-center mr-3"></i>
            <span>Dashboard</span>
          </a>
        </li>

        <!-- Users Dropdown -->
        <li>
          <div x-data="{ open: false }">
            <button @click="open = !open" class="nav-link flex items-center justify-between w-full p-3 rounded-lg text-white hover:text-white">
              <div class="flex items-center">
                <i class="fas fa-users w-6 text-center mr-3"></i>
                <span>Users</span>
              </div>
              <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
            </button>
            
            <ul x-show="open" x-collapse class="submenu mt-1 ml-2 pl-4 rounded-lg space-y-1">
              <li>
                <a href="createuser" class="submenu-link block py-2 px-3 rounded-md text-gray-200 hover:text-white">
                  <i class="fas fa-plus-circle mr-2 text-xs"></i>
                  Create User
                </a>
              </li>
              <li>
                <a href="viewuser" class="submenu-link block py-2 px-3 rounded-md text-gray-200 hover:text-white">
                  <i class="fas fa-list mr-2 text-xs"></i>
                  User List
                </a>
              </li>
            </ul>
          </div>
        </li>

        <!-- Booking Dropdown -->
        <li>
          <div x-data="{ open: false }">
            <button @click="open = !open" class="nav-link flex items-center justify-between w-full p-3 rounded-lg text-white hover:text-white">
              <div class="flex items-center">
                <i class="fas fa-bookmark w-6 text-center mr-3"></i>
                <span>Booking</span>
              </div>
              <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
            </button>
            
            <ul x-show="open" x-collapse class="submenu mt-1 ml-2 pl-4 rounded-lg space-y-1">
              <li>
                <a href="allbooking?hell=12" class="submenu-link block py-2 px-3 rounded-md text-gray-200 hover:text-white">
                  <i class="fas fa-list mr-2 text-xs"></i>
                  All Bookings
                </a>
              </li>
              <li>
                <a href="createbooking" class="submenu-link block py-2 px-3 rounded-md text-gray-200 hover:text-white">
                  <i class="fas fa-plus-circle mr-2 text-xs"></i>
                  Create Booking
                </a>
              </li>
              <li>
                <a href="approvebooking" class="submenu-link block py-2 px-3 rounded-md text-gray-200 hover:text-white">
                  <i class="fas fa-check-circle mr-2 text-xs"></i>
                  Approved
                </a>
              </li>
              <li>
                <a href="pendingbooking" class="submenu-link block py-2 px-3 rounded-md text-gray-200 hover:text-white">
                  <i class="fas fa-clock mr-2 text-xs"></i>
                  Pending
                </a>
              </li>
              <li>
                <a href="cancelbooking" class="submenu-link block py-2 px-3 rounded-md text-gray-200 hover:text-white">
                  <i class="fas fa-times-circle mr-2 text-xs"></i>
                  Cancelled
                </a>
              </li>
            </ul>
          </div>
        </li>

        <!-- Activities Dropdown -->
        <li>
          <div x-data="{ open: false }">
            <button @click="open = !open" class="nav-link flex items-center justify-between w-full p-3 rounded-lg text-white hover:text-white">
              <div class="flex items-center">
                <i class="fas fa-hiking w-6 text-center mr-3"></i>
                <span>Activities</span>
              </div>
              <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
            </button>
            
            <ul x-show="open" x-collapse class="submenu mt-1 ml-2 pl-4 rounded-lg space-y-1">
              <li>
                <a href="allactivities" class="submenu-link block py-2 px-3 rounded-md text-gray-200 hover:text-white">
                  <i class="fas fa-list mr-2 text-xs"></i>
                  All Activities
                </a>
              </li>
              <li>
                <a href="createactivities" class="submenu-link block py-2 px-3 rounded-md text-gray-200 hover:text-white">
                  <i class="fas fa-plus-circle mr-2 text-xs"></i>
                  Create Activity
                </a>
              </li>
            </ul>
          </div>
        </li>

        <!-- Destination Dropdown -->
        <li>
          <div x-data="{ open: false }">
            <button @click="open = !open" class="nav-link flex items-center justify-between w-full p-3 rounded-lg text-white hover:text-white">
              <div class="flex items-center">
                <i class="fas fa-map-marked-alt w-6 text-center mr-3"></i>
                <span>Destination</span>
              </div>
              <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
            </button>
            
            <ul x-show="open" x-collapse class="submenu mt-1 ml-2 pl-4 rounded-lg space-y-1">
              <li>
                <a href="alldestination" class="submenu-link block py-2 px-3 rounded-md text-gray-200 hover:text-white">
                  <i class="fas fa-list mr-2 text-xs"></i>
                  All Destinations
                </a>
              </li>
              <li>
                <a href="createdestination" class="submenu-link block py-2 px-3 rounded-md text-gray-200 hover:text-white">
                  <i class="fas fa-plus-circle mr-2 text-xs"></i>
                  Create Destination
                </a>
              </li>
            </ul>
          </div>
        </li>

        <!-- Trips Types Dropdown -->
        <li>
          <div x-data="{ open: false }">
            <button @click="open = !open" class="nav-link flex items-center justify-between w-full p-3 rounded-lg text-white hover:text-white">
              <div class="flex items-center">
                <i class="fas fa-tags w-6 text-center mr-3"></i>
                <span>Trips Types</span>
              </div>
              <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
            </button>
            
            <ul x-show="open" x-collapse class="submenu mt-1 ml-2 pl-4 rounded-lg space-y-1">
              <li>
                <a href="alltriptype" class="submenu-link block py-2 px-3 rounded-md text-gray-200 hover:text-white">
                  <i class="fas fa-list mr-2 text-xs"></i>
                  All Types
                </a>
              </li>
              <li>
                <a href="createtriptype" class="submenu-link block py-2 px-3 rounded-md text-gray-200 hover:text-white">
                  <i class="fas fa-plus-circle mr-2 text-xs"></i>
                  Create Type
                </a>
              </li>
            </ul>
          </div>
        </li>

        <!-- Itinerary Dropdown -->
        <li>
          <div x-data="{ open: false }">
            <button @click="open = !open" class="nav-link flex items-center justify-between w-full p-3 rounded-lg text-white hover:text-white">
              <div class="flex items-center">
                <i class="fas fa-route w-6 text-center mr-3"></i>
                <span>Itinerary</span>
              </div>
              <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
            </button>
            
            <ul x-show="open" x-collapse class="submenu mt-1 ml-2 pl-4 rounded-lg space-y-1">
              <li>
                <a href="allitinerary" class="submenu-link block py-2 px-3 rounded-md text-gray-200 hover:text-white">
                  <i class="fas fa-list mr-2 text-xs"></i>
                  All Itineraries
                </a>
              </li>
              <li>
                <a href="createitinerary" class="submenu-link block py-2 px-3 rounded-md text-gray-200 hover:text-white">
                  <i class="fas fa-plus-circle mr-2 text-xs"></i>
                  Create Itinerary
                </a>
              </li>
            </ul>
          </div>
        </li>

         <!-- Highlight Dropdown -->
        <li>
          <div x-data="{ open: false }">
            <button @click="open = !open" class="nav-link flex items-center justify-between w-full p-3 rounded-lg text-white hover:text-white">
              <div class="flex items-center">
                <i class="fas fa-route w-6 text-center mr-3"></i>
                <span>Highlight</span>
              </div>
              <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
            </button>
            
            <ul x-show="open" x-collapse class="submenu mt-1 ml-2 pl-4 rounded-lg space-y-1">
              <li>
                <a href="allhighlight" class="submenu-link block py-2 px-3 rounded-md text-gray-200 hover:text-white">
                  <i class="fas fa-list mr-2 text-xs"></i>
                  All Highlight
                </a>
              </li>
              <li>
                <a href="createhighlight" class="submenu-link block py-2 px-3 rounded-md text-gray-200 hover:text-white">
                  <i class="fas fa-plus-circle mr-2 text-xs"></i>
                  Create Highlight
                </a>
              </li>
            </ul>
          </div>
        </li>

         <!-- FAQ Dropdown -->
        <li>
          <div x-data="{ open: false }">
            <button @click="open = !open" class="nav-link flex items-center justify-between w-full p-3 rounded-lg text-white hover:text-white">
              <div class="flex items-center">
                <i class="fas fa-route w-6 text-center mr-3"></i>
                <span>FAQS</span>
              </div>
              <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
            </button>
            
            <ul x-show="open" x-collapse class="submenu mt-1 ml-2 pl-4 rounded-lg space-y-1">
              <li>
                <a href="allfaq" class="submenu-link block py-2 px-3 rounded-md text-gray-200 hover:text-white">
                  <i class="fas fa-list mr-2 text-xs"></i>
                  All Faqs
                </a>
              </li>
              <li>
                <a href="createfaq" class="submenu-link block py-2 px-3 rounded-md text-gray-200 hover:text-white">
                  <i class="fas fa-plus-circle mr-2 text-xs"></i>
                  Create Faqs
                </a>
              </li>
            </ul>
          </div>
        </li>

        <!-- costs Dropdowm -->
        <li>
          <div x-data="{ open: false }">
            <button @click="open = !open" 
                    class="nav-link flex items-center justify-between w-full p-3 rounded-lg text-white hover:text-white">
              <div class="flex items-center">
                <i class="fas fa-money-bill-wave w-6 text-center mr-3"></i>
                <span>Trip Costs</span>
              </div>
              <i class="fas fa-chevron-down text-xs transition-transform duration-200" 
                :class="{ 'rotate-180': open }"></i>
            </button>

            <ul x-show="open" x-collapse class="submenu mt-1 ml-2 pl-4 rounded-lg space-y-1">
              <li>
                <a href="allcost" class="submenu-link block py-2 px-3 rounded-md text-gray-200 hover:text-white">
                  <i class="fas fa-list mr-2 text-xs"></i>
                  All Costs
                </a>
              </li>
              <li>
                <a href="createcost" class="submenu-link block py-2 px-3 rounded-md text-gray-200 hover:text-white">
                  <i class="fas fa-plus-circle mr-2 text-xs"></i>
                  Create Cost
                </a>
              </li>
            </ul>
          </div>
        </li>

        <!-- Trips Dropdown -->
        <li>
          <div x-data="{ open: false }">
            <button @click="open = !open" class="nav-link flex items-center justify-between w-full p-3 rounded-lg text-white hover:text-white">
              <div class="flex items-center">
                <i class="fas fa-suitcase-rolling w-6 text-center mr-3"></i>
                <span>Trips</span>
              </div>
              <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
            </button>
            
            <ul x-show="open" x-collapse class="submenu mt-1 ml-2 pl-4 rounded-lg space-y-1">
              <li>
                <a href="alltrip" class="submenu-link block py-2 px-3 rounded-md text-gray-200 hover:text-white">
                  <i class="fas fa-list mr-2 text-xs"></i>
                  All Trips
                </a>
              </li>
              <li>
                <a href="createtrip" class="submenu-link block py-2 px-3 rounded-md text-gray-200 hover:text-white">
                  <i class="fas fa-plus-circle mr-2 text-xs"></i>
                  Create Trip
                </a>
              </li>
              <li>
                <a href="add-trip-images" class="submenu-link block py-2 px-3 rounded-md text-gray-200 hover:text-white">
                  <i class="fas fa-images mr-2 text-xs"></i>
                  Add Trip Images
                </a>
              </li>
            </ul>
          </div>
        </li>


      </ul>
    </nav>
    
    <!-- Sidebar Footer -->
    <div class="p-4 mt-auto text-xs text-gray-300 text-center">
      © 2023 ThankYouNepalTrip<br>
      All Rights Reserved
    </div>
  </aside>