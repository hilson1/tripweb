<!-- Export Buttons -->
<div class="glass-effect bg-[#FAFAFA] rounded-2xl p-6 mb-6 shadow-xl">
<div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
<div class="flex flex-wrap items-center gap-3">
              <h3 class="text-lg font-semibold text-gray-700 mr-4">Export Data:</h3>
              <button onclick="printTable()" class="action-button bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-all">
                <i class="fas fa-print"></i>
                <span class="hidden sm:inline">Print</span>
              </button>
              <button onclick="exportToPDF()" class="action-button bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-all">
                <i class="fas fa-file-pdf"></i>
                <span class="hidden sm:inline">PDF</span>
              </button>
              <button onclick="exportToExcel()" class="action-button bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-all">
                <i class="fas fa-file-excel"></i>
                <span class="hidden sm:inline">Excel</span>
              </button>
            </div>

            <!-- Search and Filter -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
              <div class="flex items-center gap-2">
                <label class="text-gray-600 font-medium" for="entries">Show:</label>
                <select class="search-input border-0 rounded-lg px-3 py-2 outline-none" id="entries" onchange="changeEntries()">
                  <option value="10">10</option>
                  <option value="25">25</option>
                  <option value="50">50</option>
                  <option value="100">100</option>
                </select>
                <span class="text-gray-600">entries</span>
              </div>
              
              <div class="flex items-center gap-2">
                <div class="relative">
                  <input class="search-input border-0 rounded-lg px-4 py-2 pl-10 outline-none w-64" 
                         id="search" 
                         type="text" 
                         placeholder="Search for ..."
                         oninput="searchTable()" />
                  <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
              </div>
            </div>
          </div>
        </div>