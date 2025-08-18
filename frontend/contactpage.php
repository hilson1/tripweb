<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>thankyounepaltrip!</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css"
    rel="stylesheet">
  <link rel="stylesheet" href="index.css">
</head>

<body>
  <?php
  include("header.php");
  ?>
  <header class="hero">
    <h1>Contact Us</h1>
  </header>
  <!-- Contact Section -->
  <div class="container-fluid bg-light py-5">
    <div class="container">
      <div class="row">
        <!-- Form Section -->
        <div class="col-lg-7 mb-4">
          <div class="card p-4 shadow-sm">
            <h3 class="mb-3">Get in touch</h3>
            <form id="contactForm">
              <div class="mb-3">
                <label for="name" class="form-label">Full Name*</label>
                <input type="text" class="form-control" id="name" placeholder="Full name" required>
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email*</label>
                <input type="email" class="form-control" id="email" placeholder="Email" required>
              </div>
              <div class="mb-3">
                <label for="subject" class="form-label">Subject*</label>
                <input type="text" class="form-control" id="subject" placeholder="Write subject" required>
              </div>
              <div class="mb-3">
                <label for="message" class="form-label">Message*</label>
                <textarea class="form-control" id="message" rows="5" placeholder="Write your message"
                  required></textarea>
              </div>
              <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="policy" required>
                <label class="form-check-label" for="policy">
                  By contacting us, you agree to our <a href="#" class="text-decoration-none">Privacy Policy</a>.
                </label>
              </div>
              <button type="submit" class="btn btn-warning w-100">SEND MESSAGE</button>
            </form>
            <div id="successMessage" class="alert alert-success mt-3 d-none" role="alert">
              Your message has been sent successfully!
            </div>
          </div>
        </div>
        <!-- Contact Information Section -->
        <div class="col-lg-5">
          <div class="card p-4 bg-teal text-white shadow-sm">
            <h4>Contact Information</h4>
            <p>Kathmandu<br>Bagmati, Nepal</p>
            <p><i class="bi bi-telephone"></i> (+977) 123-456789<br>(+977) 123-856475</p>
            <p><i class="bi bi-envelope"></i> thankyounepaltrip.com<br>thankyounepaltrip@gmail.com</p>
            <h5 class="mt-4">Follow us on</h5>
            <div>
              <a href="#" class="text-white me-3"><i class="bi bi-facebook"></i></a>
              <a href="#" class="text-white me-3"><i class="bi bi-twitter"></i></a>
              <a href="#" class="text-white me-3"><i class="bi bi-instagram"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="contactus.js"></script>
  <?php
  include("location.php");
  ?>
  <?php
  include("footer.php");
  ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>
</body>

</html>