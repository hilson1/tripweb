<?php
include __DIR__ . '/auth-check.php';
require "../connection.php";

// Handle booking creation
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['create_booking'])) {
    $trip_id = intval($_POST['trip_id'] ?? 0);
    $trip_name = trim($_POST['trip_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $full_name = trim($_POST['full_name'] ?? '');
    $phone_number = trim($_POST['phone_number'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $country = trim($_POST['country'] ?? '');
    $adults = intval($_POST['adults'] ?? 1);
    $children = intval($_POST['children'] ?? 0);
    $arrival_date = trim($_POST['arrival_date'] ?? '');
    $departure_date = trim($_POST['departure_date'] ?? '');
    $airport_pickup = ($_POST['airport_pickup'] ?? '0') == '1' ? 'yes' : 'no';
    $message = trim($_POST['message'] ?? '');
    $payment_mode = trim($_POST['payment_mode'] ?? 'cash');
    $payment_status = trim($_POST['payment_status'] ?? 'not paid');
    $start_date = trim($_POST['start_date'] ?? date('Y-m-d'));

    // Validate required fields
    if (empty($trip_id) || empty($full_name) || empty($email) || empty($arrival_date) || empty($departure_date)) {
        echo "<script>alert('Please fill all required fields');</script>";
    } else {
        // Check if user exists by email
        $user_id = null;
        $check_user_sql = "SELECT userid FROM users WHERE email = ?";
        $check_stmt = $conn->prepare($check_user_sql);
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if ($result->num_rows > 0) {
            // User exists - get their user_id
            $user_row = $result->fetch_assoc();
            $user_id = $user_row['userid'];
        } else {
            // User doesn't exist - create a guest user record
            $user_id = 'guest_' . uniqid();
            $guest_password = password_hash(bin2hex(random_bytes(16)), PASSWORD_BCRYPT); // Random password
            $status = 'guest';
            
            $insert_user_sql = "INSERT INTO users (userid, email, first_name, last_name, password, status, phone_number, address, country) 
                               VALUES (?, ?, ?, '', ?, ?, ?, ?, ?)";
            $user_stmt = $conn->prepare($insert_user_sql);
            
            // Split full name into first and last name
            $name_parts = explode(' ', $full_name, 2);
            $first_name = $name_parts[0];
            $last_name = isset($name_parts[1]) ? $name_parts[1] : '';
            
            $user_stmt->bind_param("ssssssss", 
                $user_id, 
                $email, 
                $first_name, 
                $guest_password, 
                $status, 
                $phone_number, 
                $address, 
                $country
            );
            
            if (!$user_stmt->execute()) {
                echo "<script>alert('Error creating guest user: " . addslashes($user_stmt->error) . "');</script>";
                $user_stmt->close();
                $check_stmt->close();
                exit;
            }
            $user_stmt->close();
        }
        $check_stmt->close();

        // Now insert the booking with the validated/created user_id
        $sql = "INSERT INTO trip_bookings (
            user_id, trip_id, trip_name, full_name, email, 
            phone_number, address, city, country, adults, 
            children, arrival_date, departure_date, airport_pickup, 
            message, payment_mode, payment_status, start_date
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("<script>alert('Prepare failed: " . addslashes($conn->error) . "');</script>");
        }

        $stmt->bind_param(
            "sisssssssiissssss",
            $user_id,          // s - string
            $trip_id,          // i - integer
            $trip_name,        // s - string
            $full_name,        // s - string
            $email,            // s - string
            $phone_number,     // s - string
            $address,          // s - string
            $city,             // s - string
            $country,          // s - string
            $adults,           // i - integer
            $children,         // i - integer
            $arrival_date,     // s - string (date)
            $departure_date,   // s - string (date)
            $airport_pickup,   // s - string (enum)
            $message,          // s - string
            $payment_mode,     // s - string
            $payment_status,   // s - string
            $start_date        // s - string (date)
        );

        if ($stmt->execute()) {
            $booking_id = $stmt->insert_id;
            echo "<script>alert('Booking created successfully! Booking ID: {$booking_id}'); window.location.href='allbooking.php';</script>";
            exit;
        } else {
            echo "<script>alert('Database error: " . addslashes($stmt->error) . "');</script>";
        }
        $stmt->close();
    }
}

// Fetch trips for booking form
$trips = [];
$trip_query = $conn->query("SELECT tripid, title, triptype FROM trips");
if ($trip_query && $trip_query->num_rows > 0) {
    while ($row = $trip_query->fetch_assoc()) {
        $trips[] = $row;
    }
}

// Fetch existing users for optional autocomplete/search
$users = [];
$user_query = $conn->query("SELECT userid, CONCAT(first_name, ' ', last_name) as full_name, email FROM users WHERE status != 'guest' ORDER BY first_name");
if ($user_query && $user_query->num_rows > 0) {
    while ($row = $user_query->fetch_assoc()) {
        $users[] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Create Booking - ThankYouNepalTrip</title>

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Fonts & Icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="frontend/sidebar.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  
  <style>
    .form-label {
      display: block;
      font-weight: 500;
      margin-bottom: 0.5rem;
      color: #374151;
    }
    .form-input {
      width: 100%;
      padding: 0.5rem 0.75rem;
      border: 1px solid #d1d5db;
      border-radius: 0.375rem;
      font-size: 0.875rem;
    }
    .form-input:focus {
      outline: none;
      border-color: #3b82f6;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    .user-suggestions {
      position: absolute;
      background: white;
      border: 1px solid #d1d5db;
      border-radius: 0.375rem;
      max-height: 200px;
      overflow-y: auto;
      width: 100%;
      z-index: 10;
      display: none;
    }
    .user-suggestion-item {
      padding: 0.5rem 0.75rem;
      cursor: pointer;
      border-bottom: 1px solid #f3f4f6;
    }
    .user-suggestion-item:hover {
      background-color: #f3f4f6;
    }
  </style>
</head>

<body class="bg-gray-50 font-sans leading-normal tracking-normal" x-data="{ sidebarOpen: false }">
  <div class="overlay" :class="{ 'open': sidebarOpen }" @click="sidebarOpen = false"></div>

  <?php include 'frontend/header.php'; ?>
  <?php include 'frontend/sidebar.php'; ?>

  <main class="main-content pt-16 min-h-screen transition-all duration-300">
    <div class="p-6 space-y-10">

      <!-- Booking Form -->
      <div class="glass-effect rounded-2xl shadow-xl p-6 bg-white">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Create New Booking</h1>

        <form method="post" onsubmit="return validateBookingForm(event)">
          <input type="hidden" name="create_booking" value="1">
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <!-- Trip Selection -->
              <label class="form-label">Select Trip *</label>
              <select name="trip_id" id="trip_id" class="form-input" required onchange="updateTripName()">
                <option value="">-- Select a Trip --</option>
                <?php foreach ($trips as $trip): ?>
                  <option value="<?= $trip['tripid'] ?>" data-name="<?= htmlspecialchars($trip['title']) ?>">
                    <?= htmlspecialchars($trip['title']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <input type="hidden" name="trip_name" id="trip_name">
            </div>

            <div>
              <label class="form-label">Booking Date</label>
              <input type="date" name="start_date" id="start_date" class="form-input" value="<?= date('Y-m-d') ?>">
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <div>
              <h2 class="text-lg font-semibold mb-4 text-gray-800">Traveler Information</h2>
              
              <!-- Email field with user lookup -->
              <div class="relative">
                <label class="form-label">Email * <span class="text-xs text-gray-500">(Check if user is already registered)</span></label>
                <input type="email" name="email" id="email" class="form-input" required onblur="checkExistingUser()">
                <div id="user-status" class="text-xs mt-1"></div>
              </div>
              
              <label class="form-label mt-3">Full Name *</label>
              <input type="text" name="full_name" id="full_name" class="form-input" required>
              
              <label class="form-label mt-3">Phone Number *</label>
              <input type="tel" name="phone_number" id="phone_number" class="form-input" required>
            </div>

            <div>
              <h2 class="text-lg font-semibold mb-4 text-gray-800">Travel Details</h2>
              <label class="form-label">Arrival Date *</label>
              <input type="date" name="arrival_date" id="arrival_date" class="form-input" required>
              <label class="form-label mt-3">Departure Date *</label>
              <input type="date" name="departure_date" id="departure_date" class="form-input" required>
              <label class="form-label mt-3">Airport Pickup?</label>
              <select name="airport_pickup" id="airport_pickup" class="form-input">
                <option value="0">No</option>
                <option value="1">Yes</option>
              </select>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <div>
              <label class="form-label">Address</label>
              <textarea name="address" id="address" class="form-input" rows="2"></textarea>
            </div>
            <div>
              <label class="form-label">City</label>
              <input type="text" name="city" id="city" class="form-input">
              <label class="form-label mt-3">Country</label>
              <select name="country" id="country" class="form-input">
                <option value="">Select Country</option>
                <option value="Nepal">Nepal</option>
                <option value="India">India</option>
                <option value="USA">USA</option>
                <option value="UK">UK</option>
                <option value="Australia">Australia</option>
                <option value="Canada">Canada</option>
              </select>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4 mt-6">
            <div>
              <label class="form-label">Adults *</label>
              <input type="number" name="adults" id="adults" class="form-input" min="1" value="1" required>
            </div>
            <div>
              <label class="form-label">Children</label>
              <input type="number" name="children" id="children" class="form-input" min="0" value="0">
            </div>
          </div>

          <div class="mt-6">
            <label class="form-label">Special Message / Requirements</label>
            <textarea name="message" id="message" class="form-input" rows="3" placeholder="Any special requests, dietary requirements, or additional information..."></textarea>
          </div>

          <div class="grid grid-cols-2 gap-6 mt-6">
            <div>
              <label class="form-label">Payment Mode</label>
              <select name="payment_mode" id="payment_mode" class="form-input">
                <option value="cash">Cash</option>
                <option value="credit_card">Credit Card</option>
                <option value="bank_transfer">Bank Transfer</option>
                <option value="online">Online</option>
              </select>
            </div>
            <div>
              <label class="form-label">Payment Status</label>
              <select name="payment_status" id="payment_status" class="form-input">
                <option value="not paid">Not Paid</option>
                <option value="paid">Paid</option>
              </select>
            </div>
          </div>

          <div class="flex justify-end mt-8 space-x-4">
            <button type="button" class="bg-gray-400 hover:bg-gray-500 text-white px-5 py-2 rounded-lg transition" onclick="window.location.href='allbooking.php'">Cancel</button>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition">Create Booking</button>
          </div>
        </form>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

  <script>
    // Store user data for lookup
    const existingUsers = <?= json_encode($users) ?>;

    document.addEventListener('DOMContentLoaded', function() {
      flatpickr("#arrival_date", {
        minDate: "today",
        dateFormat: "Y-m-d",
        onChange: function(selectedDates, dateStr) {
          const dep = document.getElementById('departure_date');
          if (dep._flatpickr) dep._flatpickr.set('minDate', dateStr);
        }
      });
      flatpickr("#departure_date", { minDate: "today", dateFormat: "Y-m-d" });
      flatpickr("#start_date", { dateFormat: "Y-m-d" });
    });

    function updateTripName() {
      const select = document.getElementById('trip_id');
      const hidden = document.getElementById('trip_name');
      const selected = select.options[select.selectedIndex];
      if (selected && selected.dataset.name) hidden.value = selected.dataset.name;
    }

    function checkExistingUser() {
      const emailInput = document.getElementById('email');
      const statusDiv = document.getElementById('user-status');
      const email = emailInput.value.trim().toLowerCase();
      
      if (!email) {
        statusDiv.innerHTML = '';
        return;
      }

      const user = existingUsers.find(u => u.email.toLowerCase() === email);
      
      if (user) {
        statusDiv.innerHTML = '<span class="text-green-600"><i class="fas fa-check-circle"></i> Registered user found: ' + user.full_name + '</span>';
        // Optionally pre-fill the name
        if (document.getElementById('full_name').value === '') {
          document.getElementById('full_name').value = user.full_name;
        }
      } else {
        statusDiv.innerHTML = '<span class="text-blue-600"><i class="fas fa-info-circle"></i> New guest booking - we\'ll create an account for you</span>';
      }
    }

    function validateBookingForm(e) {
      const required = ['trip_id', 'full_name', 'email', 'phone_number', 'arrival_date', 'departure_date', 'adults'];
      for (const id of required) {
        const el = document.getElementById(id);
        if (!el || !el.value.trim()) {
          alert(`Please fill in the ${id.replace('_', ' ')} field.`);
          e.preventDefault();
          return false;
        }
      }
      
      const email = document.getElementById('email').value;
      if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        alert('Please enter a valid email address.');
        e.preventDefault();
        return false;
      }
      
      const phone = document.getElementById('phone_number').value;
      if (!/^[0-9+\-\s()]{10,20}$/.test(phone)) {
        alert('Please enter a valid phone number (10-20 characters).');
        e.preventDefault();
        return false;
      }
      
      const arr = new Date(document.getElementById('arrival_date').value);
      const dep = new Date(document.getElementById('departure_date').value);
      if (arr >= dep) {
        alert('Departure date must be after arrival date.');
        e.preventDefault();
        return false;
      }
      
      const adults = parseInt(document.getElementById('adults').value);
      if (adults < 1) {
        alert('At least one adult is required for booking.');
        e.preventDefault();
        return false;
      }
      
      return true;
    }
  </script>
</body>
</html>