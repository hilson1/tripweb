<?php
require "../connection.php";

// Handle admin user creation
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['create_user'])) {
    $userid = uniqid('user_');
    $phone_number = trim($_POST['phone_number'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $zip_postal_code = trim($_POST['zip_postal_code'] ?? '');
    $country = trim($_POST['country'] ?? '');
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $user_name = trim($_POST['user_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password_raw = $_POST['password'] ?? '';
    $profilepic = $_POST['profilepic'] ?? '';
    $status = 'active';
    $reset_token = NULL;
    $reset_expires = NULL;

    if ($email && $password_raw && $first_name && $last_name) {
        $password = password_hash($password_raw, PASSWORD_BCRYPT);

        $sql = "INSERT INTO users (
            userid, phone_number, address, zip_postal_code, country,
            first_name, last_name, user_name, email, password,
            profilepic, status, reset_token, reset_expires
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("<script>alert('Prepare failed: " . addslashes($conn->error) . "');</script>");
        }

        $stmt->bind_param(
            "ssssssssssssss",
            $userid,
            $phone_number,
            $address,
            $zip_postal_code,
            $country,
            $first_name,
            $last_name,
            $user_name,
            $email,
            $password,
            $profilepic,
            $status,
            $reset_token,
            $reset_expires
        );

        if ($stmt->execute()) {
            echo "<script>alert('Admin user created successfully'); window.location.href='view-admins.php';</script>";
            exit;
        } else {
            echo "<script>alert('Database error: " . addslashes($stmt->error) . "');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Please fill all required fields');</script>";
    }
    $conn->close();
}

// Fetch trips for booking form
$trips = [];
$trip_query = $conn->query("SELECT tripid, triptype FROM trips");
if ($trip_query && $trip_query->num_rows > 0) {
    while ($row = $trip_query->fetch_assoc()) {
        $trips[] = $row;
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
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <!-- Trip Selection -->
              <label class="form-label">Select Trip *</label>
              <select name="trip_id" id="trip_id" class="form-input" required onchange="updateTripName()">
                <option value="">-- Select a Trip --</option>
                <?php foreach ($trips as $trip): ?>
                  <option value="<?= $trip['tripid'] ?>" data-name="<?= htmlspecialchars($trip['triptype']) ?>">
                    <?= htmlspecialchars($trip['triptype']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <input type="hidden" name="trip_name" id="trip_name">
            </div>

            <div>
              <label class="form-label">User ID (if registered)</label>
              <input type="number" name="user_id" id="user_id" class="form-input">
            </div>

            <div>
              <label class="form-label">Booking Date</label>
              <input type="date" name="start_date" id="start_date" class="form-input" value="<?= date('Y-m-d') ?>">
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <div>
              <h2 class="text-lg font-semibold mb-4 text-gray-800">Traveler Information</h2>
              <label class="form-label">Full Name *</label>
              <input type="text" name="full_name" id="full_name" class="form-input" required>
              <label class="form-label mt-3">Email *</label>
              <input type="email" name="email" id="email" class="form-input" required>
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
              </select>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4 mt-6">
            <div>
              <label class="form-label">Adults</label>
              <input type="number" name="adults" id="adults" class="form-input" min="1" value="1">
            </div>
            <div>
              <label class="form-label">Children</label>
              <input type="number" name="children" id="children" class="form-input" min="0" value="0">
            </div>
          </div>

          <div class="mt-6">
            <label class="form-label">Special Message</label>
            <textarea name="message" id="message" class="form-input" rows="3"></textarea>
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
                <option value="pending">Pending</option>
                <option value="partial">Partial</option>
                <option value="paid">Paid</option>
                <option value="cancelled">Cancelled</option>
                <option value="refunded">Refunded</option>
              </select>
            </div>
          </div>

          <div class="flex justify-end mt-8 space-x-4">
            <button type="button" class="bg-gray-400 text-white px-5 py-2 rounded-lg" onclick="window.location.href='view_bookings.php'">Cancel</button>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">Create Booking</button>
          </div>
        </form>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

  <script>
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
    });

    function updateTripName() {
      const select = document.getElementById('trip_id');
      const hidden = document.getElementById('trip_name');
      const selected = select.options[select.selectedIndex];
      if (selected && selected.dataset.name) hidden.value = selected.dataset.name;
    }


    function validateBookingForm(e) {
      const required = ['trip_id', 'full_name', 'email', 'phone_number', 'arrival_date', 'departure_date'];
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
        alert('Invalid email.');
        e.preventDefault();
        return false;
      }
      const phone = document.getElementById('phone_number').value;
      if (!/^[0-9]{10,15}$/.test(phone)) {
        alert('Phone must be 10-15 digits.');
        e.preventDefault();
        return false;
      }
      const arr = new Date(document.getElementById('arrival_date').value);
      const dep = new Date(document.getElementById('departure_date').value);
      if (arr >= dep) {
        alert('Departure must be after arrival.');
        e.preventDefault();
        return false;
      }
      return true;
    }
  </script>
</body>
</html>
