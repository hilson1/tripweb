<?php
require "../connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $user_id = trim($_POST['user_id'] ?? '');
    $trip_id = trim($_POST['trip_id'] ?? '');
    $trip_name = trim($_POST['trip_name'] ?? '');
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $phone_number = trim($_POST['phone_number'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $country = trim($_POST['country'] ?? '');
    $adults = trim($_POST['adults'] ?? 1);
    $children = trim($_POST['children'] ?? 0);
    $arrival_date = trim($_POST['arrival_date'] ?? '');
    $departure_date = trim($_POST['departure_date'] ?? '');
    $arrival_time = trim($_POST['arrival_time'] ?? '');
    $airport_pickup = trim($_POST['airport_pickup'] ?? 0);
    $message = trim($_POST['message'] ?? '');
    $payment_mode = trim($_POST['payment_mode'] ?? 'cash');
    $payment_status = trim($_POST['payment_status'] ?? 'pending');
    $start_date = trim($_POST['start_date'] ?? date('Y-m-d'));
    
    // Validate required fields
    if (empty($trip_id) || empty($full_name) || empty($email) || empty($phone_number) || 
        empty($arrival_date) || empty($departure_date)) {
        echo "<script>alert('Please fill in all required fields');</script>";
    } else {
        // Insert booking data into the database
        $sql = "INSERT INTO trip_bookings (
                    user_id, trip_id, trip_name, full_name, email, address, 
                    phone_number, city, country, adults, children, arrival_date, 
                    departure_date, arrival_time, airport_pickup, message, 
                    payment_mode, payment_status, booked_at, start_date
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)";
        
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param(
                "iisssssssiiisssissss", 
                $user_id, $trip_id, $trip_name, $full_name, $email, $address, 
                $phone_number, $city, $country, $adults, $children, $arrival_date, 
                $departure_date, $arrival_time, $airport_pickup, $message, 
                $payment_mode, $payment_status, $start_date
            );
            
            if ($stmt->execute()) {
                echo "<script>alert('Booking created successfully'); window.location.href = 'view_bookings.php';</script>";
            } else {
                echo "<script>alert('Error: " . addslashes($stmt->error) . "');</script>";
            }
            
            $stmt->close();
        } else {
            echo "<script>alert('Error preparing statement: " . addslashes($conn->error) . "');</script>";
        }
    }
    
    $conn->close();
}

// Fetch available trips for dropdown - CORRECTED to use your actual trips table
$trips = [];
$trip_query = "SELECT id, trip_name FROM trips WHERE status = 'active'"; // Assuming you have a 'trips' table
$trip_result = $conn->query($trip_query);
if ($trip_result) {
    while ($row = $trip_result->fetch_assoc()) {
        $trips[] = $row;
    }
} else {
    echo "<script>alert('Error loading trips: " . addslashes($conn->error) . "');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Create Booking - ThankYouNepalTrip</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  
  <!-- FontAwesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="frontend/sidebar.css">
  
  <!-- Flatpickr for date/time picker -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
      <div class="form-container">
        <div class="glass-effect rounded-2xl shadow-xl p-6">
          <h1 class="text-2xl font-bold text-gray-800 mb-6">Create New Booking</h1>
          
          <form method="post" onsubmit="return validateBookingForm(event)">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Trip Information -->
              <div>
                <h2 class="text-lg font-semibold mb-4 text-gray-800">Trip Information</h2>
                
                <div class="form-group">
                  <label class="form-label" for="trip_id">Select Trip *</label>
                  <select name="trip_id" id="trip_id" class="form-input" required onchange="updateTripName()">
                    <option value="">-- Select a Trip --</option>
                    <?php foreach ($trip_bookings as $trip): ?>
                      <option value="<?= $trip['id'] ?>" data-name="<?= htmlspecialchars($trip['name']) ?>">
                        <?= htmlspecialchars($trip['name']) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                  <input type="hidden" name="trip_name" id="trip_name">
                </div>
                
                <div class="form-group">
                  <label class="form-label" for="user_id">User ID (if registered)</label>
                  <input type="number" name="user_id" id="user_id" class="form-input">
                </div>
                
                <div class="form-group">
                  <label class="form-label" for="start_date">Booking Date</label>
                  <input type="date" name="start_date" id="start_date" class="form-input" value="<?= date('Y-m-d') ?>">
                </div>
              </div>
              
              <!-- Traveler Information -->
              <div>
                <h2 class="text-lg font-semibold mb-4 text-gray-800">Traveler Information</h2>
                
                <div class="form-group">
                  <label class="form-label" for="full_name">Full Name *</label>
                  <input type="text" name="full_name" id="full_name" class="form-input" required>
                </div>
                
                <div class="form-group">
                  <label class="form-label" for="email">Email *</label>
                  <input type="email" name="email" id="email" class="form-input" required>
                </div>
                
                <div class="form-group">
                  <label class="form-label" for="phone_number">Phone Number *</label>
                  <input type="tel" name="phone_number" id="phone_number" class="form-input" required>
                </div>
              </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
              <!-- Travel Details -->
              <div>
                <h2 class="text-lg font-semibold mb-4 text-gray-800">Travel Details</h2>
                
                <div class="form-group">
                  <label class="form-label" for="arrival_date">Arrival Date *</label>
                  <input type="date" name="arrival_date" id="arrival_date" class="form-input" required>
                </div>
                
                <div class="form-group">
                  <label class="form-label" for="departure_date">Departure Date *</label>
                  <input type="date" name="departure_date" id="departure_date" class="form-input" required>
                </div>
                
                <div class="form-group">
                  <label class="form-label" for="arrival_time">Arrival Time</label>
                  <input type="time" name="arrival_time" id="arrival_time" class="form-input">
                </div>
                
                <div class="form-group">
                  <label class="form-label" for="airport_pickup">Airport Pickup Needed?</label>
                  <select name="airport_pickup" id="airport_pickup" class="form-input">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                  </select>
                </div>
              </div>
              
              <!-- Additional Information -->
              <div>
                <h2 class="text-lg font-semibold mb-4 text-gray-800">Additional Information</h2>
                
                <div class="form-group">
                  <label class="form-label" for="address">Address</label>
                  <textarea name="address" id="address" class="form-input" rows="2"></textarea>
                </div>
                
                <div class="form-group">
                  <label class="form-label" for="city">City</label>
                  <input type="text" name="city" id="city" class="form-input">
                </div>
                
                <div class="form-group">
                  <label class="form-label" for="country">Country</label>
                  <select name="country" id="country" class="form-input">
                    <option value="">Select Country</option>
                    <option value="Nepal">Nepal</option>
                    <option value="India">India</option>
                    <option value="China">China</option>
                    <option value="USA">United States</option>
                    <option value="UK">United Kingdom</option>
                    <option value="Australia">Australia</option>
                    <option value="Canada">Canada</option>
                  </select>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                  <div class="form-group">
                    <label class="form-label" for="adults">Number of Adults</label>
                    <input type="number" name="adults" id="adults" class="form-input" min="1" value="1">
                  </div>
                  
                  <div class="form-group">
                    <label class="form-label" for="children">Number of Children</label>
                    <input type="number" name="children" id="children" class="form-input" min="0" value="0">
                  </div>
                </div>
              </div>
            </div>
            
            <div class="mt-6">
              <div class="form-group">
                <label class="form-label" for="message">Special Requests/Message</label>
                <textarea name="message" id="message" class="form-input" rows="3"></textarea>
              </div>
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div class="form-group">
                  <label class="form-label" for="payment_mode">Payment Mode</label>
                  <select name="payment_mode" id="payment_mode" class="form-input">
                    <option value="cash">Cash</option>
                    <option value="credit_card">Credit Card</option>
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="online">Online Payment</option>
                  </select>
                </div>
                
                <div class="form-group">
                  <label class="form-label" for="payment_status">Payment Status</label>
                  <select name="payment_status" id="payment_status" class="form-input">
                    <option value="pending">Pending</option>
                    <option value="partial">Partial Payment</option>
                    <option value="paid">Paid</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="refunded">Refunded</option>
                  </select>
                </div>
              </div>
            </div>
            
            <div class="flex justify-end space-x-4 mt-8">
              <button type="button" class="btn-secondary" onclick="window.location.href='view_bookings.php'">Cancel</button>
              <button type="submit" class="btn-primary">Create Booking</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>

  <!-- Alpine JS for dropdown functionality -->
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  
  <!-- Flatpickr for date/time picker -->
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  
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

    // Initialize date pickers
    document.addEventListener('DOMContentLoaded', function() {
      flatpickr("#arrival_date", {
        minDate: "today",
        dateFormat: "Y-m-d",
        onChange: function(selectedDates, dateStr, instance) {
          const departureDate = document.getElementById('departure_date');
          if (departureDate._flatpickr) {
            departureDate._flatpickr.set('minDate', dateStr);
          }
        }
      });
      
      flatpickr("#departure_date", {
        minDate: "today",
        dateFormat: "Y-m-d"
      });
    });

    function updateTripName() {
      const tripSelect = document.getElementById('trip_id');
      const tripNameInput = document.getElementById('trip_name');
      const selectedOption = tripSelect.options[tripSelect.selectedIndex];
      
      if (selectedOption && selectedOption.dataset.name) {
        tripNameInput.value = selectedOption.dataset.name;
      }
    }

    function validateBookingForm(event) {
      const requiredFields = [
        'trip_id', 'full_name', 'email', 'phone_number', 
        'arrival_date', 'departure_date'
      ];
      
      let isValid = true;
      
      // Check required fields
      requiredFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (!field || !field.value.trim()) {
          alert(`Please fill in the ${fieldId.replace('_', ' ')} field.`);
          isValid = false;
          return;
        }
      });
      
      // Email validation
      const email = document.getElementById('email').value;
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailPattern.test(email)) {
        alert('Please enter a valid email address.');
        isValid = false;
      }
      
      // Phone validation
      const phone = document.getElementById('phone_number').value;
      const phonePattern = /^[0-9]{10,15}$/;
      if (!phonePattern.test(phone)) {
        alert('Please enter a valid phone number (10-15 digits).');
        isValid = false;
      }
      
      // Date validation
      const arrivalDate = new Date(document.getElementById('arrival_date').value);
      const departureDate = new Date(document.getElementById('departure_date').value);
      
      if (arrivalDate >= departureDate) {
        alert('Departure date must be after arrival date.');
        isValid = false;
      }
      
      if (!isValid) {
        event.preventDefault();
      }
      
      return isValid;
    }
  </script>
</body>
</html>