<?php
require "../connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $firstname = trim($_POST['firstName']);
    $lastname = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $username = trim($_POST['userName']);
    $password = trim($_POST['password']);
    $address = trim($_POST['address'] ?? '');
    $zipcode = trim($_POST['zipcode'] ?? '');
    $country = trim($_POST['country'] ?? '');
    $status = trim($_POST['status'] ?? 'active');
    // $email_verified = trim($_POST['email_verified'] ?? 0);
    
    // Generate a unique user ID
    $userid = uniqid('user_');
    
    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    
    // Initialize profile picture path
    $profilepic = '';
    
    // Handle file upload if profile picture is provided
    if (isset($_FILES['profilepic'])) {
        $target_dir = "uploads/profile/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        // Get file extension
        $imageFileType = strtolower(pathinfo($_FILES["profilepic"]["name"], PATHINFO_EXTENSION));
        
        // Generate unique filename
        $new_filename = uniqid() . '.' . $imageFileType;
        $target_file = $target_dir . $new_filename;
        
        // Check if file is an actual image
        $check = getimagesize($_FILES["profilepic"]["tmp_name"]);
        if ($check !== false) {
            if (move_uploaded_file($_FILES["profilepic"]["tmp_name"], $target_file)) {
                $profilepic = $target_file;
            }
        }
    }
    
    // Insert user data into the database
    $sql = "INSERT INTO users (userid, phone_number, address, zip_postal_code, country, first_name, last_name, user_name, email, password, profilepic, status ) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ssssssssssssi", $userid, $phone, $address, $zipcode, $country, $firstname, $lastname, $username, $email, $hashed_password, $profilepic, $status);
        
        if ($stmt->execute()) {
            echo "<script>alert('User created successfully'); window.location.href = 'viewuser.php';</script>";
        } else {
            echo "<script>alert('Error: " . addslashes($stmt->error) . "');</script>";
        }
        
        $stmt->close();
    } else {
        echo "<script>alert('Error preparing statement: " . addslashes($conn->error) . "');</script>";
    }
    
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Create User - ThankYouNepalTrip</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  
  <!-- FontAwesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="frontend/sidebar.css">
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
        <div class="mb-8">
          <div class="gradient-bg rounded-2xl p-6 text-white">
            <h1 class="text-3xl font-bold">
              <i class="fas fa-user  mr-3"></i>Add New User
            </h1>
          </div>
        </div>

          
          <form method="post" enctype="multipart/form-data" onsubmit="return validateForm(event)">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Personal Information -->
              <div>
                <h2 class="text-lg font-semibold mb-4 text-gray-800">Personal Information</h2>
                
                <div class="form-group">
                  <label class="form-label" for="firstName">First Name *</label>
                  <input type="text" name="firstName" id="firstName" class="form-input" required>
                </div>
                
                <div class="form-group">
                  <label class="form-label" for="lastName">Last Name *</label>
                  <input type="text" name="lastName" id="lastName" class="form-input" required>
                </div>
                
                <div class="form-group">
                  <label class="form-label" for="userName">Username *</label>
                  <input type="text" name="userName" id="userName" class="form-input" required>
                </div>
                
                <div class="form-group">
                  <label class="form-label" for="email">Email *</label>
                  <input type="email" name="email" id="email" class="form-input" required>
                </div>
                
                <div class="form-group">
                  <label class="form-label" for="phone">Phone Number *</label>
                  <input type="tel" name="phone" id="phone" class="form-input" required>
                </div>
              </div>
              
              <!-- Additional Information -->
              <div>
                <h2 class="text-lg font-semibold mb-4 text-gray-800">Additional Information</h2>
                
                <div class="form-group">
                  <label class="form-label" for="address">Address</label>
                  <input type="text" name="address" id="address" class="form-input">
                </div>
                
                <div class="form-group">
                  <label class="form-label" for="zipcode">Zip/Postal Code</label>
                  <input type="text" name="zipcode" id="zipcode" class="form-input">
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
                  </select>
                </div>
                
                <div class="form-group">
                  <label class="form-label" for="password">Password *</label>
                  <input type="password" name="password" id="password" class="form-input" required>
                </div>
                
                <div class="form-group">
                  <label class="form-label" for="repeatPassword">Confirm Password *</label>
                  <input type="password" name="repeatPassword" id="repeatPassword" class="form-input" required>
                </div>
                
                <div class="form-group">
                  <label class="form-label" for="profilepic">Profile Picture</label>
                  <input type="file" name="profilepic" id="profilepic" accept="image/*" class="form-input" onchange="previewImage(event)">
                  <img id="profilePreview" src="#" alt="Profile Preview" class="profile-pic-preview hidden">
                </div>
              </div>
            </div>
            
            <div class="flex justify-end space-x-4 mt-8">
              <button type="button" class="btn-secondary" onclick="window.location.href='viewuser.php'">Cancel</button>
              <button type="submit" class="btn-primary">Create User</button>
            </div>
          </form>
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

    function previewImage(event) {
      const input = event.target;
      const preview = document.getElementById('profilePreview');
      
      if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
          preview.src = e.target.result;
          preview.classList.remove('hidden');
        }
        
        reader.readAsDataURL(input.files[0]);
      }
    }

    function validateForm(event) {
      const firstName = document.getElementById('firstName').value.trim();
      const lastName = document.getElementById('lastName').value.trim();
      const userName = document.getElementById('userName').value.trim();
      const email = document.getElementById('email').value.trim();
      const password = document.getElementById('password').value;
      const repeatPassword = document.getElementById('repeatPassword').value;
      const phone = document.getElementById('phone').value.trim();

      // Basic validation
      if (!firstName || !lastName || !userName || !email || !password || !repeatPassword || !phone) {
        alert('Please fill in all required fields.');
        event.preventDefault();
        return false;
      }

      // Password match validation
      if (password !== repeatPassword) {
        alert('Passwords do not match.');
        event.preventDefault();
        return false;
      }

      // Email validation
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailPattern.test(email)) {
        alert('Please enter a valid email address.');
        event.preventDefault();
        return false;
      }

      // Phone validation (basic 10-digit check)
      const phonePattern = /^\d{10}$/;
      if (!phonePattern.test(phone)) {
        alert('Please enter a valid 10-digit phone number.');
        event.preventDefault();
        return false;
      }

      // Password strength check (optional)
      if (password.length < 8) {
        alert('Password should be at least 8 characters long.');
        event.preventDefault();
        return false;
      }

      return true;
    }
  </script>
</body>
</html>